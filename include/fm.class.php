<?php
if (!defined('IN_EXBB')) {
	die( 'Hack attempt!' );
}

class FM extends VARS {

	/**
	 * Флаг русской локализации
	 *
	 * @var bool
	 */
	public $_RuLocale = true;

	/**
	 * @var array
	 */
	public $LANG = [ ];
	/*
		Начало отсчета времени работы скрипта integer
	*/
	/**
	 * @var float|int
	 */
	public $_StartTime = 0;
	/*
		Флаг Gzip сжатия страницы boolean
	*/
	/**
	 * @var bool
	 */
	public $_PageGziped = false;

	/**
	 * Текущее время
	 *
	 * @var int
	 */
	public $_Nowtime = 0;

	/**
	 * Массив с настройками форума
	 *
	 * @var array
	 */
	public $exbb = array();
	/*
		Массив статистики форума array
	*/
	/**
	 * @var array
	 */
	public $_Stats = array();

	/**
	 * Информация о пользователе по-умолчанию
	 *
	 * @var array
	 */
	public $user = array( 'id' => 0, 'unread' => 0, 'status' => 'gu', 'last_visit' => 0, 'private' => array(), 'new_pm' => false, 'timedif' => 0, 'visible' => false, 'upload' => false );
	/*
Атрибуты тега <body>
*/
	/**
	 * @var string
	 */
	public $_Body = '';

	/*
		Вставка в заголовок страницы string
	*/
	/**
	 * @var string
	 */
	public $_Link = '';
	/*
		Флаг нового сообщения в ЛС boolean
	*/
	/**
	 * @var string
	 */
	public $_NewEmail = '';
	/*
		Переменная для вывода банера string
	*/
	/**
	 * @var string
	 */
	public $_Baner = '';
	/*
		Переменная для вывода счетчиков string
	*/
	/**
	 * @var string
	 */
	public $_Counters = '';
	/*
		Название странице в заголовке <title> string
	*/
	/**
	 * @var string
	 */
	public $_Title = '';
	/**
	 * @var string
	 */
	public $_Keywords = '';
	/*
		Флаг модератора boolean
	*/
	/**
	 * @var bool
	 */
	public $_Moderator = false;
	/*
		Массив ID модераторов разделов форума array
	*/
	/**
	 * @var array
	 */
	public $_Moderators = array();
	/*
		Строка перечисления модераторов разделов форума string
	*/
	/**
	 * @var string
	 */
	public $_Modoutput = '';
	/*
		Массив ID пользователей в онлайн array
	*/
	/**
	 * @var array
	 */
	public $_OnlineIds = array();
	/*
		Кол-во гостей в online integer
	*/
	/**
	 * @var int
	 */
	public $_OnlineGuest = 0;
	/*
		Кол-во скрытых в online integer
	*/
	/**
	 * @var int
	 */
	public $_Invisible = 0;
	/*
		Кол-во зарегистрированых в online integer
	*/
	/**
	 * @var int
	 */
	public $_Members = 0;
	/*
		Кол-во зарегистрированых в online integer
	*/
	/**
	 * @var int
	 */
	public $_OnlineTotal = 0;
	/*
		Строка перечисления пользователей в online string
	*/
	/**
	 * @var string
	 */
	public $_MembersOutput = '';
	/*
		Массив дескрипторов открытых файлов array
	*/
	/**
	 * @var array
	 */
	public $_FilePointers = array();

	/*
		FM конструктор
	*/
	/**
	 *
	 */
	public function __construct() {
		@setlocale(LC_CTYPE, 'ru_RU.CP1251', 'ru_RU.cp1251', 'ru_RU', 'RU');
		if (!preg_match("#(russian\_russia.1251|ru\_ru.1251|russian\_russia|ru\_ru|russia|ru)#is", setlocale(LC_CTYPE, 0))) {
			$this->_RuLocale = false;
		}

		$this->_StartTime = $this->_Microtime();

		parent::__construct();

		require EXBB_DATA_CONFIG;
		$this->_Nowtime = time();
	}

	/*
		_Advertising Загрузка файлов с кодом банеров и счетчиков
	*/
	/**
	 *
	 */
	function _Advertising() {
		require_once( EXBB_DATA_BANNERS );
		require_once( EXBB_DATA_COUNTERS );
	}

	/*
		_BOARDSTATS Получение данных статистики форума
	*/
	/**
	 *
	 */
	function _BOARDSTATS() {
		$this->_Stats = $this->_Read(EXBB_DATA_BOARD_STATS);
	}

	/*
		_SAVE_STATS Сохранение изменений файла статистики
	*/
	/**
	 * @param $array
	 */
	function _SAVE_STATS($array) {
		$stats = $this->_Read2Write($fp_stats, EXBB_DATA_BOARD_STATS);
		foreach ($array as $key => $value) {
			switch ($value[1]) {
				case -1:
					$stats[$key] = $stats[$key] - $value[0];
				break;
				case 0:
					$stats[$key] = $value[0];
				break;
				case 1:
					$stats[$key] = $stats[$key] + $value[0];
				break;
			}
		}
		$this->_Write($fp_stats, $stats);

		return;
	}

	/**
	 * @return float
	 */
	function _TotalTime() {
		return round($this->_Microtime() - $this->_StartTime, 4);
	}

	/*
		_Microtime получение microtime
	*/
	/**
	 * @return float
	 */
	function _Microtime() {
		list( $usec, $sec ) = explode(" ", microtime());

		return ( (float)$usec + (float)$sec );
	}

	/*
		_DateFormat Форматирование даны в виде 21 Декабря, 2006 - 20:03:17
	*/
	/**
	 * @param $time
	 *
	 * @return string
	 */
	function _DateFormat($time) {
		$rus_m = array( '01' => 'Января', '02' => 'Февраля', '03' => 'Марта', '04' => 'Апреля', '05' => 'Мая', '06' => 'Июня', '07' => 'Июля', '08' => 'Августа', '09' => 'Сентября', '10' => 'Октября', '11' => 'Ноября', '12' => 'Декабря' );
		$currDay = strftime("%d", $time);
		$currMonth = strftime("%m", $time);
		$currYear = strftime("%Y", $time);
		$tm = date("H:i:s", $time);

		return $currDay . ' ' . $rus_m[$currMonth] . ', ' . $currYear . ' - ' . $tm;
	}

	/**
	 * @param $time
	 *
	 * @return string
	 */
	function _JoinDate($time) {
		$months = array( '00' => '', '01' => 'Янв.', '02' => 'Февр.', '03' => 'Март', '04' => 'Апр.', '05' => 'Май', '06' => 'Июнь', '07' => 'Июль', '08' => 'Авг.', '09' => 'Сент.', '10' => 'Окт.', '11' => 'Нояб.', '12' => 'Дек.' );
		$currMonth = strftime("%m", $time);
		$currYear = strftime("%Y", $time);

		return $months["$currMonth"] . " " . $currYear;
	}

