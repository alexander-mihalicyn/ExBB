<?php

/*
	Ads Mod for ExBB FM 1.0 RC2
	Copyright (c) 2004 - 2011 by Yuri Antonov aka yura3d
	Copyright (c) 2009 - 2011 by ExBB Group
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) {
	die;
}

define('FM_ADS_DIR', 'modules/ads/');
define('FM_ADS_DATA_DIR', FM_ADS_DIR . 'data/');
define('FM_ADS_BLOCK_FILE', FM_ADS_DATA_DIR . 'block.php');

class Ads {
	var $config = array();
	var $status = false;

	function Ads() {
		$this->getConfig();
	}

	function getConfig() {
		global $fm;

		$this->config = $fm->_Read(FM_ADS_BLOCK_FILE);
	}

	function setupStatus() {
		global $fm;

		if (!$fm->user['id'] || $fm->user['status'] == 'me' && !$this->config['onlyForGuests'] && $fm->user['posts'] < $this->config['needPosts'] || ( defined('IS_ADMIN') || $fm->user['status'] == 'sm' ) && $this->config['adminsSupmoders']) {

			$this->status = true;
		}
	}

	function paste($postPerPage, $lastPost = false) {
		global $fm, $key, $topic_data;

		if (!$this->status || $postPerPage != $this->config['afterPost'] && !$lastPost || $postPerPage > $this->config['afterPost'] && $lastPost) {
			return;
		}

		$sourceCode = $this->config['sourceCode'];

		include( 'templates/' . DEF_SKIN . '/modules/ads/topic_data.tpl' );
	}
}

?>