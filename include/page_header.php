<?php
/****************************************************************************
 * ExBB v.1.1                                                                *
 * Copyright (c) 2002-20õõ by Alexander Subhankulov aka Warlock                *
 *                                                                            *
 * http://www.exbb.net                                                        *
 * email: admin@exbb.net                                                    *
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
if (!defined('IN_EXBB')) {
	die( 'Hack attempt!' );
}

/* Start gzip headers */
if ($fm->exbb['gzip_compress'] && !defined('ATTACH') && !defined('NO_GZIP') && extension_loaded("zlib")) {
	ob_start("ob_gzhandler");
	$fm->_PageGziped = true;
}
else {
	ob_start();
}
ob_implicit_flush(0);
session_start();
define('_SESSION_ID', session_name() . '=' . session_id());