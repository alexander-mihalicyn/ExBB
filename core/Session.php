<?php
namespace ExBB;

/**
 * Класс для работы с сессиями
 *
 * Class Session
 * @package ExBB
 */
class Session {
	/**
	 * @var array массив данных сессии
	 */
	public $data = [];

	/**
	 *
	 */
	public function __construct() {		
		if (!session_id()) {
			ini_set('session.use_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			
			session_set_cookie_params(0, '/');
			session_start();
		}
			
		$this->data =& $_SESSION;

	}

	/**
	 * Возвращает ID сессии
	 *
	 * @return string
	 */
	public function getId() {
		return session_id();
	}
}