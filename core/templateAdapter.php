<?php
use ExBB\Helpers\LanguageHelper;
use ExBB\Helpers\UrlHelper;

/**
 * Генерирует URL страницы
 *
 * @param array $route массив вида [контроллер, действие, точка_входа(не обязательно)]
 * @param array|null $parameters массив GET параметров
 * @param string|null $anchor якорь
 *
 * @return string
 */
function exUrl($route, $parameters=null, $anchor=null) {
	return UrlHelper::to($route, $parameters, $anchor);
}

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
function exLang($file, $string, $args=[]) {
	return LanguageHelper::t($file, $string, $args);
}