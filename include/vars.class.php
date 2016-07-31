<?php
if (!defined('IN_EXBB')) {
	die( 'Hack attempt!' );
}

class VARS {
	/* Массив в который будут помещены все входящие данные */
	public $input = array();
	public $_POST = false;
	public $_GET = false;
	public $_IP = '127.0.0.1';

	/****************************************************
	 *                                                    *
	 *        Функции входящих данных                        *
	 *                                                    *
	 ****************************************************/
	/*
		Конструктор
		Проверяет массивы $_GET и $_POST,
		переменные $_SERVER['REQUEST_METHOD'],
		вычисляет IP пользователя и заносит эти данные
		в массив входящих данных $input
	*/
	public function __construct() {
		$this->_IP = $this->Return_IP();
		$this->_POST = ( $_SERVER['REQUEST_METHOD'] == 'POST' ) ? true : false;
		$this->_GET = ( $_SERVER['REQUEST_METHOD'] == 'POST' ) ? false : true;
	}

	public function _GetVars() {
		if (is_array($_GET)) {
			$this->Read_Vars($this->input, $_GET);
		}

		if (is_array($_POST)) {
			$this->Read_Vars($this->input, $_POST);
		}
	}


	/*
		Рекурсивно читает входядие массивы переданные
		в качестве аргумента $array
		По ссылке изменяет массив входящих данных $input
		Применяется только в функции конструкторе GBOOK()
	*/
	public function Read_Vars(&$return, $array) {
		if (is_array($array)) {
			foreach ($array as $k => $v) {
				if (is_array($array[$k])) {
					$this->Read_Vars($return[$this->Clean_Key($k)], $array[$k]);
				}
				else {
					$return[$this->Clean_Key($k)] = $this->Clean_Value($v);
					//$return[$this->Clean_Key($k)] = $this->htmlspecialchars($v);
				}
			}
		}
	}

	/*
		Ощишает от плохих символов и возвращает очищенную,
		передданую в аргументе строку.
		Применяется только в функции Read_Vars()
		для очистки ключей
	*/
	public function Clean_Key($key) {
		$key = trim($key);

		if (empty( $key )) {
			return $key;
		}

		$key = preg_replace("/\.\./", '', $key);
		$key = preg_replace("/\_\_(.+?)\_\_/", '', $key);
		$key = preg_replace("/^([\w\.\-\_]+)$/", "$1", $key);

		return $key;
	}

	function Clean_Value($var) {
		$var = preg_replace("/\r/", "", trim($var));

		return preg_replace("/&amp;(\#[0-9]+;)/", "&$1", htmlspecialchars($var, ENT_QUOTES, 'UTF-8'));
	}

	function _String($key, $var = '') {
		$this->input[$key] = ( isset( $this->input[$key] ) && $this->input[$key] != '' ) ? $this->input[$key] : $var;

		return $this->input[$key];
	}

	function _Strings($array) {
		foreach ($array as $key => $var) {
			$this->input[$key] = ( isset( $this->input[$key] ) && $this->input[$key] != '' ) ? $this->input[$key] : $var;
		}

		return true;
	}


	function _Intval($key, $var = 0) {
		$this->input[$key] = ( isset( $this->input[$key] ) && intval($this->input[$key]) != 0 ) ? intval($this->input[$key]) : $var;

		return $this->input[$key];
	}

	function _Intvals($array) {
		foreach ($array as $el) {
			if (is_array($el)) {
				$key = key($el);
				$this->input[$key] = ( isset( $this->input[$key] ) ) ? intval($this->input[$key]) : $el[$key];
			}
			else {
				$this->input[$el] = ( isset( $this->input[$el] ) ) ? intval($this->input[$el]) : 0;
			}
		}
	}

	function _Array($key, $var = array()) {
		$this->input[$key] = ( isset( $this->input[$key] ) && is_array($this->input[$key]) ) ? $this->input[$key] : $var;

		return $this->input[$key];
	}

	function _Boolean1($key) {
		$this->input[$key] = ( isset( $this->input[$key] ) && $this->input[$key] == 'yes' ) ? true : false;

		return $this->input[$key];
	}

