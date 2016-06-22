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

$ads = null;
$postPerPage = 0;

if ($fm->exbb['ads']) {
	include('Ads.php');
	
	$ads = new Ads;
	$ads->setupStatus();
}

?>