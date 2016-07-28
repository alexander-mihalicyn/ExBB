<?php

/*
	Mailer Mod for ExBB FM 1.0 RC1.01
	Copyright (c) 2005 - 2012 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

define('IN_EXBB', true);

define('FM_PATH',			dirname(dirname(dirname(__FILE__))) . '/');
define('FM_BOARDINFO',		'data/boardinfo.php');

// ”прощЄнное €дро ExBB
class FM {
	var $exbb	= array();
	var $_fps	= array();
	
	function __construct() {
		require_once(FM_PATH . FM_BOARDINFO);
		
		$this->_Nowtime = time();
		
		include_once(FM_PATH . "language/{$this->exbb['default_lang']}/lang_front_all.php");
	}
	
	function _Read($file) {
		if (!file_exists($file)) {
			return array();
		}
		
		$fp = @fopen($file, 'r') or die("Could not read from the file <b>{$file}</b>");
		flock($fp, LOCK_SH);
		
		fseek($fp, 8);
		$size = filesize($file);
		$str = fread($fp, $size ? $size - 8 : 1);
		
		flock($fp, LOCK_UN);
		fclose($fp);
		
		return ($str !== '') ? unserialize($str) : array();
	}
	
	function _Read2Write(&$fp, $file) {
		if (!file_exists($file)) {
			@fclose(@fopen($file, 'a+'));
			@chmod($file, $this->exbb['ch_files']);
		}
		
		$fp = @fopen($file, 'a+') or die("Could not write in the file <b>{$file}</b>");
		flock($fp, LOCK_EX);
		
		fseek($fp, 8);
		$size = filesize($file);
		$str = fread($fp, $size ? $size - 8 : 1);
		$this->_fps[$fp] = $fp;
		
		return ($str !== '') ? unserialize($str) : array();
	}
	
	function _Write(&$fp, $data) {
		ftruncate($fp, 0);
		fwrite($fp, '<?die;?>' . serialize($data));
		fflush($fp);
		
		$this->_Fclose($fp);
		
		return true;
	}
	
	function _Fclose($fp) {
		flock($fp, LOCK_UN);
		fclose($fp);
		unset($this->_fps[$fp]);
		
		return true;
	}
}

$fm = new FM;

if ($fm->exbb['mailer']) {
	require_once(FM_PATH . 'modules/mailer/Mailer.class.php');
	$mailer = new Mailer;
	$mailer->send();
	unset($mailer);
}