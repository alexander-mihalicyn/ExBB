<?php
require __DIR__.'/Converter.php';

/**
 * Class Controller
 */
class Controller extends BaseController {
	private $converter;

	public function __construct() {
		parent::__construct();

		//init converter...
		$this->converter = new Converter();

		//fill controller with information from converter data structures...
		$this->page['stepsList'] = $this->converter->stepsList;
		$this->page['title'] = lang('convertation');
	}

	public function __destruct() {
		unset( $this->converter );
	}

	public function ActionIndex() {
		//show main page of converter
		return $this->render( 'index' );
	}

	public function ActionTest() {
		//for dev
		$this->converter->doTest();
	}

	public function ActionBackend() {
		//do main job
		$this->converter->doWork();

		//prepare content for user interface (will be sent to frontend)
		$data = [
			'contentHTML' => $this->view(
				$this->page['stepsList'][ $this->converter->getActiveStep() ]['tplname'],
				$this->converter->getHTMLPageData()
			),
		];

		//send data to frontend
		return json_encode( array_merge( $data, $this->converter->getState() ) );
	}
}