	function _Boolean(&$array, $key) {
		$array[$key] = ( isset( $array[$key] ) && $array[$key] == 'yes' ) ? true : false;

		return $array[$key];
	}

	/*
		_setcookie отправка cookie
	*/
	function _setcookie($name, $value = "", $exp = 1) {
		$expires = 0;
		if ($exp == 1) {
			$expires = time() + 31536000;  #+ year (60*60*24*365 = 31536000)
		}
		elseif ($exp > 1) {
			$expires = time() + $exp;  #+ year (60*60*24*365 = 31536000)
		}
		else {
			$expires = time() - 1000;
		}
		setcookie($name, $value, $expires, '/', '');
	}

	/*
		_GetCookie получение строки cookie
	*/
	function _GetCookie($name, $return) {

		$cookie = ( isset( $_COOKIE[$name] ) && trim($_COOKIE[$name]) != '' && intval($_COOKIE[$name]) != 0 ) ? $_COOKIE[$name] : $return;

		return $cookie;
	}

	/*
		_GetCookieArray получение массива cookie
	*/
	function _GetCookieArray($name) {
		$cookie = ( isset( $_COOKIE[$name] ) && trim($_COOKIE[$name]) != '' ) ? $_COOKIE[$name] : "a:0:{}";

		return ( ( $cookie = @unserialize($cookie) ) !== false ) ? $cookie : array();
	}

	/*
		Проверка валидность e-mail
	*/
	function _Chek_Mail($key) {

		if (strlen($this->input[$key]) > 100 || preg_match("#[^A-Za-z0-9_\-\.@]#is", $this->input[$key])) {
			return false;
		}
		elseif (preg_match("#^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+)*$#", $this->input[$key])) {
			return strtolower($this->input[$key]);
		}
		else {
			return false;
		}
	}

	/*
		Проверка валидность домашней страницы
	*/
	function _Chek_WWW($key) {
		$this->input[$key] = str_replace("http://", "", $this->input[$key]);

		if (strlen($this->input[$key]) <= 255 && preg_match("#^(www\.|)([A-Za-z0-9-_]{1,40}\.){1,3}[A-Za-z]{2,4}(/[\.~A-Za-z0-9_-]{1,20}|)$#is", $this->input[$key])) {
			$this->input[$key] = 'http://' . $this->input[$key];

			return $this->input[$key];
		}
		else {
			$this->input[$key] = '';

			return $this->input[$key];
		}
	}

	/*
		_LowerCase преобразование строки в нижний регистр
	*/
	function _LowerCase($var) {
		return ( $this->_RuLocale === false ) ? $this->_strtolower($var) : strtolower($var);
	}

	/*
		_UpperCase преобразование строки в верхний регистр
	*/
	function _UpperCase($var) {
		return ( $this->_RuLocale === false ) ? $this->_strtoupper($var) : strtoupper($var);
	}

	/*
		_strtoupper собственная функция преобразование строки в верхний регистр
	*/
	function _strtoupper($var) {
		$replacement = array( "'а'" => 'А', "'б'" => 'Б', "'в'" => 'В', "'г'" => 'Г', "'д'" => 'Д', "'е'" => 'Е', "'ё'" => 'Ё', "'ж'" => 'Ж', "'з'" => 'З', "'и'" => 'И', "'й'" => 'Й', "'к'" => 'К', "'л'" => 'Л', "'м'" => 'М', "'н'" => 'Н', "'о'" => 'О', "'п'" => 'П', "'р'" => 'Р', "'с'" => 'С', "'т'" => 'Т', "'у'" => 'У', "'ф'" => 'Ф', "'х'" => 'Х', "'ц'" => 'Ц', "'ч'" => 'Ч', "'ш'" => 'Ш', "'щ'" => 'Щ', "'ъ'" => 'Ъ', "'ы'" => 'Ы', "'ь'" => 'Ь', "'э'" => 'Э', "'ю'" => 'Ю', "'я'" => 'Я', "'q'" => 'Q', "'w'" => 'W', "'e'" => 'E', "'r'" => 'R', "'t'" => 'T', "'y'" => 'Y', "'u'" => 'U', "'i'" => 'I', "'o'" => 'O', "'p'" => 'P', "'a'" => 'A', "'s'" => 'S', "'d'" => 'D', "'f'" => 'F', "'g'" => 'G', "'h'" => 'H', "'j'" => 'J', "'k'" => 'K', "'l'" => 'L', "'z'" => 'Z', "'x'" => 'X', "'c'" => 'C', "'v'" => 'V', "'b'" => 'B', "'n'" => 'N', "'m'" => 'M' );
		$search = array_keys($replacement);

		return preg_replace($search, $replacement, $var);
	}