	/**
	 * @return bool
	 */
	function _Authorization() {
		if (isset( $_SESSION['iden'] ) && isset( $_SESSION['mid'] ) && intval($_SESSION['mid']) != 0) {
			$this->user = $this->_Getmember(intval($_SESSION['mid']));
			if ($this->user === false || !is_array($this->user)) {
				$this->user['id'] = 0;
			}
			elseif ($_SESSION['iden'] != md5($this->user['name'] . $this->user['pass'] . _SESSION_ID)) {
				if (!session_destroy()) {
					session_unset();
				}
				$this->_setcookie('exbbn', '', -1);
				$this->_setcookie('exbbp', '', -1);
				$this->_setcookie('t_visits', '', -1);
				header("location: loginout.php");
				exit();
			}
			else {
				$this->_setcookie('lastvisit', $this->_Nowtime);
				//$this->user['last_visit'] = $_SESSION['last_visit'];
			}
		}
		elseif (isset( $_SESSION['mid'] ) && $_SESSION['mid'] == 0) {
			$this->user['id'] = 0;
		}
		else { //first run
			$id_cookie = ( isset( $_COOKIE['exbbn'] ) && trim($_COOKIE['exbbn']) !== '' ) ? (int)$_COOKIE['exbbn'] : false;
			$pass_cookie = ( isset( $_COOKIE['exbbp'] ) && trim($_COOKIE['exbbp']) !== '' ) ? $_COOKIE['exbbp'] : false;
			if ($id_cookie === false || $pass_cookie === false) {
				$this->user['id'] = 0;
			}
			else {
				if ($this->_Checkuser($id_cookie) === false) {
					$this->user['id'] = 0;
				}
				else {
					$user = $this->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $id_cookie . '.php');
					if ($user === false || !is_array($user) || $pass_cookie != md5($user['pass'])) {
						$this->_Fclose($fp_user);
						unset( $user );
						$this->user['id'] = 0;
					}
					else {
						$user['last_visit'] = isset( $_COOKIE['lastvisit'] ) ? (int)$_COOKIE['lastvisit'] : $this->_Nowtime;
						$this->_Write($fp_user, $user);
						$this->user = $user;
						unset( $user );
						$_SESSION['mid'] = (int)$this->user['id'];
						$_SESSION['sts'] = $this->user['status'];
						$_SESSION['lastposttime'] = isset( $this->user['lastpost']['date'] ) ? $this->user['lastpost']['date'] : $this->_Nowtime - 180;
						$_SESSION['iden'] = md5($this->user['name'] . $this->user['pass'] . _SESSION_ID);
						$this->_setcookie('exbbn', $this->user['id']);
						$this->_setcookie('exbbp', md5($this->user['pass']));
						$this->_setcookie('lastvisit', $this->_Nowtime);
						$this->_WriteLog($this->user['name']);
					}
				}
			}
		} //is first run
		$this->_Locale();
		$this->_CheckBannedIP();
		if (defined('IN_ADMIN')) {
			if (isset( $_SESSION['admin'] ) && $_SESSION['admin'] === true && isset( $_SESSION['admin_lasttime'] ) && $_SESSION['admin_lasttime'] > ( $this->_Nowtime - $this->exbb['ad_sestime'] )) {
				$_SESSION['admin_lasttime'] = $this->_Nowtime;
			}
			else {
				header("Location: loginout.php?action=loginadmin");
				exit;
			}
		}

