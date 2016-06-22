<?php

/*
	PHP 5 to 4 functions runtime library for ExBB FM 1.0 RC2
	Copyright (c) 2004 - 2011 by Yuri Antonov aka yura3d
	Copyright (c) 2009 - 2011 by ExBB Group
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) {
	die;
}

if (!function_exists('array_intersect_key')) {
	function array_intersect_key($isec, $arr2) {
		$argc = func_num_args();
		
		for ($i = 1; !empty($isec) && $i < $argc; $i++) {
			$arr = func_get_arg($i);
			
			foreach ($isec as $k => $v) {
				if (!isset($arr[$k])) {
					unset($isec[$k]);
				}
			}
		}
		
		return $isec;
	}
}

if (!function_exists('array_diff_key')) {
	function array_diff_key() {
		$arrs = func_get_args();
		$result = array_shift($arrs);
		foreach ($arrs as $array) {
			foreach ($result as $key => $v) {
				if (array_key_exists($key, $array)) {
					unset($result[$key]);
				}
			}
		}
		
		return $result;
	}
}

if(!function_exists('json_decode')) {
	include_once('JSON.php');
	
	function json_decode($data) {
		$json = new Services_JSON();
		return($json->decode($data));
	}
}

?>