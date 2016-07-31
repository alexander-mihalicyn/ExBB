<?php

class ModelCheckServer extends BaseModel {
	// Массив файлов для проверки
	private $fileSystemObjectsChecklist = [
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

	public function checkFilesPermissions() {
		$fileSystemObjectsList = [];

		foreach ($this->fileSystemObjectsChecklist as $object) {
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

			$fileSystemObjectsList[] = $objectData;
		}

		return $fileSystemObjectsList;
	}

	public function checkServerConfiguration() {
		$serverConfigurationData = [];

		// Проверка версии PHP
		$phpVersionStatus = version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '>=');

		$serverConfigurationData[] = [
			'code' => 'phpVersion',
			'title' => lang('phpParameterVersion'),
			'status' => $phpVersionStatus,

			'currentValue' => PHP_VERSION,
			'optimalValue' => REQUIRED_PHP_VERSION,
		];

		// Проверка доступности SQLite
		$SQLiteStatus = extension_loaded('sqlite3');

		$serverConfigurationData[] = [
			'code' => 'SQLite3',
			'title' => lang('phpParameterSQLite3'),
			'status' => $SQLiteStatus,

			'currentValue' => ($SQLiteStatus) ? lang('phpParameterSupported') : lang('phpParameterNotSupported'),
			'optimalValue' => lang('phpParameterSupported'),
		];

		// Проверка доступности GZIP
		$gzipStatus = function_exists('ob_gzhandler');

		$serverConfigurationData[] = [
			'code' => 'GZIP',
			'title' => lang('phpParameterGzip'),
			'status' => $gzipStatus,

			'currentValue' => ($gzipStatus) ? lang('phpParameterSupported') : lang('phpParameterNotSupported'),
			'optimalValue' => lang('phpParameterSupported'),
		];

		return $serverConfigurationData;
	}
}