		return true;
	}

	/*
	_AutoUnBan разбанивание по окончании времени действия бана
	*/
	/**
	 * @param $user
	 * @param bool|FALSE $msg
	 *
	 * @return bool|string
	 */
	function _AutoUnBan($user, $msg = false) {
		if ($usrban = $this->_GetBanMember($user['id'])) {
			if (!isset( $usrban['permanently'] ) && $usrban['end'] < time()) {
				$user_ban = $this->_Read2Write($fp_ban, EXBB_DATA_DIR_BANNED_MEMBERS . '/' . $user['id'] . '.php');
				$user_ban['whounban_id'] = 0;
				$user_ban['whounban_name'] = 'auto';
				$user_ban['days'] = 0;
				$this->_Write($fp_ban, $user_ban);

				$user_unban = $this->_Read2Write($fp_user_unban, EXBB_DATA_DIR_MEMBERS . '/'. $user['id'] . '.php');
				$user_unban['status'] = 'me';
				$this->_Write($fp_user_unban, $user_unban);

				$banlist = $this->_Read2Write($fp_banlist, EXBB_DATA_BANNED_USERS_LIST, false);
				if (isset( $banlist[$user['id']] )) {
					unset( $banlist[$user['id']] );
				}
				$this->_Write($fp_banlist, $banlist);

				$this->_Mail($this->exbb['boardname'], $this->exbb['adminemail'], $this->exbb['adminemail'], 'AutoUnBanned User (PHP.SU)', 'AutoUnBan ' . $user['name']);
				$this->_WriteLog(sprintf('AutoUnBan', 'Auto', '<b>' . $user['name'] . '</b>'), 2); // Запись в лог
				return false;
			}

			if ($msg) {
				if (!isset( $usrban['permanently'] )) {
					return '<br />Причина: ' . $usrban['reason'] . '<br /> Дней: ' . $usrban['days'] . '<br /> Дата окончания: ' . $this->_DateFormat($usrban['end']);
				}
				else {
					return '<br />Причина: ' . $usrban['reason'] . '<br /> Срок: вечно';
				}
			}
		}

		if ($msg) {
			return '<br /> Вы забанены перманентно';
		}

		return true;
	}

	/*
		_Locale установка локальных настроек форума для пользователя и гостя
	*/
	/**
	 * @return bool
	 */
	function _Locale() {
		$deflang = $this->exbb['default_lang'];
		$defskin = $this->exbb['default_style'];

		if ($this->user['id'] != 0) {
			$this->user['unread'] = $this->CheckUnread();
			if (isset( $this->user['lang'] ) && !empty( $this->user['lang'] )) {
				$deflang = $this->user['lang'];
			}
			if (isset( $this->user['skin'] ) && !empty( $this->user['skin'] )) {
				$defskin = $this->user['skin'];
			}

			if (!is_dir('language/' . $deflang)) {
				$deflang = $this->exbb['default_lang'];
			}
			if (!is_dir('templates/' . $defskin)) {
				$defskin = $this->exbb['default_style'];
			}

			$this->user['posts2page'] = ( $this->exbb['userperpage'] === true && isset( $this->user['posts2page'] ) && $this->user['posts2page'] <= 40 ) ? $this->user['posts2page'] : $this->exbb['posts_per_page'];
			$this->user['topics2page'] = ( $this->exbb['userperpage'] === true && isset( $this->user['topics2page'] ) && $this->user['topics2page'] <= 50 ) ? $this->user['topics2page'] : $this->exbb['topics_per_page'];

			if ($this->user['status'] == 'ad') {
				DEFINE('IS_ADMIN', true);
			}
		}
		else {
			$_SESSION['mid'] = 0;
			$_SESSION['lastposttime'] = $this->_Nowtime - 180;
			$this->user['last_visit'] = $this->_Nowtime;
			$this->user['posts2page'] = $this->exbb['posts_per_page'];
			$this->user['topics2page'] = $this->exbb['topics_per_page'];
			$this->_setcookie('lastvisit', $this->_Nowtime);
		}

		if (!is_dir('./language/' . $deflang)) {
			$deflang = 'russian';
		}
		if (!is_dir('./templates/' . $defskin)) {
			die( 'ERROR! No skin files in templates folder!' );
		}

		define("DEF_LANG", $deflang);
		define("DEF_SKIN", $defskin);

		if (defined('IN_ADMIN')) {
			include( './language/' . DEF_LANG . '/lang_admin_all.php' );
		}
		else {
			include( './language/' . DEF_LANG . '/lang_front_all.php' );
		}

		if ($this->user['id'] == 0) {
			$this->user['name'] = $this->LANG['Guest'];
		}
		elseif ($this->user['status'] == 'banned') {
			if ($text_ban = $this->_AutoUnBan($this->user, true)) {
				$this->_Message($this->LANG['MainMsg'], $this->LANG['LoginDeniedBan'] . $text_ban, '', 1);
			}
			$this->_Message($this->LANG['MainMsg'], $this->LANG['LoginDeniedBan'], '', 1);
		}

		if (!defined('IN_ADMIN') && isset( $this->user['new_pm'] ) && $this->user['new_pm'] === true) {
			include( './templates/' . DEF_SKIN . '/newmail.tpl' );
		}

		return true;
	}

	/*
		_CheckBannedIP проверка заблокированных IP адресов
	*/
	/**
	 * @return bool
	 */
	function _CheckBannedIP() {
		$banneddata = array_filter($this->_Read(EXBB_DATA_BANNED_BY_IP_LIST), "Banned");
		if (count($banneddata)) {
			$id = key($banneddata);
			$this->_Message(sprintf($this->LANG['YourIPBlocked'], $this->_IP), sprintf($this->LANG['YouBannedMess'], $banneddata[$id]['ipbd'], $this->exbb['adminemail']));
		}

		return true;
	}

	/*
		CheckUnread подсчет непрочитанных личных сообщений пользователя
	*/
	/**
	 * @return int
	 */
	function CheckUnread() {
		$unread = 0;
		$allmessages = $this->_Read(EXBB_DATA_DIR_MESSAGES . '/' . $this->user['id'] . '-msg.php');
		foreach ($allmessages as $date => $ms) {
			if (!$ms['status']) {
				$unread++;
			}
		}

		return $unread;

	}

	/*
		_LoadLang Функция загрузки языковых файлов
	*/
	/**
	 * @param $current
	 * @param bool|FALSE $admin
	 */
	function _LoadLang($current, $admin = false) {
		include_once( './language/' . DEF_LANG . '/lang_' . ( ( $admin === true ) ? 'admin' : 'front' ) . '_' . $current . '.php' );
	}

	/*
		_LoadModuleLang Функция загрузки языковых файлов для модулей
	*/
	/**
	 * @param $current
	 * @param bool|FALSE $admin
	 */
	function _LoadModuleLang($current, $admin = false) {
		include_once( 'modules/' . $current . '/language/' . DEF_LANG . '/lang' . ( ( $admin ) ? '_admin' : '' ) . '.php' );
	}


	/********************************************************************************
	 *                                                                                *
	 *                    Файловые функции                                            *
	 *                                                                                *
	 ********************************************************************************/
	/*
		_Read читаем файл возвращаем массив
	*/
	/**
	 * @param $filename
	 * @param bool|TRUE $newfile
	 *
	 * @return array|mixed
	 */
	function _Read($filename, $newfile = true) {
		if (!file_exists($filename)/* && $newfile === TRUE*/) {
			return array();
			//fclose(fopen($filename,'a+'));
			//@chmod($filename,$this->exbb['ch_files']);
		}
		$fp = @fopen($filename, 'r') or die( 'Could not read from the file <b>' . $filename . '</b>' );
		//$this->_Flock($fp,$filename);
		flock($fp, 1);
		$filesize = filesize($filename);
		$filesize = ( $filesize === 0 ) ? 1 : $filesize - 8;
		fseek($fp, 8);
		$str = fread($fp, $filesize);
		flock($fp, 3);
		fclose($fp);

		return ( !empty( $str ) ) ? unserialize($str) : array();
	}

	/*
		_Read2Write Читает файл для возможной записи в него не закрывая файла
	*/
	/**
	 * @param $fp
	 * @param $filename
	 *
	 * @return array|mixed
	 */
	function _Read2Write(&$fp, $filename) {
		if (!file_exists($filename)/* && $newfile === TRUE*/) {
			@fclose(@fopen($filename, 'a+'));
			@chmod($filename, $this->exbb['ch_files']);
		}
		$fp = @fopen($filename, 'a+') or die( 'Could not write in the file <b>' . $filename . '</b>' );
		//$this->_Flock($fp,$filename,LOCK_EX);
		flock($fp, /*1*/
			2);
		$filesize = filesize($filename);
		$filesize = ( $filesize === 0 ) ? 1 : $filesize - 8;
		fseek($fp, 8);
		$str = fread($fp, $filesize);
		//flock($fp, 2);
		$this->_FilePointers[(int)$fp] = $fp;

		return ( !empty( $str ) ) ? unserialize($str) : array();
	}

	/*
		_Write запись в файл открытый функцией _Read2Write
	*/

	/**
	 * @param $fp
	 * @param $arr
	 */
	function _Write(&$fp, $arr) {
		fseek($fp, 0);
		ftruncate($fp, 0);
		fwrite($fp, '<?die;?>' . serialize($arr));
		fflush($fp);
		flock($fp, 3);
		fclose($fp);
		clearstatcache();
		unset( $arr, $this->_FilePointers[$fp] );

		return;
	}

	/*
		_FcloseAll закрывает все открытые файлы
	*/
	/**
	 *
	 */
	function _FcloseAll() {
		foreach ($this->_FilePointers as $fp) {
			if (is_resource($fp)) {
				fclose($fp);
			}
		}
		$this->_FilePointers = array();
	}

	/*
		_Fclose закрывает файл по дескриптору переданному в аргументе
	*/
	/**
	 * @param $fp
	 */
	function _Fclose($fp) {
		fclose($fp);
		unset( $this->_FilePointers[$fp] );
	}

	/**
	 * @param $filename
	 * @param $text
	 */
	function _WriteText($filename, $text) {
		if (!file_exists($filename)) {
			@fclose(@fopen($filename, 'a+'));
			@chmod($filename, $this->exbb['ch_files']);
		}
		$fp = fopen($filename, 'a+');
		//$this->_Flock($fp,$filename,LOCK_EX);
		flock($fp, 2);
		ftruncate($fp, 0);
		fwrite($fp, $text);
		fflush($fp);
		flock($fp, 3);
		fclose($fp);
	}
	/********************************************************************************
	 *                                                                                *
	 *                    Функции пользователя                                        *
	 *                                                                                *
	 ********************************************************************************/

	/**
	 * Поверяет существование пользователя
	 *
	 * @param int $uid ID пользователя
	 *
	 * @return bool
	 */
	function _Checkuser($uid) {
		return file_exists(EXBB_DATA_DIR_MEMBERS . '/' . $uid . '.php');
	}

	/*
		_Getmember возвращает инфо о пользователе с ID переданным в аргументе
	*/
	/**
	 * @param $uid
	 *
	 * @return array|bool|mixed
	 */
	function _Getmember($uid) {
		if ($this->_Checkuser($uid)) {
			$userfile = EXBB_DATA_DIR_MEMBERS . '/' . $uid . '.php';

			return $this->_Read($userfile, false);
		}

		return false;
	}

	/**
	 * @param $uid
	 *
	 * @return bool
	 */
	function _CheckBanMember($uid) {
		if (file_exists(EXBB_DATA_DIR_BANNED_MEMBERS . '/' . $uid . '.php')) {
			return true;
		}

		return false;
	}

	/**
	 * @param $uid
	 *
	 * @return array|bool|mixed
	 */
	function _GetBanMember($uid) {
		if ($this->_Checkuser($uid) && $this->_CheckBanMember($uid)) {
			$userfile = EXBB_DATA_DIR_BANNED_MEMBERS . '/' . $uid . '.php';

			return $this->_Read($userfile, false);
		}

		return false;
	}

	/********************************************************************************
	 *                                                                                *
	 *                    Log функции                                                    *
	 *                                                                                *
	 ********************************************************************************/
	/*
		_WriteLog запись в логи действий на форуме
	*/
	/**
	 * @param $action
	 * @param int $admin
	 */
	function _WriteLog($action, $admin = 0) {
		if ($this->exbb['log'] === false) {
			return;
		}
		$action = ( $admin === 1 ) ? $action . ' (ad)' : $action;
		$action = ( $admin === 2 ) ? $action . ' (mo)' : $action; // Если 2, то это модерация
		$logfilename = EXBB_DATA_DIR_LOGS . '/' . mktime(0, 0, 0, date("m"), date("d"), date("Y")) . ".php";
		$start = ( $exs = file_exists($logfilename) ) ? '' : "<?die();?>\n";
		if (!$exs) {
			@fclose(@fopen($logfilename, "a+"));
			@chmod($logfilename, $this->exbb['ch_files']);
		}
		$fp = fopen($logfilename, "a");
		flock($fp, 2);
		fwrite($fp, $start . date("H:i:s", $this->_Nowtime) . ' :: ' . $this->user['name'] . ' :: ' . $action . ' :: ' . $this->_IP . "\n");
		fclose($fp);

		return;
	}


	/********************************************************************************
	 *                                                                                *
	 *                    Функции    вывода сообщений                                    *
	 *                                                                                *
	 ********************************************************************************/

	/*
		_Message функция вывода сообщений
	*/
	/**
	 * @var int
	 */
	public $_Refresh = 3;

	/**
	 * @param $msg_title
	 * @param $msg_text
	 * @param string $meta
	 * @param int $mode
	 */
	function _Message($msg_title, $msg_text, $meta = '', $mode = 0) {
		if (!$mode) {
			echo '&nbsp;';
		}
		$this->_Link = ( $meta !== '' ) ? "<meta http-equiv='refresh' content='" . $this->_Refresh . "; url=" . $meta . "'>" : '';
		$return = ( $meta === '' ) ? ' <a href="javascript:history.go(-1)"> << ' . $this->LANG['Back'] . '</a>' : $this->LANG['ReloadingPage'];
		$this->_Title = ' :: ' . $msg_title;
		$skins = array( 0 => array( 0 => './templates/' . DEF_SKIN . '/all_header.tpl', 1 => './templates/' . DEF_SKIN . '/error.tpl', 2 => './templates/' . DEF_SKIN . '/footer.tpl' ), 1 => array( 0 => './admin/all_header.tpl', 1 => './admin/error.tpl', 2 => './admin/footer.tpl' ) );
		include( $skins[$mode][0] );
		include( $skins[$mode][1] );
		include( $skins[$mode][2] );
		include( 'page_tail.php' );
	}


	/********************************************************************************
	 *                                                                                *
	 *                    Функции    контроля посещений                                    *
	 *                                                                                *
	 ********************************************************************************/

	public $_IsSpider = false;

	/*
		_IsSpider проверка поискового паука
	*/
	/**
	 *
	 */
	function _IsSpider() {
		$spiders = array( 'Aport', 'archive_org', 'TurtleScanner', 'Nutscrape', 'WebSpeedReader', 'StackRambler', 'NetCaptor', 'Bond', 'Wget', 'Space Bison', 'msnbot', 'Yahoo', 'Mediapartners-Google', 'Googlebot', 'Yahoo-MMCrawler', 'Google', 'Slurp', 'ZyBorg', 'Gigabot', 'Exabot', 'Yandex', 'WebAlta', 'WebCrawler' );

		foreach ($spiders as $spidername) {
			if (stristr($_SERVER['HTTP_USER_AGENT'], $spidername) !== false) {
				$this->_IsSpider = $spidername;
				break;
			}
		}

		return;
	}

	/*
		_OnlineLog функция ведет учет on-line пользователей
	*/
	/**
	 * @param $where
	 * @param $privateID
	 * @param bool|false $show
	 *
	 * @return array|mixed
	 */
	function _OnlineLog($where, $privateID, $show = false) {
		global $statvisit, $today;

		$this->_IsSpider();
		$output = array();
		$expire = $this->_Nowtime - ( $this->exbb['membergone'] * 60 );
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$sessid = md5($this->_IP . $agent);//ID  в массиве онлайн
		$status = ( !defined('IS_ADMIN') && $this->user['status'] != 'sm' && in_array($this->user['id'], $this->_Moderators) ) ? 'mo' : $this->user['status'];

		$visible = false;
		if ($this->exbb['visiblemode'] === true) {
			$visible = ( $this->user['visible'] === true ) ? true : false;
		}

		$onlinedata = $this->_Read2Write($fp_online, EXBB_DATA_MEMBERS_ONLINE);

		// Advanced Visit Stats for ExBB FM 1.0 RC1 by yura3d
		$statvisit = $today = false;
		if ($this->exbb['statvisit']) {
			$statvisit = $this->_Read('modules/statvisit/data/config.php');
			if ($statvisit['day']) {
				$day = date('d', $this->_Nowtime);

				$today = $this->_Read2Write($fp_today, 'modules/statvisit/data/today.php');

				if (empty( $today ) || $today['day'] != $day) {
					$today = array( 'day' => $day, 'members' => array(), 'guests' => 0, );
				}

				if (!$this->user['id'] && !isset( $onlinedata[$sessid] ) && empty( $onlinedata[$sessid]['id'] ) && !$this->_IsSpider) {
					$today['guests']++;
				}
				elseif ($this->user['id']) {
					$today['members'][$this->user['id']] = array( 'n' => $this->user['name'], 's' => $this->user['status'], 'v' => $visible );
				}

				$this->_Write($fp_today, $today);
			}
		}

		$onlinedata[$sessid] = array( 'ip' => $this->_IP, 'n' => $this->user['name'], 'id' => $this->user['id'], 't' => $this->_Nowtime, 'in' => $where, 'pf' => $privateID, 'st' => $status, 'v' => $visible, 'ua' => $_SERVER['HTTP_USER_AGENT'], 'b' => $this->_IsSpider );
		require( 'modules/watches/_includeFm.php' );

		foreach ($onlinedata as $id => $info) {
			if ($expire > $info['t']) {
				// Решение проблемы актуальной даты последнего посещения
				// Если пользователь уходит без нажатия кнопки "Выход" то после сдыхания его сессии
				// мы обновим инфу в профиле о дате последнего посещения :)
				if (!empty( $info['id'] ) && file_exists(EXBB_DATA_DIR_MEMBERS . '/' . $info['id'] . '.php')) {
					$user = $this->_Read2Write($file, EXBB_DATA_DIR_MEMBERS . '/' . $info['id'] . '.php');
					$user['last_visit'] = $info['t'];
					$this->_Write($file, $user);

					if ($this->exbb['watches']) {
						_watchesIncludeFmDeadline($info['id']);
					}

				}

				unset( $onlinedata[$id] );
				continue;
			}

			if (( $info['b'] !== false && $this->_IsSpider == $info['b'] ) || ( $info['id'] != 0 && $info['n'] == $this->user['name'] && $id != $sessid )) {
				unset( $onlinedata[$id] );
				continue;
			}

			$this->_OnlineIds[$info['id']] = 1;

			if ($show === true) {
				switch ($info['id']) {
					case 0:
						$this->_OnlineGuest++;
					break 1;
					default:
						if ($this->exbb['visiblemode'] && $info['v'] === true) {
							$this->_Invisible++;
							break 1;
						}
						switch ($info['st']) {
							case 'ad':
								$class = "admin";
							break;
							case 'sm':
								$class = "supmoder";
							break;
							case 'mo':
								$class = "moder";
							break;
							default:
								$class = "noclass";
							break;
						}
						$output[] = '<a href="profile.php?action=show&member=' . $info['id'] . '" class="' . $class . '">' . $info['n'] . '</a>';
						$this->_Members++;
					break 1;
				}
			}
		}

		$this->_OnlineTotal = sizeof($onlinedata);
		unset( $this->_OnlineIds[0] );
		$this->_Write($fp_online, $onlinedata);

		$this->_Stats = $this->_Read2Write($fp_maxonline, EXBB_DATA_BOARD_STATS);
		if ($this->_OnlineTotal > $this->_Stats['max_online']) {
			$this->_Stats['max_online'] = $this->_OnlineTotal;
			$this->_Stats['max_time'] = $this->_Nowtime;
			$this->_Write($fp_maxonline, $this->_Stats);
		}
		else {
			$this->_Fclose($fp_maxonline);
		}

		if ($show === true) {
			$this->_MembersOutput = implode(' &raquo; ', $output);
			unset( $output );
		}

		return $onlinedata;
	}

	/*
		_GetModerators возвращает список модераторов и флаг модератора
	*/
	/**
	 * @param $where
	 * @param $data
	 */
	function _GetModerators($where, $data) {
		$mod_url = array();
		$moderators = $data[$where]['moderator'];
		foreach ($moderators as $id => $name) {
			$mod_url[] = '<a href="profile.php?action=show&member=' . $id . '">' . $name . '</a>';
			$this->_Moderators[] = $id;
			if ($this->user['id'] == $id) {
				$this->_Moderator = true;
			}
		}

		$this->_Modoutput = ( count($mod_url) ) ? implode(', ', $mod_url) : $this->LANG['No'];
		$this->_Modoutput = ( ( count($mod_url) > 1 ) ? $this->LANG['Moderators'] : $this->LANG['Moderator'] ) . ': ' . $this->_Modoutput;
		if (!$mod_url) {
			$this->_Modoutput = '';
		}

		if (defined('IS_ADMIN')) {
			$this->_Moderator = true;
		}
		elseif ($this->user['status'] == 'sm') {
			$this->_Moderators[] = $this->user['id'];
			$this->_Moderator = true;
		}

		return;
	}


	/********************************************************************************
	 *                                                                                *
	 *                    Функции    разделов, тем и сообщений                            *
	 *                    Обработка выходных данных                                    *
	 *                                                                                *
	 ********************************************************************************/

	public $_Smiles = false;

	/*
		setsmiles замена кодов смайлов на изображения
	*/
	/**
	 * @param $string
	 *
	 * @return string
	 */
	function setsmiles($string) {
		if ($this->_Smiles === false) {
			function SmileMap($arr) {
				return '<img src="./im/emoticons/' . $arr['img'] . '" border="0" alt="' . $arr['emt'] . '" title="' . $arr['emt'] . '">';
			}

			$allsmiles = $this->_Read(EXBB_DATA_SMILES_LIST);
			$this->_Smiles = array_map("SmileMap", $allsmiles['smiles']);
			unset( $allsmiles );
		}

		return strtr($string, $this->_Smiles);
	}

	/*
		html_replace заменет HTML сущности с учетом таблиц преобразований    ENT_QUOTES
	*/
	/**
	 * @param $string
	 *
	 * @return string
	 */
	function html_replace($string) {
		$_TransTable = array( '&amp;' => '&', '&quot;' => '"', '&#039;' => '\'', '&lt;' => '<', '&gt;' => '>' );

		return strtr($string, $_TransTable);
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	function url_text($matches) {
		if (!$this->user['id']) {
			return '<i>' . $this->LANG['ViewLinkReged'] . '</i> ';
		}
		$matches[3] = trim($matches[3]);
		if ($matches[1] === $matches[3] && strlen($matches[3]) > 32) {
			$matches[3] = preg_replace("#(.{32})(.+)(.{16})#is", "$1...$3", $matches[3]);
		}
		elseif (!preg_match("#src=\"http://[A-Za-z0-9-_\./\?\&\+\;\,~=]+?\"#is", $matches[3])) {
			$matches[3] = $this->chunk_split($matches[3]);
		}
		$Link = ( empty( $matches[2] ) ) ? "http://" . $matches[1] : $matches[1];
		if ($this->exbb['redirect'] && !stristr($Link, 'http://www.' . $this->exbb_domain) && !stristr($Link, 'http://' . $this->exbb_domain)) {
			$Link = $this->out_redir . $Link;
		}

		return "<a href=\"" . $Link . "\" target=\"_blank\">{$matches[3]}</a>";
	}

	// Spoiler for ExBB FM 1.0 RC2 by yura3d
	/**
	 * @param $matches
	 *
	 * @return string
	 */
	function spoiler($matches) {
		static $sp = 0;
		$sp++;
		$text = ( $matches[2] ) ? $matches[2] : $this->LANG['Spoiler'];

		return '<div class="block"><b>' . $text . '</b> <span id="sp' . $sp . '" style="line-height: 18px">(<a href="#" onClick="spoiler(\'' . $sp . '\'); return false">' . $this->LANG['SpoilerShow'] . '</a>)</span><div class="quote" id="spoiler' . $sp . '" style="display: none">' . $matches[3] . '</div></div>';
	}

	// Hide text mod for ExBB FM 1.0 RC2 by yura3d
	/**
	 * @param $matches
	 *
	 * @return string
	 */
	function hide_text($matches) {
		if ($matches[2] && @$this->user['posts'] < $matches[2]) {
			return '<div class="block"><b>' . $this->LANG['HideText'] . '</b><div class="quote">' . sprintf($this->LANG['HideMesN'], $matches[2]) . '</div></div>';
		}
		elseif (!$this->user['id']) {
			return '<div class="block"><b>' . $this->LANG['HideText'] . '</b><div class="quote">' . $this->LANG['HideMes'] . '</div></div>';
		}

		return $matches[3];
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	function youtube($matches) {
		$title = ( $matches[2] !== '' ) ? '<b>' . $matches[2] . '</b><br>' : '';

		return $title . '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/' . $matches[3] . '&hl=ru&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $matches[3] . '&hl=ru&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object><br>';
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	function rutube($matches) {
		$title = ( $matches[2] !== '' ) ? '<b>' . $matches[2] . '</b><br>' : '';

		return $title . '<iframe width="720" height="405" src="//rutube.ru/play/embed/' . $matches[5] . '/" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen></iframe>';
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	function vkvideo($matches) {
		$title = ( $matches[2] !== '' ) ? '<b>' . $matches[2] . '</b><br />' : '';

		$title .= "<iframe width=\"607\" height=\"360\" frameborder=\"0\" src=\"http://$matches[3]/video_ext.php?oid=$matches[4]&id=$matches[5]&hash=$matches[6]&$matches[7]\"></iframe>";

		return $title;
	}

	/**
	 * @param $string
	 * @param bool|FALSE $html
	 * @param bool|TRUE $smiles
	 * @param string $findstring
	 *
	 * @return string
	 */
	function formatpost($string, $html = false, $smiles = true, $findstring = '') {
		global $fm, $array, $num, $patern;

		if ($this->exbb['exbbcodes'] === false) {
			return nl2br(( $smiles === true && $this->exbb['emoticons'] === true ) ? $this->setsmiles($string) : $string);
		}

		$array = array();
		$num = 0;

		$string = preg_replace_callback("#\[code\](.+?)\[/code\]#is", create_function('$matches', 'global $array,$num;
													$key = "%__".$num."__%";
													$num++;
													$array[$key] = "<div class=\"block\"><b>CODE:</b><div class=\"htmlcode\">".$matches[1]."</div></div>";
													return $key;'), $string);

		$string = preg_replace_callback("#\[php\](.+?)\[/php\]#is", create_function('$matches', 'global $array,$num;
													$key = "%__".$num."__%";
													$num++;
													$array[$key] = "<div class=\"block\"><b>PHP:</b><div class=\"phpcode\">".$matches[1]."</div></div>";
													return $key;'), $string);

		if ($html === true) {
			$string = $this->html_replace($string);
		}

		if ($smiles === true && $this->exbb['emoticons'] === true) {
			$string = $this->setsmiles($string);
		}

		$search = array( "#\[hr\]#i", "#\[s\](.*?)\[/s\]#is", "#\[b\](.+?)\[/b\]#is", "#\[i\](.+?)\[/i\]#is", "#\[u\](.+?)\[/u\]#is", "#\[c\](.+?)\[/c\]#is", "#\[left\](.+?)\[/left\]#is", "#\[center\](.+?)\[/center\]#is", "#\[right\](.+?)\[/right\]#is", "#\[justify\](.+?)\[/justify\]#is", "#\[sub\](.+?)\[/sub\]#is", "#\[sup\](.+?)\[/sup\]#is", "#\[h1\](.+?)\[/h1\]#is", "#\[h2\](.+?)\[/h2\]#is", "#\[big\](.+?)\[/big\]#is", "#\[small\](.+?)\[/small\]#is", "#\[list\](.+?)\[\/list\]#is", "#\[list=(A|1)\](.+?)\[\/list\]#is", "#\[\*\]#is", "#\[marquee\](.+?)\[/marquee\]#is", "#\[off\](.+?)\[/off\]#is", "#\[color=\s*([A-Za-z]{3,10}|\#[A-Za-z0-9]{6})\s*\](.+?)\[/color\]#is", "#\[size=\s*([0-9]{1,2})\s*\](.+?)\[/size\]#is", "#\[email\]\s*([a-zA-Z0-9\-_]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?))\s*\[/email\]#is" );

		$replace = array( "<hr width=\"40%\" align=\"left\">", "<s>$1</s>", "<b>$1</b>", "<i>$1</i>", "<u>$1</u>", "<center>$1</center>", "<div align=\"left\">$1</div>", "<center>$1</center>", "<div align=\"right\">$1</div>", "<div align=\"justify\">$1</div>", "<sub>$1</sub>", "<sup>$1</sup>", "<h1>$1</h1>", "<h2>$1</h2>", "<big>$1</big>", "<small>$1</small>", "<ul>$1</ul>", "<ol type=\"$1\">$2</ol>", "<li>", "<marquee>$1</marquee>", "<br><img src=\"./im/emoticons/off.gif\" border=\"0\"><div class=\"offtop\">$1</div>", "<span style=\"color: $1;\">$2</span>", "<span style=\"font-size: $1px;\">$2</span>", "<a href=\"mailto:$1\">$1</a>" );

		$string = preg_replace($search, $replace, $string);
		$string = preg_replace_callback("#\[spoiler(\=(.+?)|)\](.+?)\[\/spoiler\]#is", array( $this, 'spoiler' ), $string);
		$string = preg_replace_callback("#\[hide(\=([0-9]+)|)\](.+?)\[\/hide\]#is", array( $this, 'hide_text' ), $string);
		$string = preg_replace_callback('#\[youtube(=(.+?)|)\].+?youtube.com/watch\?v=(.+?)(&.+?|)\[/youtube\]#is', array( $this, 'youtube' ), $string);
		$string = preg_replace_callback('#\[youtube(=(.+?)|)\].+?youtu.be/(.+?)(&.+?|)\[/youtube\]#is', array( $this, 'youtube' ), $string);
		$string = preg_replace_callback('#\[rutube(=(.+?)|)\]((http://|)www\.|http://)rutube.ru/video/(.*|\n*)/\[/rutube\]#is', array( $this, 'rutube' ), $string);
		$string = preg_replace_callback("#\[vkvideo(=(.+?)|)\].+?(vkontakte.ru|vk.com)/video_ext.php\?oid=([-0-9]+)&amp;id=([0-9]+)&amp;hash=([0-9a-f]{1,16})&amp;(.+?)&quot;.+?\[/vkvideo\]#is", array( $this, 'vkvideo' ), $string);
		$string = preg_replace_callback("#\[(rus)\]([^\[]*(\[\/{0,1}(?!\\1\])[^\[]*)*?)\[/\\1\]#is", create_function('$matches', '$trans = array("YO"=>"Ё",	"yo"=>"ё",	"ZH"=>"Ж",	"zh"=>"ж",	"IY"=>"Й",	"iy"=>"й",
																	"SH"=>"Ш",	"sh"=>"ш",	"SCH"=>"Щ",	"sch"=>"щ",	"IU"=>"Ы",	"iu"=>"ы",
																	"CH"=>"Ч",	"ch"=>"ч",	"Ch"=>"Ч",	"ch"=>"ч",	"YE"=>"Э",	"ye"=>"э",
																	"YU"=>"Ю",	"yu"=>"ю",	"YA"=>"Я",	"ya"=>"я",	"A"=>"А",	"a"=>"а",
																	"B"=>"Б",	"b"=>"б",	"V"=>"В",	"v"=>"в",	"G"=>"Г",	"g"=>"г",
																	"D"=>"Д",	"d"=>"д",	"E"=>"Е",	"e"=>"е",	"Z"=>"З",	"z"=>"з",
																	"I"=>"И",	"i"=>"и",	"K"=>"К",	"k"=>"к",	"L"=>"Л",	"l"=>"л",
																	"M"=>"М",	"m"=>"м",	"N"=>"Н",	"n"=>"н",	"O"=>"О",	"o"=>"о",
																	"P"=>"П",	"p"=>"п",	"R"=>"Р",	"r"=>"р",	"S"=>"С",	"s"=>"с",
																	"T"=>"Т",	"t"=>"т",	"U"=>"У",	"u"=>"у",	"F"=>"Ф",	"f"=>"ф",
																	"H"=>"Х",	"h"=>"х",	"C"=>"Ц",	"c"=>"ц",	"\'\'"=>"Ъ","\'\'"=>"ъ",
																	"\'"=>"Ь",	"\'"=>"ь"
													);
													return "<br><b><i>Перевод с транслита</i></b>:<br>".strtr($matches[2], $trans)."<br>";'), $string);

		$string = preg_replace_callback("#\[img\]\s*((http://|www\.)[A-Za-z0-9-_\./\?\%\&\+\;\,~=]+?)\s*\[/img\]#is", create_function('$matches', 'global $fm;
													$matches[1] = ($matches[2] === "www.") ? "http://".$matches[1]:$matches[1];
													return ($fm->exbb[\'imgpreview\'] === TRUE) ? replace_img_link($matches[1]):"<img src=\"".$matches[1]."\"> ";'), $string);

		$patern = '(?:
								((?:http|https|ftp)://)
								|
								www\.
							)
							(?> [a-zа-яА-ЯёЁ0-9_-]+ (?>\.[a-zа-яА-ЯёЁ0-9_-]+)* )
							(?: : \d+)?
							(?: & | &amp; | [^[\]&\s\x00»«"<>])*
							(?:
								(?<! [[:punct:]] )
								|
								(?<= & | &amp; | [-/&+=*] )
							)';

		$string = preg_replace_callback("#\[url\](" . $patern . ")\[\/url\]#isx", create_function('$matches', 'if (!' . $this->user['id'] . ') return \'<i>' . $this->LANG['ViewLinkReged'] . '</i> \'; global $fm; $LinkText = preg_replace("#^(.{32})(.+)(.{16})#is","$1...$3",trim($matches[1]));
													$Link = (empty($matches[2])) ? "http://".$matches[1]:$matches[1];
													if ($fm->exbb[\'redirect\'] && !stristr($Link, \'http://www.\'.$fm->exbb_domain) && !stristr($Link, \'http://\'.$fm->exbb_domain))
													$Link = $fm->out_redir.$Link;
													return "<a href=\"{$Link}\" target=\"_blank\">".trim($LinkText)."</a> ";'), $string);

		$string = preg_replace_callback("#\[url=\s*(" . $patern . ")\s*\](.*?)\[\/url\]#isx", array( $this, 'url_text' ), $string);

		$string = preg_replace_callback("#(^|\s|\b)(" . $patern . ")(\[|\s|$)#xis", create_function('$matches', 'if (!' . $this->user['id'] . ') return \'<i>' . $this->LANG['ViewLinkReged'] . '</i> \'; global $fm; $LinkText = preg_replace("#^(.{32})(.+)(.{16})#is","$1...$3",trim($matches[2]));
													$Link = (empty($matches[3])) ? "http://".$matches[2]:$matches[2];
													if ($fm->exbb[\'redirect\'] && !stristr($Link, \'http://www.\'.$fm->exbb_domain) && !stristr($Link, \'http://\'.$fm->exbb_domain))
													$Link = $fm->out_redir.$Link;
													return $matches[1]."<a href=\"$Link\" target=\"_blank\">$LinkText</a>".$matches[4];'), $string);

		while (preg_match("#\[(q|quote)(|=([^\[\]]+?))\](?!.*\[\\1(|=([^\[\]]+?))\])(.+?)\[/\\1\]#is", $string, $matches)) {
			$title = ( $matches[3] !== '' ) ? $matches[3] . ' пишет:' : 'Цитата:';
			$string = str_replace($matches[0], "<div class=\"quotetop\">&nbsp;<b>{$title}</b></div><div class=\"quotemain\">{$matches[6]}</div>", $string);
		}

		$string = $this->chunk_split($string);

		$string = preg_replace_callback("#\[search\](.+?)\[\/search\]#is", "search_link", $string);

		if ($findstring !== '') {
			$string = preg_replace("#(" . $findstring . ")(?![^<]*?>)#mi", "<span style=\"background-color:red;\">$1</span>", $string);
		}

		if (count($array) != 0) {
			$array = array_reverse($array);
			$string = str_replace(array_keys($array), $array, $string);
		}

		return nl2br($string);
	}

	/**
	 * @param $string
	 * @param int $num
	 * @param string $delim
	 *
	 * @return mixed
	 */
	function chunk_split($string, $num = 128, $delim = "\040") {
		$string = preg_replace_callback("#(^|\s)([АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюяA-Za-z0-9\/\.\=\-_]{" . $num . ",})(\s|$)#s", create_function('$matches', 'return $matches[1].trim(chunk_split($matches[2],' . $num . ',"' . $delim . '")).$matches[3];'), $string);

		return $string;
	}

	/**
	 * @param $string
	 * @param int $replace
	 *
	 * @return bool|mixed
	 */
	function bads_filter($string, $replace = 1) {
		$badwords = file(EXBB_DATA_BADWORDS);
		unset( $badwords[0] );
		if (count($badwords)) {
			$bad = array();
			$good = array();
			foreach ($badwords as $words) {
				list( $bw, $gw ) = explode('=', $words);
				$bad[] = '/(^|\b)' . trim($bw) . '(\b|!|\?|\.|,|$)/i';
				$good[] = trim($gw);
			}
			if (sizeof($bad)) {
				$cleared = preg_replace($bad, $good, $string);
			}
		}
		else {
			$cleared = $string;
		}
		if ($replace === 1) {
			return $cleared;
		}
		if ($string != $cleared) {
			return true;
		} //есть плохие слова
	}

	/********************************************************************************
	 *                                                                                *
	 *                    E-mail Функции                                                *
	 *                                                                                *
	 ********************************************************************************/

	/*
		_Mail Основная функция которая определяет как отправлять письма
	*/
	/**
	 *
	 */
	function _Mail() {

		$args = func_get_args();
		if ($this->exbb['mailer']) {
			include( 'modules/mailer/_mail.php' );
		}
		else {
			$this->_SendMail($args);
		}

	}

	/*
		_SendMail функция отправки e-mail через sendmail сервера
	*/
	/**
	 * @param $list
	 */
	function _SendMail($list) {
		$headers = 'From: =?windows-1251?B?' . base64_encode($list[0]) . '?= <' . $list[1] . ">\n";
		$headers .= 'Reply-To: ' . $list[1] . "\n";
		$headers .= 'Return-Path: ' . $list[1] . "\n";
		$headers .= "MIME-Version: 1.0\nContent-type: text/plain; charset=windows-1251\nContent-Transfer-Encoding: 8bit\nDate: " . gmdate('D, d M Y H:i:s', time()) . " UT\nX-Priority: 3\nX-Mailer: PHP\n";
		$list[3] = '=?windows-1251?B?' . base64_encode($list[3]) . '?=';
		$skip_mails = ( file_exists(EXBB_DATA_SKIP_MAILS) ) ? file(EXBB_DATA_SKIP_MAILS) : array();
		if (count($skip_mails) !== 0) {
			unset( $skip_mails[0] );
			$skip_mails = preg_replace("#(\r\n|\|$)#", "", trim(implode("|", $skip_mails)));
		}
		else {
			$skip_mails = "@";
		}

		$users = $this->_Read(EXBB_DATA_USERS_LIST);
		if (is_array($list[2])) {
			@set_time_limit(360);
			foreach ($list[2] as $user_id => $flag) {
				if (isset( $users[$user_id] ) && !preg_match("#(" . $skip_mails . ")$#is", $users[$user_id]['m'])) {
					mail($users[$user_id]['m'], $list[3], $list[4], $headers);
				}
			}
		}
		else {
			if (!preg_match("#(" . $skip_mails . ")$#is", $list[2])) {
				mail($list[2], $list[3], $list[4], $headers);
			}
		}
	}

	/********************************************************************************
	 *                                                                                *
	 *                    UPLOAD Функции                                                *
	 *                                                                                *
	 ********************************************************************************/

	/*                                <img src="" width="" height="" alt="" border="0">
		Объект аплоада
	*/
	/**
	 * @var bool
	 */
	public $UP = false;
	/**
	 * @var int
	 */
	public $MAX_SIZE = 0;

	/*
		Upload Основная функция определяющая загрузку файлов
	*/
	/**
	 * @param $maxsize
	 * @param $storagename
	 * @param $destdir
	 * @param string $mode
	 *
	 * @return array|bool
	 */
	function Upload($maxsize, $storagename, $destdir, $mode = 'image') {
		include( 'upload.class.php' );
		$this->UP = new UPLOAD($maxsize);

		if (defined("UP_NOADDED")) {
			return false;
		}

		if (defined("UP_ERROR")) {
			return true;
		}

		switch ($mode) {
			case 'avatar':
				$attach = $this->UP->DoUpload('image', $destdir, $storagename, $this->exbb['avatar_max_width'], $this->exbb['avatar_max_height']);
				if ($attach !== false && preg_match("#personal/#is", $this->user['avatar']) && $this->user['avatar'] !== $attach['STORAGE'] && file_exists($destdir . $this->user['avatar'])) {
					unlink($destdir . $this->user['avatar']);
				}

			break;
			case 'file':
			case 'image':
				if ($this->exbb['file_type'] !== '.*') {
					$this->UP->_REQUEST_TYPE = str_replace(",", "|", $this->exbb['file_type']);
				}
				// TAR FLAG
				$this->UP->_TARFILE = true;
				$attach = $this->UP->DoUpload('file', $destdir, $storagename, $this->exbb['image_max_width'], $this->exbb['image_max_height']);
			break;
		}
		if ($attach !== false) {
			@chmod($destdir . $attach['STORAGE'], $this->exbb['ch_upfiles']);

			return $attach;
		}
	}
}

$fm = new FM();
?>