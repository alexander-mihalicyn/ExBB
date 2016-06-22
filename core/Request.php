<?php
namespace ExBB;

class Request {
	const COOKIE_EXPIRE_YEAR = 31536000;
	const COOKIE_EXPIRE_NEGATIVE = -1000;
	
	const COOKIE_AS_STRING = 1;
	const COOKIE_AS_ARRAY = 2;
	
	public $query = [];
	public $post = [];
	public $cookie = [];
	public $files = [];
	public $server = [];
	
	public function __construct() {
		$this->query =& $_GET;
		$this->post =& $_POST;
		$this->request =& $_REQUEST;
		$this->cookie =& $_COOKIE;
		$this->files =& $_FILES;
		$this->server =& $_SERVER;
	}
	
	public function setCookie($name, $value = '', $expire = self::COOKIE_EXPIRE_YEAR) {
		setcookie($name, $value, time()+$expire, '/','');
	}
	
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