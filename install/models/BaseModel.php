<?php
use ExBB\DataBase\FileDB;

/**
 * Class BaseModel
 */
class BaseModel {
	/**
	 * @var \FM;
	 */
	protected $fm;

	/**
	 * @var \ExBB\DataBase\FileDB
	 */
	protected $db;

	public function __construct() {
		global $fm;

		$this->fm = $fm;
		$this->db = new FileDB();
	}

	protected function sanitizeString($string) {
		return htmlspecialchars(trim($string), ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Проверяет валидность E-mail
	 *
	 * @param string $email E-mail
	 *
	 * @return bool
	 */
	protected function validateEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
}