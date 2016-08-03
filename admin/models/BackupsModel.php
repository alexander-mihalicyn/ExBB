<?php
namespace Admin\Models;

use ExBB\Base\Model;
use ExBB\Helpers\FileSystemHelper;
use ExBB\Helpers\LanguageHelper;
use ExBB\Helpers\UrlHelper;

/**
 * Class BackupsModel
 * @package Admin\Models
 */
class BackupsModel extends Model {
	/**
	 * Вовзращает отсортированный по убыванию по дате создания
	 * массив информации обо всех созданных резервных копиях
	 *
	 * @return array
	 */
	public function getBackupsList() {
		$files = glob(EXBB_DATA_DIR_BACKUPS.'/*.zip');

		usort($files, function($a, $b) {
			return filemtime($a) < filemtime($b);
		});

		return array_map(function($file) {
			return [
				'date' => date('d-m-Y H:i:s', filemtime($file)),
				'filename' => str_replace(EXBB_DATA_DIR_BACKUPS.'/', '', $file),
				'path' => $file,
				'size' => filesize($file),
			];
		}, $files);
	}

	/**
	 * Создаёт ZIP архив со всеми данными форума и возвращает путь к нему
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function createBackup() {
		if (!is_dir(EXBB_DATA_DIR_BACKUPS)) {
			FileSystemHelper::createDirectory(EXBB_DATA_DIR_BACKUPS);
		}

		if (!class_exists('\ZipArchive', false)) {
			throw new \Exception(LanguageHelper::t('admin_backups', 'zipExtensionNotLoaded'));
		}

		$filename = EXBB_DATA_DIR_BACKUPS.'/'.date('d-m-Y_H-i-s').'.zip';

		$zip = new \ZipArchive();
		$zip->open($filename, \ZipArchive::CREATE);

		$iterator = $this->getDirectoryRecursiveIterator(EXBB_DATA);

		foreach ($iterator as $item) {
			if ($item->isDir()) {
				$zip->addEmptyDir(str_replace(realpath(EXBB_ROOT).DIRECTORY_SEPARATOR, '', $item->getRealPath()));
			}
			else {
				$zip->addFile($item->getRealPath(), str_replace(realpath(EXBB_ROOT).DIRECTORY_SEPARATOR, '', $item->getRealPath()));
			}
		}

		$zip->close();

		return $filename;
	}

	/**
	 * Проверяет резервную копию на существование
	 *
	 * @param string $filename Имя файла резервной копии
	 *
	 * @return bool
	 */
	public function backupExists($filename) {
		$filename = basename($filename);

		$parts = explode('.', $filename);

		if (empty($parts[1]) || $parts[1] != 'zip') {
			return false;
		}

		return file_exists(EXBB_DATA_DIR_BACKUPS.'/'.$filename);
	}

	/**
	 * Возвращает URL файла резервной копии
	 *
	 * @param string $filename Имя файла резервной копии
	 *
	 * @return string
	 */
	public function getDownloadBackupUrl($filename) {
		$filePath = str_replace('\\', '/', realpath(EXBB_DATA_DIR_BACKUPS.'/'.$filename));

		return UrlHelper::getRootUrl().str_replace(str_replace('\\', '/', EXBB_ROOT), '', $filePath);
	}

	/**
	 * Удаляет файл резеврной копии
	 *
	 * @param string $filename Имя файла резервной копии
	 *
	 * @return bool
	 */
	public function deleteBackup($filename) {
		$filename = basename($filename);

		$parts = explode('.', $filename);

		if (empty($parts[1]) || $parts[1] != 'zip') {
			return false;
		}

		$path = EXBB_DATA_DIR_BACKUPS.'/'.$filename;

		if (!file_exists($path)) {
			return false;
		}

		unlink($path);
		return true;
	}

	/**
	 * Возвращает итератор для обхода всего содержимого директории
	 *
	 * @param string $dir Путь к директории
	 *
	 * @return \RecursiveIteratorIterator
	 */
	private function getDirectoryRecursiveIterator($dir) {
		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::SELF_FIRST);

		return $iterator;
	}
}