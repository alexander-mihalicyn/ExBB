<?php
namespace ExBB\Helpers;

/**
 * Класс для работы с языком
 *
 * Class LanguageHelper
 * @package ExBB\Helpers
 */
class LanguageHelper {
	/**
	 * @var array Массив языковых строк
	 */
	private static $strings = [];

	/**
	 * Возвращает форматированную языковую строку
	 *
	 * @param string $file Языковой файл
	 * @param string $string Индекс языковой строки
	 * @param array $args Аргументы для vsprintf
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function t($file, $string, $args=[]) {
		if (!isset(static::$strings[$file])) {
			$filePath = EXBB_ROOT.'/language/'.DEF_LANG.'/lang_'.$file.'.php';

			if (!file_exists($filePath)) {
				throw new \Exception('Language file "'.$filePath.'" not found');
			}

			static::$strings[$file] = include($filePath);
		}

		return (isset(static::$strings[$file][$string])) ? vsprintf(static::$strings[$file][$string], $args) : $string;
	}
}