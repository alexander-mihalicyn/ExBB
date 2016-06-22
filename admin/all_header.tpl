<?php
echo <<<DATA
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{$GLOBALS['fm']->exbb['boardname']} :: {$GLOBALS['fm']->LANG['Administrating']}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$GLOBALS['fm']->LANG['ENCODING']}">
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Cache-Control" content="private">
<meta name="description" content="{$GLOBALS['fm']->exbb['description']}">
<meta http-equiv="description" content="{$GLOBALS['fm']->exbb['description']}">
<meta name="Resource-type" content="document">
<meta name="document-state" content="dynamic">
<meta name="Robots" content="index,follow">{$GLOBALS['fm']->_Link}
<link rel="stylesheet" href="./admin/style.css" type="text/css">
</head>
<body bgcolor="#E5E5E5" text="#000000" link="#006699" vlink="#5493B4" />
DATA;
?>
