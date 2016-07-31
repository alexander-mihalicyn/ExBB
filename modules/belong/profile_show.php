<?php
/**
 * Belong Mod for ExBB FM 1.0 RC2
 * @copyright ExBB Group, http://www.exbb.org/
 *
 * @author Yuri Antonov, http://www.exbb.org/
 *
 * @version 1.0 $Id:$
 */

if (!defined('IN_EXBB')) {
    die;
}

$show_belong = '';

if ($fm->exbb['belong']) {
    $fm->_LoadModuleLang('belong');
    
    include('templates/' . DEF_SKIN . '/modules/belong/profile_show.tpl');
}