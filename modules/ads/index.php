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

require( 'AdsAdmin.php' );

$fm->_LoadModuleLang('ads', true);

changeConfig();

function changeConfig() {
	global $fm;

	if (!$fm->_Boolean1('doSend')) {
		$adsAdmin = new AdsAdmin;

		$onlyForGuests_yes = ( $adsAdmin->config['onlyForGuests'] ) ? ' checked="checked"' : '';
		$onlyForGuests_no = ( !$adsAdmin->config['onlyForGuests'] ) ? ' checked="checked"' : '';

		$needPosts = $adsAdmin->config['needPosts'];

		$adminsSupmoders_yes = ( $adsAdmin->config['adminsSupmoders'] ) ? ' checked="checked"' : '';
		$adminsSupmoders_no = ( !$adsAdmin->config['adminsSupmoders'] ) ? ' checked="checked"' : '';

		$afterPost = $adsAdmin->config['afterPost'];

		$sourceCode = htmlspecialchars($adsAdmin->config['sourceCode']);

		unset( $adsAdmin );

		include( 'admin/all_header.tpl' );
		include( 'admin/nav_bar.tpl' );
		include( 'modules/ads/admintemplates/config.tpl' );
		include( 'admin/footer.tpl' );

		return;
	}

	$fm->_Boolean1('onlyForGuests');
	$fm->_Boolean1('adminsSupmoders');
	$fm->_Intvals(array( 'needPosts', 'afterPost' ));
	$fm->_String('sourceCode');

	$adsAdmin = new AdsAdmin;
	$adsAdmin->changeConfig($fm->input['onlyForGuests'], $fm->input['needPosts'], $fm->input['adminsSupmoders'], $fm->input['afterPost'], $fm->html_replace($fm->input['sourceCode']));
	unset( $adsAdmin );

	$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=ads', 1);
}

?>