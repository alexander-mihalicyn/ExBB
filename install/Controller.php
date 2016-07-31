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

		$checkModel = $this->loadModel('CheckServer');
		$fileSystemObjectsList = $checkModel->checkFilesPermissions();

		foreach ($fileSystemObjectsList as $objectData) {
			if (!$objectData['isExists'] || !$objectData['isReadable'] || !$objectData['isWriteable']) {
				$checkingErrors = true;
			}
		}

		$serverConfigurationCheckList = $checkModel->checkServerConfiguration();

		foreach ($serverConfigurationCheckList as $object) {
			if (!$object['status']) {
				if ($object['code'] == 'phpVersion') {
					$checkingErrors = true;
				}
				else {
					$checkingWarnings = true;
				}
			}
		}

		$this->session->data['checkingResult'] = !$checkingErrors;

		return $this->render('check', [
			'fileSystemObjectsCheckList' => $fileSystemObjectsList,
			'serverConfigurationCheckList' => $serverConfigurationCheckList,

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
				'chmodDirs' => '0777',
				'chmodFiles' => '0777',
				'chmodUploads' => '0644',

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
			$modelSettings->resetData();
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
		global $fm;

		if (!isset($this->session->data['adminAccountInstalled']) || !$this->session->data['adminAccountInstalled']) {
			$this->redirect('index.php?action=adminAccountSettings');
		}

		$this->setActiveStep(4);

		return $this->render('finish', [
			'indexUrl' => $fm->exbb['boardurl'],
		]);
	}

	private function setActiveStep($index) {
		$this->page['stepsList'][$index]['active'] = true;
	}
}