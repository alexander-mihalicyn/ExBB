<?php
namespace ExBB\Helpers;

/**
 * Класс для работы с URL
 *
 * Class UrlHelper
 * @package ExBB\Helpers
 */
class UrlHelper {
	/**
	 * Генерирует URL страницы
	 *
	 * @param array $route массив вида [контроллер, действие, точка_входа(не обязательно)]
	 * @param array|null $parameters массив GET параметров
	 * @param string|null $anchor якорь
	 *
	 * @return string
	 */
	public static function to($route, $parameters=null, $anchor=null) {
		global $fm; // Временное решение

		$entryPoint = (!empty($route[3])) ? $route[3] : 'index';

		$url = $fm->exbb['boardurl'] . '/' . $entryPoint . '.php';

		if (!empty($route[0]) || $parameters !== null) {
			$url .= '?';
		}

		if (!empty($route[0])) {
			$url .= 'r='.$route[0];

			if (!empty($route[1])) {
				$url .= '/'.$route[1];
			}
		}

		$addAmpersand = (!empty($route[0]));

		if ($parameters !== null) {
			foreach ($parameters as $name => $value) {
				$url .= (($addAmpersand) ? '&amp;' : '') . $name . '=' . $value;

				$addAmpersand = true;
			}
		}

		if ($anchor !== null) {
			$url .= '#'.$anchor;
		}

		return $url;
	}
}