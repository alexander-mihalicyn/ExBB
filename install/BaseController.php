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
	 * «агружает модель
	 *
	 * @param string $model название модели
	 *
	 * @return mixed
	 */
	protected function loadModel($model) {
		require $this->modelPath . '/' . $model . '.php';

		$className = 'Model'.ucfirst($model);

		return new $className;
	}

	/**
	 * ќтображает полный шаблон страницы
	 *
	 * @param string $view название шаблона
	 * @param array $data данные дл€ шаблона
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
	 * ќтображает указанный шаблон
	 *
	 * @param string $view название шаблона
	 * @param array $data данные дл€ шаблона
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
	 * ¬ыполн€ет переадресацию на указанный URL
	 *
	 * @param string $url URL дл€ переадресации
	 * @param int $method
	 */
	protected function redirect($url, $method=null) {
		header('Location: '.$url);
		die;
	}
}