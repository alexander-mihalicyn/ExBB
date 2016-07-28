<?php

/*
	Ads Mod for ExBB FM 1.0 RC2
	Copyright (c) 2004 - 2011 by Yuri Antonov aka yura3d
	Copyright (c) 2009 - 2011 by ExBB Group
	http://www.exbb.org/
	ICQ: 313321962
*/

require_once('Ads.php');

if (!defined('IN_EXBB')) {
	die;
}

class AdsAdmin extends Ads {
	function changeConfig($onlyForGuests, $needPosts, $adminsSupmoders, $afterPost, $sourceCode) {
		global $fm;
		
		$block = array(
			'onlyForGuests'		=> $onlyForGuests,
			'needPosts'			=> $needPosts,
			'adminsSupmoders'	=> $adminsSupmoders,
			'afterPost'			=> $afterPost,
			'sourceCode'		=> $sourceCode
		);
		
		$fm->_Read2Write($fpBlock, EXBB_MODULE_ADS_DATA_CONFIG);
		$fm->_Write($fpBlock, $block);
	}
}

?>