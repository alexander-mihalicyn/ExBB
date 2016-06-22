<?php
if (defined('IS_ADMIN')) {
	$adminadd = ' :: <a href="announcements.php?action=delall">'.$fm->LANG['DellAllNews'].'</a> :: <a href="announcements.php?action=add">'.$fm->LANG['AddNewNews'].'</a>';
}
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" /> <a href="index.php" title="{$fm->exbb['boardname']}">{$fm->exbb['boardname']}</a> &raquo; <a href="announcements.php" title="{$fm->LANG['Announ']}">{$fm->LANG['Announ']}</a> {$adminadd}
			</div>
			<br>
			{$newsbody}
DATA;
?>