	/*
		_strtolower собственная функция преобразование строки в нижний регистр
	*/
	function _strtolower($var) {
		$replacement = array( "'А'" => 'а', "'Б'" => 'б', "'В'" => 'в', "'Г'" => 'г', "'Д'" => 'д', "'Е'" => 'е', "'Ё'" => 'ё', "'Ж'" => 'ж', "'З'" => 'з', "'И'" => 'и', "'Й'" => 'й', "'К'" => 'к', "'Л'" => 'л', "'М'" => 'м', "'Н'" => 'н', "'О'" => 'о', "'П'" => 'п', "'Р'" => 'р', "'С'" => 'с', "'Т'" => 'т', "'У'" => 'у', "'Ф'" => 'ф', "'Х'" => 'х', "'Ц'" => 'ц', "'Ч'" => 'ч', "'Ш'" => 'ш', "'Щ'" => 'щ', "'Ъ'" => 'ъ', "'Ы'" => 'ы', "'Ь'" => 'ь', "'Э'" => 'э', "'Ю'" => 'ю', "'Я'" => 'я', "'Q'" => 'q', "'W'" => 'w', "'E'" => 'e', "'R'" => 'r', "'T'" => 't', "'Y'" => 'y', "'U'" => 'u', "'I'" => 'i', "'O'" => 'o', "'P'" => 'p', "'A'" => 'a', "'S'" => 's', "'D'" => 'd', "'F'" => 'f', "'G'" => 'g', "'H'" => 'h', "'J'" => 'j', "'K'" => 'k', "'L'" => 'l', "'Z'" => 'z', "'X'" => 'x', "'C'" => 'c', "'V'" => 'v', "'B'" => 'b', "'N'" => 'n', "'M'" => 'm' );
		$search = array_keys($replacement);

		return preg_replace($search, $replacement, $var);
	}

	/*
		Вычисляет и возвращет IP пользователя
		Используется пока только в функции конструкторе GBOOK()
	*/
	function _tst_ip($env) {
		if (( $ip = getenv($env) ) === false) {
			return false;
		}
		$i = ip2long($ip);
		if ($i === false || $i == -1 || $i == 0) {
			return false;
		}
		// RFC 1819
		// Class A: 10.0.0.0 - 10.255.255.255
		// Class B: 172.16.0.0 - 172.31.255.255
		// Class C: 192.168.0.0 - 192.168.255.255
		$a = ( $i >> 24 ) & 0xFF;
		$b = ( $i >> 16 ) & 0xFFFF;

		if (( $a == 0x0A ) || ( $b == 0xC0A8 ) || ( $b >= 0xAC10 && $b <= 0xAC1F )) {
			return false;
		}

		return $ip;
	}

	function Return_IP() {
		if (!empty( $_SERVER['REMOTE_ADDR'] )) {
			return $_SERVER['REMOTE_ADDR'];
		}

		if (( $ip = $this->_tst_ip('HTTP_CLIENT_IP') ) !== false) {
			return $ip;
		}
		if (( $ip = $this->_tst_ip('HTTP_X_FORWARDED_FOR') ) !== false) {
			return $ip;
		}
		if (( $ip = $this->_tst_ip('HTTP_X_FORWARDED') ) !== false) {
			return $ip;
		}
		if (( $ip = $this->_tst_ip('HTTP_FORWARDED_FOR') ) !== false) {
			return $ip;
		}
		if (( $ip = $this->_tst_ip('HTTP_FORWARDED') ) !== false) {
			return $ip;
		}
	}
}

?>