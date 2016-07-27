<?php
namespace ExBB\Helpers;

/**
 * Class FileSystemHelper
 * @package core\Helpers
 */
class FileSystemHelper {
	/**
	 * Удаляет директорию
	 *
	 * @param string $directory название директории
	 *
	 * @throws \Exception
	 */
	public static function deleteDirectory($directory) {
		if (!is_dir($directory)) {
			throw new \Exception('Directory "'.$directory.'" is not found');
		}

		rmdir($directory);
	}

	/**
	 * Рекурсивно удаляет директорию (вместе со всем её содержимым)
	 *
	 * @param string $directory название директории
	 *
	 * @throws \Exception
	 */
	public static function deleteDirectoryRecursive($directory) {
		if (!is_dir($directory)) {
			throw new \Exception('Directory "'.$directory.'" is not found');
		}

		$objects = glob($directory.'/*');

		if (!empty($objects)) {
			foreach ($objects as $object) {
				if (is_dir($object)) {
					static::deleteDirectoryRecursive($object);
				}
				else {
					unlink($object);
				}
			}
		}

		rmdir($directory);
	}
}