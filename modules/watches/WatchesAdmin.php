<?php

/*
	Watches Mod for ExBB FM 1.0 RC2
	Copyright (c) 2004 - 2011 by Yuri Antonov aka yura3d
	Copyright (c) 2009 - 2011 by ExBB Group
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) {
	die;
}

require('Watches.php');

class WatchesAdmin extends Watches {
	function saveConfig($days) {
		global $fm;
		
		$config = array(
			'days'	=> $days
		);
		
		$fm->_Read2Write($fpConfig, EXBB_MODULE_WATCHES_DATA_CONFIG);
		$fm->_Write($fpConfig, $config);
		
		$this->loadConfig();
		
		return true;
	}
}