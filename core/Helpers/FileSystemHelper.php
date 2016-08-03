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

		$files = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($files as $fileinfo) {
			($fileinfo->isDir()) ? rmdir($fileinfo->getRealPath()) : unlink($fileinfo->getRealPath());
		}

		rmdir($directory);
	}

	/**
	 * Создаёт директорию на сервере
	 *
	 * @param string $directory путь к директории
	 * @param int $chmod права на создаваемую директорию
	 * @param bool $recursive создавать директорию рекурсивно
	 *
	 * @throws \Exception
	 */
	public static function createDirectory($directory, $chmod=0777, $recursive=false) {
		if (is_dir($directory)) {
			throw new \Exception('Directory "'.$directory.'" is already exists');
		}

		mkdir($directory, $chmod, $recursive);
	}

	/**
	 * Копирует директорию со всем её содержимым в другое место
	 *
	 * @param string $source Исходная директория
	 * @param string $destination Конечная директория
	 * @param int $chmodDirs Права на дериктории
	 * @param int $chmodFiles Права на файлы
	 */
	public static function copyDirectory($source, $destination, $chmodDirs=0777, $chmodFiles=0777) {
		mkdir($destination, $chmodDirs);

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::SELF_FIRST);

		foreach ($iterator as $item) {
			if ($item->isDir()) {
				mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			} else {
				copy($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
			}
		}
	}
}