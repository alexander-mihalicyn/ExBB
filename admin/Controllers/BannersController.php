<?php
namespace Admin\Controllers;

use ExBB\Helpers\LanguageHelper;
use ExBB\Helpers\UrlHelper;

/**
 * Class BannersController
 * @package Admin\Controllers
 */
class BannersController extends BaseController {
	/**
	 * Отображает форму для редактирования кода баннеров
	 */
	public function IndexAction() {
		/**
		 * @var $bannersModel \Admin\Models\BannersModel
		 */
		$bannersModel = $this->loadModel('banners');

		return $this->render('banners/form', [
			'code' => $bannersModel->getBannersCode(),
		]);
	}

	/**
	 * Выполняет сохранение кода баннеров
	 */
	public function SaveAction() {
		if (!isset($this->request->post['code'])) {
			$this->redirectPage(
				LanguageHelper::t('admin_banners', 'invalidRequestError'),
				LanguageHelper::t('admin_banners', 'invalidRequestErrorText'),
				UrlHelper::to(['banners', 'index', 'admincenter'])
			);
		}

		/**
		 * @var $bannersModel \Admin\Models\BannersModel
		 */
		$bannersModel = $this->loadModel('banners');

		$bannersModel->saveBannersCode($this->request->post['code']);

		$this->redirectPage(
			LanguageHelper::t('admin_banners', 'banners'),
			LanguageHelper::t('admin_banners', 'bannersCodeSaved'),
			UrlHelper::to(['banners', 'index', 'admincenter'])
		);
	}
}