<?php
/****************************************************************************
 * ExBB v.1.9                                                                *
 * Copyright (c) 2002-20õõ by Alexander Subhankulov aka Warlock                *
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
define('IN_ADMIN', true);
define('IN_EXBB', true);

include( './include/common.php' );
$fm->_GetVars();
$fm->_String('module');
$fm->_LoadLang('setmodule', true);

if ($fm->input['module'] != '') {
	$fm->input['module'] = preg_replace("#[^A-Za-z0-9]#is", "", $fm->input['module']);
	$modulefile = 'modules/' . $fm->input['module'] . '/index.php';
	if (!file_exists($modulefile)) {
		$fm->_WriteLog($fm->LANG['ActionNotExists'], 1);
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['ModNotInstaled'], '', 1);
	}
	else {
		include( $modulefile );
	}
}
else {
	$fm->_WriteLog($fm->LANG['ActionNotExists'], 1);
	$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost'], '', 1);
}
include( 'page_tail.php' );
?>
