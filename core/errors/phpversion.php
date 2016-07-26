<?php defined('IN_EXBB') or die; ?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Uncaught exception</title>

	<style>
		html, body {
			margin: 0;
			padding: 0;
		}

		html {
			font-family: sanf-serif;
			font-weight:  normal;
			font-size: 16px;
		}

		body {
			background-color: #98AAB1;
		}

		section, article, header {
			display: block;
			padding: 0;
			margin: 0;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
		}

		section {
			background-color: #98AAB1;
		}

		section:after {
			display: table;
			clear: both;
			content: "";
		}

		section > header {
			background-color: #535552;
			color: #FFFFFF;
			font-size: 1.6em;
			padding: 15px 20px;
		}

		section > header > h1 {
			padding: 0;
			margin: 0;
		}

		section > article {
			background-color: #FFFFFF;
			margin: 20px 20px;
			padding: 10px 20px;
		}

		section > article > h2 {
			padding: 0;
			margin: 0;
			font-size: 1.7em;
			color: #990000;
		}

		section > article > p {
			font-size: 1.2em;
		}
	</style>
</head>
<body>
<section>
	<header>
		<h1>Серверная ошибка</h1>
	</header>

	<article>
		<h2>Минимальная требуемая версия PHP для установки форума - <?php echo REQUIRED_PHP_VERSION; ?></h2>

		<p>Ваша версия PHP - <?php echo PHP_VERSION; ?></p>
		<p>
			Возможные варианты решения проблемы:<br>
			<ul>
				<li>Обратиться в поддержку хостинга с просьбой установить более новую версию PHP</li>
				<li>Сменить версию PHP в панели управления хостингом самостоятельно</li>
			</ul>
		</p>
	</article>
</section>
</body>

</html>