<?php
namespace ExBB\Base;

use ExBB\Session;
use ExBB\Request;

use ExBB\DataBase\FileDB;

/**
 * Класс, являющийся родителем для любого контроллера в системе
 *
 * Class Controller
 * @package ExBB\Base
 */
class Controller {
	/**
	 * @var \ExBB\Request Объект класса для доступа к переменным запроса
	 */
	protected $request;
	/**
	 * @var \ExBB\Session Объект класса для работы с сессиями
	 */
	protected $session;
	/**
	 * @var \ExBB\DataBase\FileDB Объект класс для работы с файловой базой данных
	 */
	protected $db;

	/**
	 * @var string Путь к директории с файлами представления
	 */
	protected $viewsPath;
	/**
	 * @var string Путь к директории с файлами моделей
	 */
	protected $modelsPath;

	/**
	 * @var string Префикс пространства имён для текущего приложения (форум, админ-панель)
	 */
	protected $appNamespacePrefix;

	/**
	 *
	 */
	public function __construct() {
		$this->request = new Request();
		$this->session = new Session();
		$this->db = new FileDB();
	}

	protected function loadModel($model) {
		$modelPath = $this->modelsPath.'/'.ucfirst($model).'Model.php';

		if (!file_exists($modelPath)) {
			throw new \Exception('Model file "'.$modelPath.'" not found');
		}

		include $modelPath;

		$className = $this->appNamespacePrefix.'\\Models\\'.ucfirst($model).'Model';

		if (!class_exists($className, false)) {
			throw new \Exception('Model class "'.$className.'" not found');
		}

		return new $className();
	}

	/**
	 * Отображает шаблон
	 *
	 * @param string $view Путь к файлу шаблона
	 * @param array $data Массив данных для шаблона
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	protected function render($view, $data=[]) {
		global $fm; // Временное решение

		$viewPath = $this->viewsPath.'/'.$view.'.tpl';

		if (!file_exists($viewPath)) {
			throw new \Exception('View "'.$viewPath.'" not found');
		}

		extract($data);

		$this->beforeRender();

		include $viewPath;

		$this->afterRender();

		// TODO: возвращение HTML кода шаблона
		return '';
	}

	/**
	 * Выполняется перед подключением файла с представлением
	 */
	protected function beforeRender() {

	}

	/**
	 * Выполняется после подключения файла с представлением
	 */
	protected function afterRender() {

	}

	/**
	 * Выполняет HTTP-переадресацию
	 *
	 * @param string $url URL для переадресации
	 * @param int $statusCode Код статуса
	 */
	protected function redirect($url, $statusCode = 302) {
		header('Location: '.$url, true, $statusCode);
		die;
	}

	/**
	 * Отображает страницу переадресации
	 *
	 * @param string $title Заголовок
	 * @param string $text Текст сообщения о переадресации
	 * @param string $url URL для переадресации
	 * @param int $delay Время задержки
	 */
	protected function redirectPage($title, $text, $url, $delay=3) {
		global $fm;

		$fm->_Link = "<meta http-equiv='refresh' content='" . $delay . "; url=" . $url . "'>";
		$fm->_Title = ' :: ' . $title;

		include $this->viewsPath.'/all_header.tpl';
		include $this->viewsPath.'/redirect.tpl';
		include $this->viewsPath.'/footer.tpl';

		include EXBB_ROOT.'/include/page_tail.php';
		die;
	}
}