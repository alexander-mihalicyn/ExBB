<?php
echo <<<DATA
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{$GLOBALS['fm']->exbb['boardname']}{$GLOBALS['fm']->_Title}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$GLOBALS['fm']->LANG['ENCODING']}">
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Cache-Control" content="private">
<meta name="description" content="ExBB Full Mods {$GLOBALS['fm']->exbb['version']} ‘орум на PHP">
<meta http-equiv="description" content="{$GLOBALS['fm']->exbb['description']}">
<meta name="Keywords" content="{$GLOBALS['fm']->exbb['keywords']},{$GLOBALS['fm']->_Keywords}" />
<meta name="Resource-type" content="document">
<meta name="document-state" content="dynamic">
<meta name="Robots" content="index,follow">
<link rel="alternate" type="application/rss+xml" title="{$GLOBALS['fm']->exbb['boardname']}" href="./tools.php?action=rss">
<link rel="stylesheet" href="./templates/InvisionExBB/style.css" type="text/css">
<script type="text/javascript" language="JavaScript" src="javascript/JsHttpRequest/JsHttpRequest.js"></script>{$GLOBALS['fm']->_Link}
<script type="text/javascript" language="JavaScript" src="javascript/jquery.min.js"></script>
<script type="text/javascript" language="JavaScript" src="javascript/button.js"></script>
</head>
<body{$GLOBALS['fm']->_Body}>
<a name="top"></a>
<table id="ipbwrapper" cellspacing="0" cellpadding="0" border="0" height="95%">
	<tr>
		<td valign="top">
DATA;
?>
