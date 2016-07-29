<?php
use ExBB\Request;
use ExBB\Session;

/**
 * Class BaseController
 */
abstract class BaseController {
	/**
	 * @var \ExBB\Request
	 */
	protected $request;
	/**
	 * @var \ExBB\Session
	 */
	protected $session;

	/**
	 * @var string
	 */
	protected $viewPath;

	/**
	 * @var string
	 */
	protected $modelPath;

	/**
	 * @var array
	 */
	protected $page = [
		'title' => '',
		'header' => '',
	];

	public function __construct() {
		$this->request = new Request();
		$this->session = new Session();

		$this->viewPath = __DIR__.'/template';
		$this->modelPath = __DIR__.'/models';

		$this->page['title'] = lang('installation');
	}

	abstract public function ActionIndex();

	/**
	 * ��������� ������
	 *
	 * @param string $model �������� ������
	 *
	 * @return mixed
	 */
	protected function loadModel($model) {
		require $this->modelPath . '/' . $model . '.php';

		$className = 'Model'.ucfirst($model);

		return new $className;
	}

	/**
	 * ���������� ������ ������ ��������
	 *
	 * @param string $view �������� �������
	 * @param array $data ������ ��� �������
	 *
	 * @return string
	 */
	protected function render($view, $data=[]) {
		ob_start();
		$content = $this->view($view, $data);
		extract($this->page);

		include $this->viewPath . '/template.php';
		return ob_get_clean();
	}

	/**
	 * ���������� ��������� ������
	 *
	 * @param string $view �������� �������
	 * @param array $data ������ ��� �������
	 *
	 * @return string
	 */
	protected function view($view, $data=[]) {
		extract($data);

		ob_start();
		include $this->viewPath . '/' . $view . '.php';

		return ob_get_clean();
	}

	/**
	 * ��������� ������������� �� ��������� URL
	 *
	 * @param string $url URL ��� �������������
	 * @param int $method
	 */
	protected function redirect($url, $method=null) {
		header('Location: '.$url);
		die;
	}
}