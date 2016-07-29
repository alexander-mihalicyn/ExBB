<?php

/**
 * Class BaseModel
 */
class BaseModel {
	/**
	 * @var \FM;
	 */
	protected $fm;

	public function __construct() {
		global $fm;

		$this->fm = $fm;
	}

	protected function sanitizeString($string) {
		return htmlspecialchars(trim($string), ENT_COMPAT, 'Windows-1251');
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