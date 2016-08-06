<?php

use ExBB\DataBase\FileDB;

class Converter {
	public $stepsList;
	//private $activeStep;
	private $state;

	private $debug = false;

	private $stepCountFiles = 1000;

	private $fileDB;
	private $stateFile;

	private $htmlPageData = [
		'mainContent' => '',
		'mainTitle' => '',
	];

	public function __construct() {
		$this->stepsList = [
			[
				'tplname' => 'welcome',//to do clean that because it's not good to choose templates in converter class
				'title' => lang('stepConvertationWelcome'),
			],

			[
				'tplname' => 'datamoving',
				'title' => lang('stepMovingDirectoriesAndFiles'),
			],

			[
				'tplname' => 'charsetconvert',
				'title' => lang('stepCharsetConvertation'),
			],

			[
				'tplname' => 'commontpl',
				'title' => lang('stepFinish'),
			],
		];

		$this->initConverterState();
	}

	public function __destruct() {
		//save converter state to file...
		$this->saveState();

		unset( $this->fileDB );
	}

	public function getActiveStep() {
		return $this->state[ 'activeStep' ];
	}

	public function doTest() {
		$this->debug = true;
		$this->processFile( './../data/allforums.php' );
	}

	public function doWork() {
		if ( $this->state[ 'error' ] )
			return;

		//it's ok we can do our job...
		if ( $this->state[ 'activeStepFinished' ] ) $this->state[ 'activeStep' ]++;
/*
		$this->state[ 'converterDone' ] = false;
		if ( $this->state[ 'activeStep' ] >= 3 ) {
			$this->state[ 'activeStep' ] = 2;

			unset( $this->state[ 'fileList' ] );
		}
*/
		if ( $this->state[ 'activeStep' ] == 0 ) {
			$this->state[ 'activeStep' ] = 1;
			$this->doRename();
			$this->state[ 'activeStepFinished' ] = true;
			return;
		}

		if ( $this->state[ 'activeStep' ] == 2 ) {
			$this->state[ 'activeStepFinished' ] = false;
			$this->doConvertEncoding();
		}

		if ( $this->state[ 'activeStep' ] == 3 ) {
			$this->htmlPageData = [
				'mainContent' => '',
				'mainTitle' => lang( 'finishText' ),
			];

			//stops increasing step counter
			$this->state[ 'activeStepFinished' ] = false;

			$this->state[ 'converterDone' ] = true;
		}
	}

	public function getState() {
		return $this->state;
	}

	public function getHTMLPageData() {
		return $this->htmlPageData;
	}

	private function doRename() {
		$list = [
			[ 'messages', 'data/messages' ],
			[ 'search', 'data/search' ],
			[ 'members', 'data/members' ],
			[ 'data/access_log', 'data/logs' ],
		];

		if ( is_dir( './../data/banned_users' ) )
			$list[] = [ 'data/banned_users', 'data/banned' ];

		//preparing $list...

		//$modules_list = array_diff( scandir( './../modules' ), array( '..', '.', 'index.html' ) );
		//print_r( $modules_list );

		//process modules...
		$dir = './../modules/';
		mkdir( './../data/modules' );
		//$except_modules = [ 'watches', 'rss', 'karma', 'belong', 'chat', 'userstop', ];
		if ( is_dir( $dir ) && ( $dh = opendir( $dir ) ) ) {
			while ( ( $file = readdir( $dh ) ) !== false ) {
				if ( filetype( $dir . $file ) === 'dir' && $file !== '.' && $file !== '..'/* &&
						( in_array( $file, $except_modules ) === false ) */
						&& is_dir( $dir . $file . '/data' ) ) {
					if ( $file === 'statvisit' ) {
						$list[] = [
							'modules/' . $file . '/data', 'data/modules/advanced_stats'
						];
					} else if ( $file === 'birstday' ) {
						$list[] = [
							'modules/' . $file . '/data', 'data/modules/birthday'
						];	
					} else {
						$list[] = [
							'modules/' . $file . '/data', 'data/modules/' . $file
						];
					}
				}
			}
			closedir( $dh );
		}

		$list[] = [ 'data/modules/birthday/birstday_data.php', 'data/modules/birthday/data.php' ];
		$list[] = [ 'data/modules/karma/karmalog.php', 'data/modules/karma/log.php' ];
		$list[] = [ 'data/modules/userstop/userstop_data.php', 'data/modules/userstop/data.php' ];
		//eof process modules

		//process forums...
		$dir = './../';
		mkdir( './../data/forums' );
		if ( is_dir( $dir ) && ( $dh = opendir( $dir ) ) ) {
			while ( ( $file = readdir( $dh ) ) !== false ) {
				if ( ( filetype( $dir . $file ) === 'dir' ) && preg_match( '/forum([0-9]+)/', $file, $matches ) ) {
					$list[] = [
						$file, 'data/forums/' . $matches[1]
					];
				}
			}
			closedir( $dh );
		}
		//eof process forums

		//prepare info for user...
		$this->htmlPageData = [
			//'info' => 

			//debug...
			'mainContent' => print_r( $list, true ),
		];

		foreach ( $list as $item ) {
			if ( !@rename( './../' . $item[ 0 ], './../' . $item[ 1 ] ) ) {
				//handle error...
				if ( empty( $this->htmlPageData[ 'error' ] ) )
					$this->htmlPageData[ 'error' ] = lang( 'problemfilesdirslist' ) . "\n";

				$this->htmlPageData[ 'error' ] .= $item[ 0 ] . " -> " . $item[ 1 ] . "\n";
 			}
		}

		if ( !empty( $this->htmlPageData[ 'error' ] ) )
			$this->handleError( print_r( $this->htmlPageData[ 'error' ], true ) );
	}

