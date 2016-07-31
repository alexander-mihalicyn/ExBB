<?php
use ExBB\Request;

define('IN_EXBB', true);

define('EXBB_ROOT', dirname(__DIR__));
define('EXBB_BASE', EXBB_ROOT);

define('EXBB_INSTALLATION_ROOT', __DIR__);

require EXBB_ROOT.'/include/paths.php';
require EXBB_ROOT.'/include/lib.php';
require EXBB_ROOT.'/include/page_header.php';

require __DIR__.'/language/russian/lang.php';

function lang($string, $data=null) {
	global $lang;

	return (is_array($data)) ? vsprintf($lang[$string], $data) : $lang[$string];
}

$request = new Request();

$actionsList = array(
	'check',
	'forumSettings',
	'adminAccountSettings',
	'finish',
);

$isActionValid = !empty($request->query['action']) && in_array($request->query['action'], $actionsList);
$action = ($isActionValid) ? $request->query['action'] : 'index';
$actionName = 'Action'.ucfirst($action);

require __DIR__.'/BaseController.php';
require __DIR__.'/models/BaseModel.php';
require __DIR__.'/Controller.php';

$controller = new Controller;
echo $controller->$actionName();

if (ob_get_level()) ob_end_flush();