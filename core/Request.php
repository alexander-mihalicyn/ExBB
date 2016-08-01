<?php
namespace ExBB;

/**
 * Класс для работы с данными запроса
 *
 * Class Request
 * @package ExBB
 */
class Request {
	/**
	 * Время жизни cookie 1 год
	 */
	const COOKIE_EXPIRE_YEAR = 31536000;
	/**
	 * Отрицательное время жизни cookie (для удаления)
	 */
	const COOKIE_EXPIRE_NEGATIVE = -1000;

	/**
	 * Флаг возврата значения cookie как строки
	 */
	const COOKIE_AS_STRING = 1;
	/**
	 * Флаг возврата значения cookie как массива
	 */
	const COOKIE_AS_ARRAY = 2;

	/**
	 * @var array массив GET данных
	 */
	public $query = [];
	/**
	 * @var array массив POST данных
	 */
	public $post = [];
	/**
	 * @var array массив COOKIE данных
	 */
	public $cookie = [];

	/**
	 * @var array массив данных $_FILES
	 */
	public $files = [];
	/**
	 * @var array массив данных $_SERVER
	 */
	public $server = [];

	/**
	 *
	 */
	public function __construct() {
		$this->query =& $_GET;
		$this->post =& $_POST;
		$this->request =& $_REQUEST;
		$this->cookie =& $_COOKIE;
		$this->files =& $_FILES;
		$this->server =& $_SERVER;
	}

	/**
	 * Устанавливает значение cookie
	 *
	 * @param string $name имя cookie
	 * @param string $value значение cookie
	 * @param int $expire срок жизни
	 * @param string $path путь
	 */
	public function setCookie($name, $value = '', $expire = self::COOKIE_EXPIRE_YEAR, $path='/') {
		setcookie($name, $value, time()+$expire, $path,'');
	}

	/**
	 * Получает значение cookie
	 *
	 * @param string $name имя cookie
	 * @param null|mixed $default значение по-умолчанию (возвращается при отсутствии cookie с таким именем)
	 * @param int $mode режим получения значения
	 *
	 * @return array|string|null
	 */
	public function getCookie($name, $default=null, $mode=self::COOKIE_AS_STRING) {
		if ( !isset( $this->cookie[$name] ) ) {
			return $default;
		}
		
		switch ($mode) {
			case self::COOKIE_AS_ARRAY:
				return ( !empty($this->cookie[$name]) ) ? unserialize( $this->cookie[$name] ) : [];
			break;
			
			case static::COOKIE_AS_STRING:
				return $this->cookie[$name];
			break;
		}
	}
}