	private function doConvertEncoding() {
		if ( !isset( $this->state[ 'fileList' ] ) ) $this->makeFileListForEncodingConversion();

		if ( !count( $this->state[ 'fileList' ] ) )
			$this->state[ 'activeStepFinished' ] = true;

		$processedFilesCount = 0;
		foreach ( $this->state[ 'fileList' ] as $key=>$item ) {
			$this->processFile( $item );
			unset( $this->state[ 'fileList' ][ $key ] );

			$processedFilesCount++;

			if ( $processedFilesCount >= $this->stepCountFiles ) {
				break;
			}
		}

		//prepare info for user...
		$this->htmlPageData = [
			'mainContent' => print_r( $this->state[ 'fileList' ], true ),
		];

		//sleep(1);
	}

	private function makeFileListForEncodingConversion() {
		$this->state[ 'fileList' ] = [];

		//let's go fucking crazy (c) Ozzy :D
		$dir = './../data';
		$dirIterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator( $dir, \RecursiveDirectoryIterator::SKIP_DOTS ),
			\RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ( $dirIterator as $item ) {
			if ( !$item->isFile() ) continue;

			$this->state[ 'fileList' ][] = $item->getPathname();
		}

		$this->state[ 'filesCount' ] = count( $this->state[ 'fileList' ] );
		//$this->state[ '_fileList' ] = $this->state[ 'fileList' ];
	}

	//that helper method checks that $var can be used is iterable entity
	private function isIterable( $var ) {
		return $var !== null
			&& (is_array($var)
				|| $var instanceof Traversable
				|| $var instanceof Iterator
				|| $var instanceof IteratorAggregate
				);
	}

	private function processFile( $filePath ) {
		$fileContent = file_get_contents( $filePath );

		$fileContent_ = substr( $fileContent, 8 );
		$fileData = @unserialize( $fileContent_ );

		//check that data unserialized without errors
		if ( $fileData !== false || $fileContent_ === 'b:0;' ) {
			unset( $fileContent_ );

			//handle arrays...
			if ( $this->isIterable( $fileData ) )
				$fileData = $this->convertCP1251toUTF8Array( $fileData );
			//handle strings...
			else if ( is_string( $fileData ) )
				$this->convertCP1251toUTF8( $fileData );
			//bools and number, null no need charset conversion :)
			//...

			$fileContent = '<?die;?>' . serialize( $fileData );
		} else {
			//handle sqlite files... may be need handle only sqlite2?...
			//WARNING! this construction is dangerous because we can unlink no sqlite db files
			//this check need rewriting
			if ( strpos( $fileContent, 'SQLite' ) !== false ) {
				//delete... because most modules can regenerate sqlite files
				unlink( $filePath );
				return;
			}

			$this->convertCP1251toUTF8( $fileContent );
		}

		file_put_contents( $filePath, $fileContent );
	}

	private function initConverterState() {
		$this->fileDB = new FileDB();
		$this->stateFile = $this->fileDB->open( 'converter.state.php', FileDB::MODE_READWRITE );
		$this->state = $this->stateFile->read();

		if ( empty( $this->state ) ) {
			$this->state = [
				'activeStep' => 0,
				'activeStepFinished' => false,
				'error' => null,
				'converterDone' => false,
			];
		}
	}

	private function handleError( $message ) {
		$this->state[ 'error' ] = ( $message ) ? $message : true;
	}

	private function debugPrint( $data ) {
		if ( !isset( $this->debug ) || !$this->debug ) return;
		print '<pre>';
		print_r( $data );
		print '</pre>';
	}

	private function saveState() {
		$this->stateFile->write( $this->state );
		$this->stateFile->close();
	}

/*for tests only!
	private function convertCP1251toUTF8Array( &$array ) {
		foreach ( $array as &$value ) {
			if ( is_string( $value ) ) {
				$values = [];

				if ( function_exists( 'mb_convert_encoding' ) )
					$values[] = mb_convert_encoding( $value, 'UTF-8', 'Windows-1251' );

				if ( function_exists( 'iconv' ) )
					$values[] = iconv( 'Windows-1251', 'UTF-8', $value );

				if ( ( count( $values ) === 2 ) && $values[0] !== $values[1] )
					throw new \Exception( 'fail when convert encoding' );

				$value = $values[0];
			} else if ( is_array( $value ) ) {
				$this->convertCP1251toUTF8Array( $value );
			}
		}
	}
*/

	private function convertCP1251toUTF8Array( $array ) {
		$result_array = [];

		//$this->debugPrint( $array );

		foreach ( $array as $key=>$value ) {
			if ( is_string( $value ) ) {
				$this->convertCP1251toUTF8( $value );
			} else if ( is_array( $value ) ) {
				$value = $this->convertCP1251toUTF8Array( $value );
			}

			//handle string keys...
			if ( is_string( $key ) ) {
				$this->convertCP1251toUTF8( $key );
			}

			$result_array[ $key ] = $value;
		}

		return $result_array;
	}

	private function convertCP1251toUTF8( &$value ) {
		if ( function_exists( 'mb_convert_encoding' ) ) {
			//conversion of string two times cracks her
			//if ( mb_detect_encoding( $value ) == 'UTF-8' ) return;

			$value = mb_convert_encoding( $value, 'UTF-8', 'Windows-1251' );
			return;
		}

		if ( function_exists( 'iconv' ) )
			$value = iconv( 'Windows-1251', 'UTF-8', $value );
	}

/*fast variant
	private function convertCP1251toUTF8( &$value ) {
		$value = mb_convert_encoding( $value, 'UTF-8', 'Windows-1251' );
	}
*/
/*
	private function convertCP1251toUTF8Array( &$array ) {

	}

	private function convertCP1251toUTF8( &$value ) {

	}
*/
}

?>