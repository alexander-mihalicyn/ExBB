<?php
if (!defined('IN_EXBB')) {
	die( 'Hack attempt!' );
}

class UPLOAD {
	/*
		Флаг русской локали boolean
	*/
	var $_TEMPNAME = '';
	var $_NAME = '';
	var $_SIZE = '';
	var $_TYPE = '';
	var $_IMAGE = '';

	var $_REQUEST_TYPE = '.*';
	var $_TARFILE = false;

	public function __construct($maxsize) {
		$this->_TEMPNAME = $_FILES['FILE_UPLOAD']['tmp_name'];
		$this->_NAME = $_FILES['FILE_UPLOAD']['name'];
		$this->_SIZE = $_FILES['FILE_UPLOAD']['size'];
		$this->_TYPE = $_FILES['FILE_UPLOAD']['type'];

		switch ($_FILES['FILE_UPLOAD']['error']) {
			case 1:
				define("UP_ERROR", 'Ошибка загрузки файла! Размер файла больше установленного в директиве upload_max_filesize в php.ini');
			break;
			case 2:
				define("UP_ERROR", 'Ошибка загрузки файла! Размер файла больше разрешенного на форуме!');
			break;
			case 3:
				define("UP_ERROR", 'Ошибка загрузки файла! Файл загружен только частично!');
			break;
			case 4:
				define("UP_NOADDED", true);
			break;
			default:
			break;
		}

		if (!defined("UP_ERROR") && !defined("UP_NOADDED")) {
			if ($this->_NAME == "" || $this->_NAME == 'none') {
				define("UP_ERROR", 'Ошибка загрузки файла! Не указано имя закачиваемого файла!');;
			}
			elseif ($this->_SIZE > $maxsize) {
				define("UP_ERROR", 'Ошибка загрузки файла! Размер загруженного файла больше, чем разрешено на форуме!');
			}
			elseif ($this->_SIZE == 0) {
				define("UP_ERROR", 'Ошибка загрузки файла! Размер закачанного файла 0 b!');
			}
		}
	}

	function DoUpload($mode, $destination, $storage_name, $max_width = 0, $max_height = 0) {
		$this->_IMAGE = @getimagesize($this->_TEMPNAME);
		$mode = ( $this->_IMAGE !== false && $mode !== 'image' ) ? 'image' : $mode;
		if ($mode === 'image') {
			if ($this->_IMAGE === false) {
				define("UP_ERROR", 'Ошибка загрузки файла! Загружаемый файл не является правильным изображением!');

				return false;
			}

			return $this->IMAGE($max_width, $max_height, $destination, $storage_name);
		}
		else {
			return $this->FILE($destination, $storage_name);
		}
	}

	function FILE($dest, $storage) {
		if (!preg_match("#\.(" . $this->_REQUEST_TYPE . ")$#is", $this->_NAME)) {
			define("UP_ERROR", 'Ошибка загрузки файла! Запрещенное расширение файла!');

			return false;
		}
		$storage = $storage . '.ext';

		if ($this->_TARFILE === true) {
			if (( $filetype = $this->_TarAttach($dest . $storage) ) === false) {
				define("UP_ERROR", 'Ошибка загрузки файла! Не могу упаковать файл!');

				return false;
			}
		}
		else {
			if (!move_uploaded_file($this->_TEMPNAME, $dest . $storage)) {
				define("UP_ERROR", 'Ошибка загрузки файла! Сервер не смог скопировать файл в директорию ' . $dest . '. Проверьте права на эту папку!');

				return false;
			}
			$filetype = 'file';
		}

		return array( 'NAME' => $this->_NAME, 'STORAGE' => $storage, 'SIZE' => $this->_SIZE, 'TYPE' => $filetype );
	}

	function IMAGE($_width, $_height, $dest, $storage) {

		list( $width, $height ) = $this->_IMAGE;
		if ($width > $_width || $height > $_height) {
			define("UP_ERROR", 'Ошибка загрузки файла! Ширина или высота закачиваемого изображения больше разрешенных на форуме!');

			return false;
		}

		if (!preg_match('#image\/[x\-]*(jpeg|pjpeg|jpg|gif|png|bmp|tiff)#', $this->_TYPE, $type)) {
			define("UP_ERROR", 'Ошибка загрузки файла! Запрещенное расширение файла!');

			return false;
		}

		switch ($type[1]) {
			case 'jpeg':
			case 'pjpeg':
			case 'jpg':
				$ext = '.jpg';
			break;
			case 'gif':
				$ext = '.gif';
			break;
			case 'png':
				$ext = '.png';
			break;
			case 'bmp':
				$ext = '.bmp';
			break;
			case 'tiff':
				$ext = '.tiff';
			break;
		}

		$storage = $storage . $ext;
		if (!move_uploaded_file($this->_TEMPNAME, $dest . $storage)) {
			define("UP_ERROR", 'Ошибка загрузки файла! Сервер не смог скопировать файл в директорию ' . $dest . '. Проверьте права на эту папку!');

			return false;
		}

		return array( 'NAME' => $this->_NAME, 'STORAGE' => $storage, 'SIZE' => $this->_SIZE, 'TYPE' => 'image', 'WIDTH' => $width, 'HEIGHT' => $height );
	}

