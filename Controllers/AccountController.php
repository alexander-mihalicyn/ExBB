<?php
namespace Forum\Controllers;

use ExBB\Request;
use ExBB\Helpers\LanguageHelper;

class AccountController extends BaseController {
	public function IndexAction() {
		$this->redirect('index.php');
	}

	public function deleteCookiesAction() {
		if (isset($this->request->server['HTTP_COOKIE'])) {
			$cookies = explode(';', $this->request->server['HTTP_COOKIE']);

			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);

				$this->request->setCookie($name, '', Request::COOKIE_EXPIRE_NEGATIVE, '');
				$this->request->setCookie($name, '', Request::COOKIE_EXPIRE_NEGATIVE, '/');
			}
		}

		$this->redirectPage(
			LanguageHelper::t('front_account', 'account'),
			LanguageHelper::t('front_account', 'cookiesDeleted'),
			'index.php'
		);
	}
}