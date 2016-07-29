<?php
class ModelForumSettings extends BaseModel {
	public function validate($data) {
		$messages = [];

		/**if (empty($data['url'])) {
		$messages[] = [
		'type' => 'error',
		'text' => lang('forumSettingsUrlEmpty'),
		];
		}*/

		if (empty(trim($data['title']))) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('forumSettingsTitleEmpty'),
			];
		}

		/**if (empty($data['description'])) {
		$messages[] = [
		'type' => 'error',
		'text' => lang('forumSettingsDescriptionEmpty'),
		];
		}*/

		if (empty(trim($data['email']))) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('forumSettingsEmailEmpty'),
			];
		}

		if (!is_numeric(trim($data['chmodDirs'])) || !is_numeric(trim($data['chmodFiles'])) || !is_numeric(trim($data['chmodUploads']))) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('forumSettingsChmodInvalid'),
			];
		}

		if (!$this->validateEmail(trim($data['email']))) {
			$messages[] = [
				'type' => 'error',
				'text' => lang('forumSettingsEmailInvalid'),
			];
		}

		return $messages;
	}

	public function saveConfig($data) {
		$url = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
		$url .= $_SERVER['SERVER_NAME'];
		$url .= $_SERVER['REQUEST_URI'];

		$forumUrl = dirname(dirname($url));

		if (empty($data['chmodDirs'])) {
			$data['chmodDirs'] = '0777';
		}

		if (empty($data['chmodFiles'])) {
			$data['chmodFiles'] = '0777';
		}

		if (empty($data['chmodUploads'])) {
			$data['chmodUploads'] = '0644';
		}

		$this->_saveForumConfig([
			'boardurl' => $forumUrl,
			'boardname' => $this->sanitizeString($data['title']),
			'boarddesc' => $this->sanitizeString($data['description']),
			'adminemail' => $this->sanitizeString($data['email']),

			'ch_dirs' => (int)trim($data['chmodDirs']),
			'ch_files' => (int)trim($data['chmodFiles']),
			'ch_upfiles' => (int)trim($data['chmodUploads']),
			
			'installed' => true,
		]);
	}

	private function _saveForumConfig($data) {
		include EXBB_DATA_CONFIG;

		$configFileContent = '<?php'.PHP_EOL.'defined(\'IN_EXBB\') or die(\'Hack attempt!\');'.PHP_EOL.PHP_EOL;

		foreach ($this->exbb as $var => $value) {
			if (isset($data[$var])) {
				$value = $data[$var];
			}

			$configFileContent .= '$this->exbb[\''.$var.'\'] = ';

			if (is_string($value)) {
				$configFileContent .= '"'.$value.'"';
			}
			else if ($var == 'ch_dirs' || $var == 'ch_files' || $var == 'ch_upfiles') {
				$configFileContent .= '0'.(int)$value;
			}
			else if (is_numeric($value)) {
				$configFileContent .= $value;
			}
			else if (is_bool($value)) {
				$configFileContent .= ($value) ? 'true' : 'false';
			}
			else {
				$configFileContent .= $value;
			}

			$configFileContent .= ';'.PHP_EOL;
		}

		file_put_contents(EXBB_DATA_CONFIG, $configFileContent, LOCK_EX);
	}
}