	function _TarAttach($tarfile) {
		$stat = stat($this->_TEMPNAME);

		if (!is_array($stat) || !is_file($this->_TEMPNAME) || ( $fp = fopen($this->_TEMPNAME, 'rb') ) === false) {
			//define("UP_ERROR",'Это не массив в результате работы stat');
			return false;
		}

		$data = fread($fp, filesize($this->_TEMPNAME));
		fclose($fp);


		$files[0] = array( 'name' => $this->_NAME, 'mode' => fileperms($this->_TEMPNAME), 'uid' => $stat[4], 'gid' => $stat[5], 'size' => strlen($data), 'mtime' => filemtime($this->_TEMPNAME), 'typeflag' => 0, 'linkname' => "", 'uname' => 'unknown', 'gname' => 'unknown', 'data' => $data );

		$uploadtime = date("F j, Y, H:i:s");
		$readme = "Upload time: $uploadtime\r\nFile saved from http://" . $_SERVER['SERVER_NAME'];

		$files[1] = array( 'name' => "readme.txt", 'mode' => 33152, 'uid' => $stat[4], 'gid' => $stat[5], 'size' => strlen($readme), 'mtime' => time(), 'typeflag' => 0, 'linkname' => "", 'uname' => 'unknown', 'gname' => 'unknown', 'data' => $readme );
		unset( $stat );
		clearstatcache();

		$tardata = "";
		foreach ($files as $file) {
			$prefix = "";
			$tmp = "";
			$last = "";
			if (strlen($file['name']) > 99) {
				$pos = strrpos($file['name'], "/");
				if (is_string($pos) && !$pos) {
					//define("UP_ERROR",'if (is_string(\$pos) && !\$pos)');
					return false;
				}

				$prefix = substr($file['name'], 0, $pos);
				$file['name'] = substr($file['name'], ( $pos + 1 ));

				if (strlen($prefix) > 154) {
					//define("UP_ERROR",'if (strlen(\$prefix) > 154)');
					return false;
				}
			}

			$mode = sprintf("%6s ", decoct($file['mode']));
			$uid = sprintf("%6s ", decoct($file['uid']));
			$gid = sprintf("%6s ", decoct($file['gid']));
			$size = sprintf("%11s ", decoct($file['size']));
			$mtime = sprintf("%11s ", decoct($file['mtime']));
			$tmp = pack("a100a8a8a8a12a12", $file['name'], $mode, $uid, $gid, $size, $mtime);
			$last = pack("a1", $file['typeflag']);
			$last .= pack("a100", $file['linkname']);
			$last .= pack("a6", "ustar"); // magic
			$last .= pack("a2", ""); // version
			$last .= pack("a32", $file['uname']);
			$last .= pack("a32", $file['gname']);
			$last .= pack("a8", ""); // devmajor
			$last .= pack("a8", ""); // devminor
			$last .= pack("a155", $prefix);

			$test_len = $tmp . $last . "12345678";
			$last .= $this->internal_build_string("\0", ( 512 - strlen($test_len) ));
			$checksum = 0;

			for ($i = 0; $i < 148; $i++) {
				$checksum += ord(substr($tmp, $i, 1));
			}

			for ($i = 148; $i < 156; $i++) {
				$checksum += ord(' ');
			}

			for ($i = 156, $j = 0; $i < 512; $i++, $j++) {
				$checksum += ord(substr($last, $j, 1));
			}

			$checksum = sprintf("%6s ", decoct($checksum));
			$tmp .= pack("a8", $checksum);
			$tmp .= $last;
			$tmp .= $file['data'];
			if ($file['size'] > 0) {
				if ($file['size'] % 512 != 0) {
					$homer = $this->internal_build_string("\0", ( 512 - ( $file['size'] % 512 ) ));
					$tmp .= $homer;
				}
			}
			$tardata .= $tmp;
		}

		$tardata .= pack("a512", "");
		if (extension_loaded("zlib")) {
			$tardata = gzencode($tardata, 9);
			$fp = fopen($tarfile, 'wb');
			fwrite($fp, $tardata, strlen($tardata));
			fclose($fp);
			$gzip = 'gz';
		}
		else {
			$fp = fopen($tarfile, 'wb');
			fputs($fp, $tardata, strlen($tardata));
			fclose($fp);
			$gzip = 'tar';
		}
		$this->_SIZE = filesize($tarfile);

		return $gzip;
	}

	function internal_build_string($string = "", $times = 0) {
		$return = "";
		for ($i = 0; $i < $times; ++$i) {
			$return .= $string;
		}

		return $return;
	}
}

?>