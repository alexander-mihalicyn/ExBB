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

if ($fm->exbb['belong']) {
    include_once('belong.php');
    
    $belong = new Belong;
    $belong->moveTopic($forum_id, $topic_id, $toforum_id, $newtopic_id, $autors);
    unset($belong);
}

?>