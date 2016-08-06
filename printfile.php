<?php
/****************************************************************************
 * ExBB v.1.1                                                                *
 * Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock                *
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
define('ATTACH', true);
define('IN_EXBB', true);

include( './include/common.php' );

$fm->_GetVars();
$fm->_String('action');

switch ($fm->input['action']) {
	case 'attach'   :
		attachment();
	break;
	case 'error'    :
		ImgError();
	break;
	case 'link'    :
		create_tmb($fm->_String('img'));
	break;
	default:
		mailtouser();
	break;
}

function ImgError() {
	header("Content-Type: image/png");
	header("Content-Encoding: none");
	header("Content-Transfer-Encoding: binary");
	header("Content-Disposition: inline; filename=\"error404.png\"");
	header("Content-Length: " . (string)( filesize("im/images/error404.png") ));
	readfile("im/images/error404.png");
	exit;
}

function attachment() {
	global $fm;

	$img = $fm->_Boolean($fm->input, 'img');
	if (( $forum_id = $fm->_Intval('f') ) === 0 || ( $topic_id = $fm->_Intval('t') ) === 0 || ( $attach_id = $fm->_Intval('id') ) === 0) {
		if ($img === true) {
			ImgError();
		}
		else {
			$fm->_Message($fm->LANG['MainMsg'], "aaa" . $fm->LANG['CorrectPost']);
		}
	}

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (!isset( $allforums[$forum_id] )) {
		if ($img === true) {
			ImgError();
		}
		else {
			$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
		}
	}

	if ($allforums[$forum_id]['private'] === true && !defined('IS_ADMIN')) {
		if ($fm->user['id'] === 0) {
			if ($img === true) {
				ImgError();
			}
			else {
				$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['UserUnreg'], 'loginout.php');
			}
		}
		$userprivate = ( isset( $fm->user['private'][$forum_id] ) && $fm->user['private'][$forum_id] === true ) ? true : false;
		if ($userprivate === false) {
			if ($img === true) {
				ImgError();
			}
			else {
				$fm->_Message($fm->LANG['PrivatForum'], $fm->LANG['PrivatRule']);
			}
		}
	}
	unset( $allforums );

	if (!file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php')) {
		if ($img === true) {
			ImgError();
		}
		else {
			$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
		}
	}

	$attaches = $fm->_Read2Write($fp_attach, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
	if (count($attaches) === 0) {
		$fm->_Fclose($fp_attach);
		unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
		if ($img === true) {
			ImgError();
		}
		else {
			$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
		}
	}

	if (!isset( $attaches[$attach_id]['id'] ) || !file_exists('uploads/' . $attaches[$attach_id]['id'])) {
		$fm->_Fclose($fp_attach);
		if ($img === true) {
			ImgError();
		}
		else {
			$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
		}
	}

	// Получаем номер байта, начиная с которого будем отдавать файл пользователю
	$range = (int)( ( isset( $_SERVER['HTTP_RANGE'] ) ) ? strtr($_SERVER['HTTP_RANGE'], array( 'bytes=' => '', '-' => '' )) : 0 );

	if (!$range) {
		$attaches[$attach_id]['hits']++;
	}
	$fm->_Write($fp_attach, $attaches);

	if ($fm->_Boolean($fm->input, 'icon') === true) {                   // application/x-gzip
		create_tmb('uploads/' . $attaches[$attach_id]['id']);
	}
	else {
		$type = 'unknown/unknown';
		switch ($attaches[$attach_id]['type']) {
			case 'gz'    :
				$attaches[$attach_id]['file'] = preg_replace("#\.[A-Za-z0-9]{1,5}$#is", ".tar.gz", $attaches[$attach_id]['file']);
				$type = 'application/x-gzip';
			break;
			case 'tar'    :
				$attaches[$attach_id]['file'] = preg_replace("#\.[A-Za-z0-9]{1,5}$#is", ".tar", $attaches[$attach_id]['file']);
				$type = 'application/x-tar';
			break;
			default        :
				$extension = mb_strtolower(mb_substr(mb_strrchr($attaches[$attach_id]['file'], '.'), 1));

				switch ($extension) {
					case 'gif'    :
						$type = 'image/gif';
					break;
					case 'jpg'    :
					case 'jpeg' :
						$type = 'image/pjpeg';
					break;
					case 'pdf'    :
						$type = 'application/pdf';
					break;
					case 'zip'    :
						$type = 'application/zip';
					break;
					case 'pdf'    :
						$type = 'application/pdf';
					break;
					default    :
						$type = 'unknown/unknown';
					break;
				};
			break;
		}

		// Ответ сервера в зависимости от нового скачивания либо докачки
		if ($range > 0) {
			header("HTTP/1.1 206 Partial Content");
		}
		else {
			header("HTTP/1.1 200 OK");
		}

		// Преобразование заголовка файла в зависимости от браузера
		if (mb_stristr(@$_SERVER['HTTP_USER_AGENT'], 'Opera')) {
			$attaches[$attach_id]['file'] = iconv('cp1251', 'utf-8', $attaches[$attach_id]['file']);
		}

		// Content-Encoding всегда none для подавления любых видов сжатия на уровне сервера, прокси и т п.
		header("Content-Type: " . $type);
		header("Content-Encoding: none");
		header("Content-Transfer-Encoding: binary");
		header("Content-Disposition: " . ( ( $img === true ) ? "inline" : "attachment" ) . "; filename=\"" . $attaches[$attach_id]['file'] . "\"");

		$file = @fopen('uploads/' . $attaches[$attach_id]['id'], 'rb');
		flock($file, 1);

		// Заголовок длины отдаваемой части и заголовок для поддержки докачки файла
		header("Accept-Ranges: bytes");
		header("Content-Length: " . (string)( $attaches[$attach_id]['size'] - $range ));
		header("Content-Range: " . (string)$range . "-" . (string)( $attaches[$attach_id]['size'] - 1 ) . "/" . (string)$attaches[$attach_id]['size']);

		// Отдаём содержимое файла начиная от запрашиваемого байта
		if ($range > 0) {
			fseek($file, $range);
		}
		fpassthru($file);
		fclose($file);
	}
	exit;
}

function create_tmb($bigimgsrc) {
	$rgb = 0xFFFFFF;
	$quality = 100;
	$width = 150;
	$bigimgsrc = strtr($bigimgsrc, array( ' ' => '%20' ));

	if ($size = @getimagesize($bigimgsrc)) {
		if ($size === false) {
			ImgError();

			return false;
		}

		$format = mb_strtolower(mb_substr($size['mime'], mb_strpos($size['mime'], '/') + 1));
		$icfunc = "imagecreatefrom" . $format;
		if (!function_exists($icfunc)) {
			ImgError();

			return false;
		}
		$x_ratio = $size[0] / $width;
		$height = floor($size[1] / $x_ratio);
		header("Content-type: image/jpg");
		header("Content-Encoding: none");
		header("Content-Transfer-Encoding: binary");
		$bigimg = $icfunc($bigimgsrc);
		$trumbalis = imagecreatetruecolor($width, $height);
		imagefill($trumbalis, 0, 0, $rgb);
		imagecopyresampled($trumbalis, $bigimg, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		imagejpeg($trumbalis);
		flush();
		imagedestroy($bigimg);
		imagedestroy($trumbalis);
	}
	else {
		ImgError();
	}

	return;
}

?>
