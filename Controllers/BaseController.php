<?php
namespace Forum\Controllers;

use ExBB\Base\Controller;

/**
 * Класс, являющийся родителем для всех контроллеров админ-панели
 *
 * Class BaseController
 * @package Admin\Controllers
 */
abstract class BaseController extends Controller {
	public function __construct() {
		parent::__construct();

		$this->viewsPath = EXBB_ROOT.'/templates/'.DEF_SKIN;
		$this->modelsPath = EXBB_ROOT.'/Models';
		$this->appNamespacePrefix = 'Forum';
	}

	abstract public function IndexAction();

	/**
	 * Выполняется перед подключением файла с представлением
	 */
	protected function beforeRender() {
		global $fm; // Временное решение

		include $this->viewsPath.'/all_header.tpl';
		include $this->viewsPath.'/logos.tpl';
	}

	/**
	 * Выполняется после подключения файла с представлением
	 */
	protected  function afterRender() {
		global $fm; // Временное решение

		include $this->viewsPath.'/footer.tpl';
		include EXBB_ROOT.'/include/page_tail.php';
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