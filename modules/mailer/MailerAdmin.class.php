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

require('Mailer.class.php');

class MailerAdmin extends Mailer {
	function getWSentThrough() {
		global $fm;
		
		$wSentThrough = array(0, 0);
		
		$list = $fm->_Read2Write($fpList, FM_MAILER_LIST_FILE);
		foreach ($list as $id => $info) {
			$count = isset($info[2]) ? $info[2] - (isset($info[3]) ? $info[3] : 0) : 1;
			switch ($info[0]) {
				case FM_MAILER_ACCOUNT_PRIORITY:
				case FM_MAILER_PERSON_PRIORITY:
					$wSentThrough[0]	+= $count;
					$wSentThrough[1]	+= $count;
				break;
				
				case FM_MAILER_SUBSCRIBERS_PRIORITY:
				case FM_MAILER_MASS_PRIORITY:
					$wSentThrough[0]	+= $count;
				break;
			}
		}
		$fm->_Fclose($fpList);
		if (!$list) {
			unlink(FM_MAILER_LIST_FILE);
		}
		
		return $wSentThrough;
	}
}

?>