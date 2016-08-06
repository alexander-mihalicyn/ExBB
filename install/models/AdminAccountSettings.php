<?php

/**
 * Class ModelAccountSettings
 */
class ModelAdminAccountSettings extends BaseModel {
	public function validate($data) {
		$messages = [];

		$login = trim($data['login']);
		$password = trim($data['password']);
		$email = trim($data['email']);

		if (empty($login)) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('adminAccountLoginEmpty'),
			];
		}

		if (!preg_match("/^[A-Za-zа-яА-ЯёЁ0-9\.\s_]+$/si", $login)) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('adminAccountLoginInvalid'),
			];
		}

		if (empty($password)) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('adminAccountPasswordEmpty'),
			];
		}

		if (mb_strlen($password) < 5) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('adminAccountPasswordShort'),
			];
		}

		if ($password != $data['confirmPassword']) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('adminAccountConfirmPasswordInvalid'),
			];
		}

		if (empty($email)) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('adminAccountEmailEmpty'),
			];
		}

		if (!$this->validateEmail($email)) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('adminAccountEmailInvalid'),
			];
		}

		return $messages;
	}

	public function createAccount($data) {
		$users = [];

		$users[1] = [
			'n' => mb_strtolower(trim($data['login']), 'UTF-8'),
			'm' => trim($data['email']),
			'p' => 0,
		];

		$fpUsersList = null;
		$this->fm->_Read2Write($fpUsersList, EXBB_DATA_USERS_LIST);
		$this->fm->_Write($fpUsersList, $users);


		$user = [];
		$user['id'] = 1;
		$user['name'] = trim($data['login']);
		$user['pass'] = md5($data['password']);
		$user['mail'] = trim($data['email']);
		$user['status'] = 'ad';
		$user['title'] = '';
		$user['posts'] = 0;
		$user['showemail'] = false;
		$user['www'] = '';
		$user['aim'] = '';
		$user['icq'] = '';
		$user['location'] = '';
		$user['joined'] = $this->fm->_Nowtime;
		$user['sig'] = '';
		$user['sig_on'] = true;
		$user['timedif'] = 0;
		$user['upload'] = true;
		$user['avatar'] = 'noavatar.gif';
		$user['last_visit'] = 0;
		$user['posted'] = array();
		$user['lastpost'] = array( 'date' => 0, 'link' => '', 'name' => '' );
		$user['lang'] = $this->fm->exbb['default_lang'];
		$user['skin'] = $this->fm->exbb['default_style'];
		$user['interests'] = '';
		$user['private'] = array();
		$user['new_pm'] = false;
		$user['sendnewpm'] = false;
		$user['visible'] = false;
		$user['posts2page'] = $this->fm->exbb['posts_per_page'];
		$user['topics2page'] = $this->fm->exbb['topics_per_page'];

		$fpUser = null;
		$this->fm->_Read2Write($fpUser, EXBB_DATA_DIR_MEMBERS . '/1.php');
		$this->fm->_Write($fpUser, $user);
	}

	public function updateBoardStats($data) {
		$this->fm->_SAVE_STATS([
			'max_online' => [
				1,
				0
			],
			'max_time' => [
				$this->fm->_Nowtime,
				0
			],
			'lastreg' => [
				trim($data['login']),
				0
			],
			'last_id' => [
				1,
				0
			],
			'totalmembers' => [
				1,
				0
			],
			'totalposts' => [
				0,
				0
			],
			'totalthreads' => [
				0,
				0
			],
		]);
	}
}