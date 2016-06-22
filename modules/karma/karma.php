<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

define('KARMA_MIN_POSTS',	50); 	//минимальное кол-во сообщений для возможности понижать или повышать карму
define('KARMA_TIME_OUT',	3600);	//Интервал времени, через который можно повторить действия в карме
define('KARMA_LOG',			FALSE);	//Писать историю кармы или нет. Чтобы вести истороию замените FALSE на TRUE

$fm->_LoadModuleLang('karma');

if(!empty($_SERVER['HTTP_REFERER']) && !preg_match("#^http://".$_SERVER['HTTP_HOST']."#is",$_SERVER['HTTP_REFERER'])){
	echo $fm->LANG['KarmaHacked'];
} elseif(empty($_SERVER['HTTP_REFERER'])){
		$_RESULT['text'] = $fm->LANG['KarmaHacked'];
} elseif ($fm->user['id'] === 0) {
		echo $fm->LANG['KarmaYouGuest'];
} elseif ($fm->user['posts'] <= KARMA_MIN_POSTS){
		echo $fm->LANG['KarmaPostMin'];
} elseif(($touser_id = $fm->_Intval('user')) === 0){
		echo $fm->LANG['KarmaNotGuest'];
} elseif($fm->_Checkuser($touser_id) === FALSE){
		echo $fm->LANG['KarmaNotDeleted'];
} elseif($fm->user['id'] === $touser_id){
		echo $fm->LANG['KarmaNotSelf'];
} elseif (isset($fm->user['lastkarma']) && (($fm->_Nowtime - $fm->user['lastkarma'])< KARMA_TIME_OUT)){
		echo $fm->LANG['KarmaWait'];
} else {
        $touser = $fm->_Read2Write($fp_touser,'members/'.$touser_id.'.php',FALSE);

        $touser['karma'] = (isset($touser['karma'])) ? $touser['karma']:0;
        switch($fm->input['action']) {
        	case 'plus': 	$touser['karma']++;
        					break;
        	case 'minus': 	$touser['karma']--;
        					break;
        }
        $fm->_Write($fp_touser,$touser);

        $user = $fm->_Read2Write($fp_user,'members/'.$fm->user['id'].'.php',FALSE);
        $user['lastkarma'] = $fm->_Nowtime;
        $fm->_Write($fp_user,$user);

        if (KARMA_LOG === TRUE) {
        	save_log($touser);
        }
		$_RESULT = array(
			"error"	=> 0,
			"karma" => $touser['karma'],
			"user" => $touser['id']
		);
		echo sprintf($fm->LANG['KarmaAdded'],$touser['karma']);

}
die();
/*
	Functions
*/
function save_log($touser){
		global $fm;
        $karma_log = 'modules/karma/data/karmalog.php';
        $action =($fm->input['action'] == 'plus') ? $fm->LANG['KarmaUp']:$fm->LANG['KarmaLow'];
        $logs = sprintf($fm->LANG['KarmaLog'],$fm->_Nowtime,$fm->user['name'],$action,$touser['name']);
        $fp = @fopen($karma_log,'a+');
        @flock($fp,LOCK_EX);
        @fwrite($fp,$logs);
        @fclose($fp);
}
?>

