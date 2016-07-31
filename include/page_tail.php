<?php
/****************************************************************************
* ExBB v.1.1                                                              	*
* Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock            	*
*                                                                         	*
* http://www.exbb.net                                             			*
* email: admin@exbb.net                                           			*
*                                                                         	*
****************************************************************************/
/****************************************************************************
*                                                                         	*
*   This program is free software; you can redistribute it and/or modify  	*
*   it under the terms of the GNU General Public License as published by  	*
*   the Free Software Foundation; either version 2 of the License, or     	*
*   (at your option) any later version.                                   	*
*                                                                         	*
****************************************************************************/
if (!defined('IN_EXBB')) die('Hack attempt!');

$GLOBALS['fm']->_FcloseAll();
$totaltime = $GLOBALS['fm']->_TotalTime();

$_GZIP_STATUS = ($GLOBALS['fm']->_PageGziped === TRUE) ? 'Gzipped':'Gzip Disabled';
?>

<center>
	<font color="#990000" size="1">
    	  <!-- [Script Execution time: <?php echo $totaltime; ?>] [ <?php echo $_GZIP_STATUS; ?> ] -->
    </font>
</center>
</body>
</html>

<?php if (ob_get_level()) ob_end_flush(); ?>