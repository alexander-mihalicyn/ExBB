<?php
namespace Admin\Controllers;

use ExBB\Helpers\LanguageHelper;
use ExBB\Helpers\UrlHelper;

define('EXBB_DATA_DIR_BACKUPS', EXBB_ROOT.'/backups');

/**
 * Class BackupsController
 * @package Admin\Controllers
 */
class BackupsController extends BaseController {
	/**
	 * Отображает форму для редактирования кода баннеров
	 */
	public function IndexAction() {
		/**
		 * @var $backupsModel \Admin\Models\BackupsModel
		 */
		$backupsModel = $this->loadModel('backups');

		return $this->render('backups/list', [
			'backupsList' => $backupsModel->getBackupsList(),
		]);
	}

	/**
	 * Создаёт резервную копию данных форума
	 */
	public function CreateAction() {
		/**
		 * @var $backupsModel \Admin\Models\BackupsModel
		 */
		$backupsModel = $this->loadModel('backups');

		try {
			$backupsModel->createBackup();
		}
		catch (\Exception $exception) {
			$this->redirectPage(
				LanguageHelper::t('admin_backups', 'backups'),
				$exception->getMessage(),
				UrlHelper::to(['backups', 'index', 'admincenter'])
			);
		}

		$this->redirectPage(
			LanguageHelper::t('admin_backups', 'backups'),
			LanguageHelper::t('admin_backups', 'backupsCreated'),
			UrlHelper::to(['backups', 'index', 'admincenter'])
		);
	}

	/**
	 * Перенаправляет пользователя на скачивание резервной копии
	 *
	 * @throws \Exception
	 */
	public function DownloadAction() {
		/**
		 * @var $backupsModel \Admin\Models\BackupsModel
		 */
		$backupsModel = $this->loadModel('backups');

		if (empty($this->request->query['backup']) || !$backupsModel->backupExists($this->request->query['backup'])) {
			$this->redirectPage(
				LanguageHelper::t('admin_backups', 'backups'),
				LanguageHelper::t('admin_backups', 'backupNotFound'),
				UrlHelper::to(['backups', 'index', 'admincenter'])
			);
		}

		$this->redirect($backupsModel->getDownloadBackupUrl($this->request->query['backup']));
	}

	public function DeleteAction() {
		/**
		 * @var $backupsModel \Admin\Models\BackupsModel
		 */
		$backupsModel = $this->loadModel('backups');

		if (empty($this->request->query['backup']) || !$backupsModel->backupExists($this->request->query['backup'])) {
			$this->redirectPage(
				LanguageHelper::t('admin_backups', 'backups'),
				LanguageHelper::t('admin_backups', 'backupNotFound'),
				UrlHelper::to(['backups', 'index', 'admincenter'])
			);
		}

		$backupsModel->deleteBackup($this->request->query['backup']);

		$this->redirectPage(
			LanguageHelper::t('admin_backups', 'backups'),
			LanguageHelper::t('admin_backups', 'backupDeleted'),
			UrlHelper::to(['backups', 'index', 'admincenter'])
		);
	}
}