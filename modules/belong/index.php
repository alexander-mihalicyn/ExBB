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

require('belong.php');

$fm->_LoadModuleLang('belong', 1);

if ($fm->_POST !== true) {
    switch ($fm->_String('execute')) {
        case 'index':
            index();
            return;
    }
    
    $belong = new Belong;
    
    $config = $belong->getConfig();
    
    $viewTopicsYes  = ($config['viewTopics']) ? 'checked="checked" ' : '';
    $viewTopicsNo   = (!$config['viewTopics']) ? 'checked="checked" ' : '';
    
    $viewPostsYes   = ($config['viewPosts']) ? 'checked="checked" ' : '';
    $viewPostsNo    = (!$config['viewPosts']) ? 'checked="checked" ' : '';
    
    include('admin/all_header.tpl');
	include('admin/nav_bar.tpl');
	include('modules/belong/admintemplates/index.tpl');
	include('admin/footer.tpl');
}
else {
    if ($fm->_Intval('membersPerDb') == 0 || abs($fm->input['membersPerDb']) > 999) {
        $fm->input['membersPerDb'] = 1;
    }
    
    $belong = new Belong;
    
    $belong->setConfig(array(
        'membersPerDb'  => abs($fm->input['membersPerDb']),
        'viewTopics'    => $fm->_Boolean1('viewTopics'),
        'viewPosts'     => $fm->_Boolean1('viewPosts')
	));
    
    $belong->saveConfig();
    
    $fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=belong', 1);
}

function index() {
    global $fm;
    
    $belong = new Belong;
    $belong->index();
}