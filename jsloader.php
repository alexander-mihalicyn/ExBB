<?php
/****************************************************************************
 * ExBB v.1.9                                                                *
 * Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock                *
 *                                                                            *
 * http://www.exbb.revansh.com                                                *
 * email: admin@exbb.revansh.com                                            *
 *                                                                            *
 ****************************************************************************/
/****************************************************************************
 *                                                                            *
 *   This program is free software; you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation; either version 2 of the License, or        *
 *   (at your option) any later version.                                    *
 *                                                                            *
 ****************************************************************************/
define('NO_GZIP', true);
define('IN_EXBB', true);
define('IS_REGISTER', true);
include( './include/common.php' );

// Load JsHttpRequest backend.
require_once "include/JsHttpRequest/JsHttpRequest.php";

// Create main library object. You MUST specify page encoding!
$JsHttpRequest = new JsHttpRequest($fm->LANG['ENCODING']);

$fm->_GetVars();
$fm->_String('loader');
$fm->_String('action');

$_RESULT = array( "error" => 1 );

if ($fm->input['loader'] === 'karma') {
	include( 'modules/karma/karma.php' );
}
elseif ($fm->input['loader'] === 'threadstop') {
	include( 'modules/threadstop/threadstop.php' );
}
elseif ($fm->input['loader'] === 'chat') {
	require_once( 'modules/chat/backend.php' );
}
else {
	if ($fm->input['loader'] == 'verify') {
		include( 'include/JsHttpRequest/verify_forms.php' );
	}
	elseif ($fm->input['loader'] === 'newmail') {
		if ($fm->exbb['pm'] === false && !defined('IS_ADMIN')) {
			echo $fm->LANG['PMClosed'];
		}

		if ($fm->user['id'] === 0) {
			echo $fm->LANG['UserUnreg'];
		}

		if ($fm->user['new_pm'] === true) {
			$user = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $fm->user['id'] . '.php', false);
			$user['new_pm'] = $fm->user['new_pm'] = false;
			$fm->_Write($fp_user, $user);
			unset( $user );
		}
		$_RESULT["error"] = 0;
		die();
	}
	elseif ($fm->input['loader'] === 'preview') {
		if ($fm->input['action'] === 'news' && defined('IS_ADMIN')) {
			$_RESULT["error"] = 0;
			echo $fm->formatpost($fm->input['text'], $fm->_Boolean($fm->input, 'html'));
		}
		elseif ($fm->input['action'] === 'newtopic') {
			$_RESULT["error"] = 0;
			echo $fm->formatpost($fm->input['text'], $fm->_Boolean($fm->input, 'html'));
		}
		else {
			echo $fm->LANG['CorrectPost'];
		}
		die();
	}
	else {
		echo $fm->LANG['ModNotInstalled'];
	}
}