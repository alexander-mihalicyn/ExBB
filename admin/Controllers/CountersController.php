<?php
namespace Admin\Controllers;

use ExBB\Helpers\LanguageHelper;
use ExBB\Helpers\UrlHelper;

/**
 * Class CountersController
 * @package Admin\Controllers
 */
class CountersController extends BaseController {
	/**
	 * Отображает форму для редактирования кода счётчиков
	 */
	public function IndexAction() {
		/**
		 * @var $countersModel \Admin\Models\CountersModel
		 */
		$countersModel = $this->loadModel('counters');

		return $this->render('counters/form', [
			'code' => $countersModel->getCountersCode(),
		]);
	}

	/**
	 * Выполняет сохранение кода баннеров
	 */
	public function SaveAction() {
		if (!isset($this->request->post['code'])) {
			$this->redirectPage(
				LanguageHelper::t('admin_counters', 'invalidRequestError'),
				LanguageHelper::t('admin_counters', 'invalidRequestErrorText'),
				UrlHelper::to(['counters', 'index', 'admincenter'])
			);
		}

		/**
		 * @var $countersModel \Admin\Models\CountersModel
		 */
		$countersModel = $this->loadModel('counters');

		$countersModel->saveCountersCode($this->request->post['code']);

		$this->redirectPage(
			LanguageHelper::t('admin_counters', 'counters'),
			LanguageHelper::t('admin_counters', 'countersCodeSaved'),
			UrlHelper::to(['counters', 'index', 'admincenter'])
		);
	}
}