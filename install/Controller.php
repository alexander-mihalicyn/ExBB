<?php
/**
 * Class Controller
 */
class Controller extends BaseController {
	public function __construct() {
		parent::__construct();

		$this->page['stepsList'] = [
			[
				'title' => lang('installtionStepWelcome'),
				'active' => false,
			],

			[
				'title' => lang('installtionStepCheckingRequirements'),
				'active' => false,
			],

			[
				'title' => lang('installtionStepForumSettings'),
				'active' => false,
			],

			[
				'title' => lang('installtionStepAdminAccountSettings'),
				'active' => false,
			],

			[
				'title' => lang('installtionStepFinal'),
				'active' => false,
			],
		];
	}

	public function ActionIndex() {
		$this->setActiveStep(0);

		return $this->render('welcome');
	}

	public function ActionCheck() {
		$this->setActiveStep(1);

		$checkingErrors = false;
		$checkingWarnings = false;

		// Массив файлов для проверки
		$fileSystemObjectsChecklist = [
			// Основные директории
			EXBB_DATA,
			EXBB_DATA_DIR_FORUMS,
			EXBB_DATA_DIR_LOGS,
			EXBB_DATA_DIR_MEMBERS,
			EXBB_DATA_DIR_MESSAGES,
			EXBB_DATA_DIR_SEARCH,
			EXBB_DATA_DIR_BANNED_MEMBERS,
			EXBB_DATA_DIR_MODULES,
			EXBB_DIR_UPLOADS,

			// Основные файлы
			EXBB_DATA_CONFIG,
			EXBB_DATA_CONFIG_BACKUP,
			EXBB_DATA_FORUMS_LIST,
			EXBB_DATA_FORUMS_LIST_BACKUP,
			EXBB_DATA_BADWORDS,
			EXBB_DATA_BANNED_USERS_LIST,
			EXBB_DATA_BANNED_BY_IP_LIST,
			EXBB_DATA_BANNERS,
			EXBB_DATA_COUNTERS,
			EXBB_DATA_BOARD_STATS,
			EXBB_DATA_MEMBERS_TITLES,
			EXBB_DATA_NEWS,
			EXBB_DATA_MEMBERS_ONLINE,
			EXBB_DATA_SKIP_MAILS,
			EXBB_DATA_SMILES_LIST,
			EXBB_DATA_USERS_LIST,
			EXBB_DATA_TEMP_USERS_LIST,
		];

		$fileSystemObjectsList = [];

		foreach ($fileSystemObjectsChecklist as $object) {
			$objectData = [
				'path' => str_replace(EXBB_ROOT . '/', '', $object),
			];

			$objectData['isExists'] = file_exists($object);

			if ($objectData['isExists']) {
				$objectData['isReadable'] = is_readable($object);
				$objectData['isWriteable'] = is_writeable($object);
			}
			else {
				$objectData['isReadable'] = false;
				$objectData['isWriteable'] = false;
			}

			if (!$objectData['isExists'] || !$objectData['isReadable'] || !$objectData['isWriteable']) {
				$checkingErrors = true;
			}

			$fileSystemObjectsList[] = $objectData;
		}

		$phpVersionStatus = version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '>=');

		$phpExtensionsCheckList = [
			[
				'title' => lang('phpParameterSQLite3'),
				'status' => extension_loaded('sqlite3')
			],

			[
				'title' => lang('phpParameterGzip'),
				'status' => function_exists('ob_gzhandler')
			],
		];

		if (!$phpVersionStatus) {
			$checkingErrors = true;
		}

		foreach ($phpExtensionsCheckList as $object) {
			if (!$object['status']) {
				$checkingWarnings = true;
			}
		}

		$this->session->data['checkingResult'] = !$checkingErrors;

		return $this->render('check', [
			'fileSystemObjectsCheckList' => $fileSystemObjectsList,
			'currentPhpVersion' => PHP_VERSION,
			'requiredPhpVersion' => REQUIRED_PHP_VERSION,
			'phpVersionStatus' => $phpVersionStatus,
			'phpExtensionsCheckList' => $phpExtensionsCheckList,

			'checkingErrors' => $checkingErrors,
			'checkingWarnings' => $checkingWarnings,
		]);
	}

	public function ActionForumSettings() {
		if (!isset($this->session->data['checkingResult']) || !$this->session->data['checkingResult']) {
			$this->redirect('index.php?action=check');
		}

		$this->setActiveStep(2);

		$this->session->data['settingsInstalled'] = false;

		$settingsFormData = [
			'forum' => [
				'chmodDirs' => 0777,
				'chmodFiles' => 0777,
				'chmodUploads' => 0644,

				'title' => '',
				'description' => '',
				'email' => '',
				'demodata' => false,
			],
		];

		$messages = [];

		if (isset($this->request->post['settings'])) {
			$settingsFormData = array_merge($settingsFormData, $this->request->post['settings']);

			$data = $settingsFormData['forum'];

			$modelSettings = $this->loadModel('ForumSettings');

			$messages = $modelSettings->validate($data);

			if (!empty($messages)) {
				return $this->render('forumsettings', [
					'settings' => $settingsFormData,
					'messages' => $messages,
				]);
			}

			// Сохраняем настройки
			$modelSettings->saveConfig($data);

			$this->session->data['settingsInstalled'] = true;

			$this->redirect('index.php?action=adminAccountSettings');
		}

		return $this->render('forumsettings', [
			'settings' => $settingsFormData,
			'messages' => $messages,
		]);
	}

	public function ActionAdminAccountSettings() {
		if (!isset($this->session->data['settingsInstalled']) || !$this->session->data['settingsInstalled']) {
			$this->redirect('index.php?action=forumSettings');
		}

		$this->setActiveStep(3);

		$this->session->data['adminAccountInstalled'] = false;

		$settingsFormData = [
			'account' => [
				'login' => '',
				'password' => '',
				'confirmPassword' => '',
				'email' => '',
			],
		];

		$messages = [];

		if (isset($this->request->post['settings'])) {
			$settingsFormData = array_merge($settingsFormData, $this->request->post['settings']);
			$data = $settingsFormData['account'];

			$modelAdminAccountSettings = $this->loadModel('AdminAccountSettings');

			$messages = $modelAdminAccountSettings->validate($data);

			if (!empty($messages)) {
				return $this->render('adminaccountsettings', [
					'settings' => $settingsFormData,
					'messages' => $messages,
				]);
			}

			$modelAdminAccountSettings->createAccount($data);
			$modelAdminAccountSettings->updateBoardStats($data);

			$this->session->data['adminAccountInstalled'] = true;

			$this->redirect('index.php?action=finish');
		}

		return $this->render('adminaccountsettings', [
			'settings' => $settingsFormData,
			'messages' => $messages,
		]);
	}

	public function ActionFinish() {
		if (!isset($this->session->data['adminAccountInstalled']) || !$this->session->data['adminAccountInstalled']) {
			$this->redirect('index.php?action=adminAccountSettings');
		}

		$this->setActiveStep(4);

		return $this->render('finish');
	}

	private function setActiveStep($index) {
		$this->page['stepsList'][$index]['active'] = true;
	}
}