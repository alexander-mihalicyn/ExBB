<?php
echo <<<DATA
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{$GLOBALS['fm']->exbb['boardname']}{$GLOBALS['fm']->_Title}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$GLOBALS['fm']->LANG['ENCODING']}">
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Cache-Control" content="private">
<meta name="description" content="ExBB Full Mods {$GLOBALS['fm']->exbb['version']} Форум на PHP">
<meta http-equiv="description" content="{$GLOBALS['fm']->exbb['description']}">
<meta name="Keywords" content="{$GLOBALS['fm']->exbb['keywords']},{$GLOBALS['fm']->_Keywords}" />
<meta name="Resource-type" content="document">
<meta name="document-state" content="dynamic">
<meta name="Robots" content="index,follow">

<style type="text/css">
<!--
BODY {font-family: Verdana, Arial, Helvetica, sans-serif;color: #000000;}
a {color: #444444;text-decoration: none;}
a:hover {color: #000000;text-decoration: underline;}
a.copyright {color: #444444;text-decoration: none;}
a.copyright:hover {color: #000000;text-decoration: underline;}
#pagepatch {width:95%;font-size: 14px;text-align: left;}
#pages {width:95%;font-size: 9pt;text-align: right;}
#pages div {float:left;}
#main {width:95%;border:solid 1px grey;padding: 5px;text-align: left;padding: 0px 0px 5px 0px;}
.blok {background-color: #EEF2F7;border:solid 1px grey;margin: 5px 5px 0px 5px;}
.blok div {padding: 4px;}
.text {background-color: #FFFFFF;font-size: 0.8em;text-align: justify;}
.text font {color: red;}
.title {font-size: 8pt;border-bottom: solid 1px grey}
.title span {font-size: 10pt;font-weight: bold;}
.copyright {font-size: 10px;font-family: Verdana, Arial, Helvetica, sans-serif;color: #444444;letter-spacing: -1px;}
.block {margin-left: 20px;}
.quote {background-color: #FAFCFE;border: 1px solid #000;padding: 4px;white-space:normal;font-size: 11px;color: #465584;width: 98%;}
.phpcode {width: 98%;background-color: #FAFCFE;border: 1px solid #000;padding: 4px;color: Teal;font: 15px "Courier New";}
.htmlcode {width: 98%;background-color: #FAFCFE;border: 1px solid #000;padding: 4px;color: #00008B;font: 15px "Courier New";}
.offtop {background-color: #E4EAF2;border: 1px solid #ffffff;padding: 4px;font-family: Comic Sans MS;}
.curentpage {color: darkblue;}
-->
</style>
</style>{$GLOBALS['fm']->_Link}
</head>
<body marginheight="8" marginwidth="8" topmargin="8" leftmargin="8" rightmargin="8">
<div align="center">
	<div id="pagepatch">
		<a href="{$fm->exbb['boardurl']}">{$fm->exbb['boardname']}</a> &raquo; <a href="{$fm->exbb['boardurl']}/index.php?c={$cat_id}">{$catname}</a> &raquo; <a href="{$fm->exbb['boardurl']}/forums.php?forum={$forum_id}">{$forumname}</a> &raquo; <b><a href="{$fm->exbb['boardurl']}/topic.php?forum={$forum_id}&topic={$topic_id}">{$topicname}</a></b>
	</div><br>
	<div id="pages">
		<div>{$pages}</div>{$founds}
	</div>
	<br />
	<div id="main">
		{$print_data}
	</div>
	<div align="center">
		{$GLOBALS['fm']->_Counters}<br />
		<span class="copyright">
			{$GLOBALS['fm']->LANG['Powered']} <a href="http://www.exbb.info/" title="Скрипт форума ExBB, группа разработчиков ExBB Group" target="_blank">ExBB FM 1.0 Final</a>
        </span><br /><br />
		<br />
	</div>
</div>
DATA;
?>
