<?php
if (!preg_match('#^(http|https|ftp)%3A%2F%2([' . chr(33) . '-' . chr(127) . ']+)$#is', urlencode($_SERVER['QUERY_STRING']))) {
	die;
}

header('Location: ' . strtr(urldecode($_SERVER['QUERY_STRING']), array( ' ' => '+' )));