<?php

/*
	Mailer Mod for ExBB FM 1.0 RC1.01
	Copyright (c) 2005 - 2012 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) {
	die;
}

define('FM_MAILER_DATA_DIR', 				FM_PATH . 'modules/mailer/data/');		// Путь к папке данных модуля
define('FM_MAILER_LOCK_FILE',				FM_MAILER_DATA_DIR . 'lock.php');		// Блокиратор доступа
define('FM_MAILER_CONFIG_FILE',				FM_MAILER_DATA_DIR . 'config.php');		// Конфигурация
define('FM_MAILER_LIST_FILE',				FM_MAILER_DATA_DIR . 'list.php');		// Список писем в очереди
define('FM_MAILER_MAIL_FORMAT',				FM_MAILER_DATA_DIR . '%d.php');			// Письмо
define('FM_MAILER_ACCOUNT_PRIORITY',		1);										// Приоритет учётных писем
define('FM_MAILER_PERSON_PRIORITY',			2);										// Приоритет персональных писем
define('FM_MAILER_SUBSCRIBERS_PRIORITY',	3);										// Приоритет подписок
define('FM_MAILER_MASS_PRIORITY',			4);										// Приоритет массовой рассылки

class Mailer {
	var $config		= array();
	var $_fpLock	= null;
	var $_fpConfig	= null;
	
	function getConfig() {
		global $fm;
		
		$fm->_Read2Write($this->_fpLock, FM_MAILER_LOCK_FILE);
		
		$this->config = $fm->_Read2Write($this->_fpConfig, FM_MAILER_CONFIG_FILE);
		
		return $this->config;
	}
	
	function saveConfig($config = null) {
		global $fm;
		
		$fm->_Write($this->_fpConfig, $config !== null ? $config : $this->config);
		
		$fm->_Fclose($this->_fpLock);
		unlink(FM_MAILER_LOCK_FILE);
		
		return true;
	}
	
	function closeConfig() {
		global $fm;
		
		$fm->_Fclose($this->_fpConfig);
		
		$fm->_Fclose($this->_fpLock);
		unlink(FM_MAILER_LOCK_FILE);
		
		return true;
	}
	
	function toAccountQueue($from, $email, $emails, $subject, $text) {
		return $this->_toQueue(func_get_args(), FM_MAILER_ACCOUNT_PRIORITY);
	}
	
	function toPersonQueue($from, $email, $emails, $subject, $text) {
		return $this->_toQueue(func_get_args(), FM_MAILER_PERSON_PRIORITY);
	}
	
	function toSubscribersQueue($from, $email, $emails, $subject, $text) {
		return $this->_toQueue(func_get_args(), FM_MAILER_SUBSCRIBERS_PRIORITY);
	}
	
	function toMassQueue($from, $email, $emails, $subject, $text) {
		return $this->_toQueue(func_get_args(), FM_MAILER_MASS_PRIORITY);
	}
	
	function _toQueue($args, $priority) {
		global $fm;
		
		// Config start
		$config = $this->getConfig();
		$id = $config['id'] = isset($config['id']) ? $config['id'] : 1;
		
		// List start
		$list = $fm->_Read2Write($fpList, FM_MAILER_LIST_FILE);
		$list[$id] = array($priority, true);
		if (is_array($args[2])) {
			$list[$id][2] = count($args[2]);
		}
		
		// Mail
		$fm->_Read2Write($fpMail, sprintf(FM_MAILER_MAIL_FORMAT, $id));
		$fm->_Write($fpMail, array($args[0], $args[1], $args[2], $args[3], $args[4]));
		
		// List end
		ksort($list);
		uasort($list, create_function('$a, $b', 'if ($a[0] == $b[0]) return 0; return $a[0] < $b[0] ? -1 : 1;'));
		$fm->_Write($fpList, $list);
		
		// Config end
		$config['id']++;
		$this->saveConfig($config);
		
		return true;
	}
	
	function _makeHeaders($from, $email) {
		global $fm;
		
		$headers = "From: =?{$fm->LANG['ENCODING']}?B?" . base64_encode($from) . "?= <{$email}>\n" .
			"Reply-To: {$email}\n" .
			"Return-Path: {$email}\n" .
			"Content-Type: text/plain; charset={$fm->LANG['ENCODING']}\n" .
			"Content-Transfer-Encoding: 8bit\n" .
			"Date: " . gmdate('D, d M Y H:i:s', $fm->_Nowtime) . " UT\n" .
			"X-Priority: 3\n" .
			"X-MSMail-Priority: Normal\n" .
			"X-Mailer: ExBB FM {$fm->exbb['version']} Advanced Mailer by yura3d (http://www.exbb.org/)\n";
		
		return $headers;
	}
	
	function isCron() {
		return php_sapi_name() == 'cli';
	}
	
	function send() {
		global $fm;
		
		// Lock checking
		if (file_exists(FM_MAILER_LOCK_FILE) && !$this->isCron()) {
			return false;
		}
		
		// Config start
		$config = $this->getConfig();
		if (!$config['cron'] || $this->isCron()) {
			$config['last']		= isset($config['last']) ? $config['last'] : 0;
			$config['sent']		= isset($config['sent']) && $fm->_Nowtime - $config['last'] <= $config['period'] ? $config['sent'] : 0;
			$config['through']	= isset($config['through']) && $fm->_Nowtime - $config['last'] <= $config['period'] ? $config['through'] : 0;
			
			// Send
			if ($fm->_Nowtime - $config['last'] >= $config['period'] || $config['sent'] + $config['through'] < $config['messages']) {
				$process = 0;
				
				// List start
				$list = $fm->_Read2Write($fpList, FM_MAILER_LIST_FILE);
				
				$stop = false;
				foreach ($list as $id => $info) {
					if (!$info[1]) {
						continue;
					}
					
					// Mail
					$mail = $fm->_Read2Write($fpMail, sprintf(FM_MAILER_MAIL_FORMAT, $id));
					$headers = $this->_makeHeaders($mail[0], $mail[1]);
					$mail[2] = is_array($mail[2]) ? $mail[2] : array($mail[2]);
					if (reset($mail[2]) === 1) {
						$users = $fm->_Read(EXBB_DATA_USERS_LIST);
						$uids = array_keys($mail[2]);
						$mail[2] = array();
						foreach ($uids as $offset => $uid) {
							if (isset($users[$uid])) {
								$mail[2][] = $users[$uid]['m'];
							}
							unset($uids[$offset]);
						}
						unset($users);
					}
					foreach ($mail[2] as $offset => $email) {
						if ($config['sent'] + $config['through'] >= $config['messages'] && $config['through'] >= $config['reserved'] || $process >= $config['process']) {
							$stop = true;
							
							break;
						}
						if (in_array($info[0], array(FM_MAILER_SUBSCRIBERS_PRIORITY, FM_MAILER_MASS_PRIORITY))) {
							if ($config['sent'] + $config['through'] >= $config['messages']) {
								break;
							}
							else {
								$sent = &$config['sent'];
							}
						}
						if (in_array($info[0], array(FM_MAILER_ACCOUNT_PRIORITY, FM_MAILER_PERSON_PRIORITY))) {
							if ($config['through'] >= $config['reserved']) {
								break;
							}
							else {
								$sent = &$config['through'];
							}
						}
						
						mail($email, $mail[3], $mail[4], $headers);
						unset($mail[2][$offset]);
						
						if (isset($info[2])) {
							$list[$id][3] = isset($list[$id][3]) ? $list[$id][3] + 1 : 1;
						}
						
						$sent++;
						$process++;
					}
					if ($mail[2]) {
						$fm->_Write($fpMail, $mail);
					}
					else {
						$fm->_Fclose($fpMail);
						unlink(sprintf(FM_MAILER_MAIL_FORMAT, $id));
						
						unset($list[$id]);
					}
					
					if ($stop) {
						break;
					}
				}
				
				// List end
				if ($list) {
					$fm->_Write($fpList, $list);
				}
				else {
					$fm->_Fclose($fpList);
					unlink(FM_MAILER_LIST_FILE);
				}
				
				if ($process) {
					$config['last'] = $fm->_Nowtime;
				}
				
				// Config end #1
				$this->saveConfig($config);
			}
			else {
				// Config end #2
				$this->closeConfig();
			}
		}
		
		return true;
	}
}

?>