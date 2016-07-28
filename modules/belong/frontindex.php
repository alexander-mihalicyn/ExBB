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

$fm->_LoadModuleLang('belong');

switch ($fm->_String('what')) {
    case 'topics':
        viewTopics();
        break;
    default:
        viewPosts();
        break;
}

function applyModuleSkin() {
    global $fm;
    
    $skins = array(DEF_SKIN, $fm->exbb['default_style'], 'InvisionExBB');
    
    foreach ($skins as $skin) {
        if (file_exists("templates/{$skin}/modules/belong/")) {
            define('MODULE_SKIN', $skin);
            
            return true;
        }
    }
    
    $fm->_Message($fm->LANG['BelongModuleTitle'], $fm->LANG['BelongNoModuleSkin']);
}

function viewTopics() {
    global $fm;
    
    applyModuleSkin();
    
    $user = $fm->_Read(EXBB_DATA_DIR_MEMBERS . '/' . $fm->_Intval('to') . '.php');
    
    if (!$user) {
        $fm->_Message($fm->LANG['BelongModuleTitle'], $fm->LANG['BelongUserNotFound']);
    }
    
    $usernames[$fm->input['to']] = $user['name'];
    unset($user);
    
    $page = ($fm->_Intval('p', 1) > 0) ? $fm->input['p'] : 1;
    
    $belong = new Belong;
    $topics = $belong->getTopics($fm->input['to'], ($page - 1) * $fm->exbb['topics_per_page'], $fm->exbb['topics_per_page'], $allforums, $found);
    
    if ($topics === false) {
        $fm->_Message($fm->LANG['BelongModuleTitle'], sprintf($fm->LANG['BelongNoTopics'], $usernames[$fm->input['to']]));
    }
    else if (!$topics) {
        $fm->_Message($fm->LANG['BelongModuleTitle'], $fm->LANG['BelongIncorrectPage']);
    }
    
    $pages = Print_Paginator($found, "tools.php?action=belong&to={$fm->input['to']}&what=topics&p={_P_}", $fm->exbb['topics_per_page'], 8, $first, TRUE);
    
    $t_visits = $fm->_GetCookieArray('t_visits');
    
    $viewsData = array();
    $topicsData = '';
    foreach ($topics as $info) {
        list($post, $forum, $topic) = $info;
        
        $thread = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum . "/{$topic}-thd.php");
        
        if (!$thread) {
            continue;
        }
        
        $replies = count($thread) - 1;
        
        end($thread);
        $postdate = key($thread);
        $poster = $thread[$postdate]['p_id'];
        
        $thread = reset($thread);
        
        $thread['posts']        = $replies;
        $thread['postdate']     = $postdate;
        
        $topicVisitTime = (isset($t_visits[$forum . ':' . $topic]) && $t_visits[$forum . ':' . $topic] > $fm->user['last_visit']) ? $t_visits[$forum . ':' . $topic] : $fm->user['last_visit'];
        
        $topicicon  = topic_icon($thread, $topicVisitTime);
        $topicname  = "<a href=\"topic.php?forum={$forum}&topic={$topic}\">{$thread['name']}</a>";
        $topicdesc  = ($thread['desc'] !== false && $thread['desc'] !== '') ? '&nbsp;&nbsp;&raquo;' . $thread['desc'] : '';
        $forumname  = "<a href=\"forums.php?forum={$forum}\">{$allforums[$forum]['name']}</a>";
        $postdate   = $fm->_DateFormat($postdate + $fm->user['timedif'] * 3600);
        
        if (!isset($viewsData[$forum])) {
            $viewsData[$forum] = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum . "/views.php");
        }
        
        $views = (isset($viewsData[$forum][$topic])) ? $viewsData[$forum][$topic] : 0;
        
        if ($poster && !isset($usernames[$poster])) {
            $usernames[$poster] = ($user = $fm->_Read(EXBB_DATA_DIR_MEMBERS ."/{$poster}.php")) ? $user['name'] : false;
            unset($user);
        }
        
        if ($poster) {
            $poster = ($usernames[$poster] !== false) ? "<a href=\"profile.php?action=show&member={$poster}\">{$usernames[$poster]}</a>" : $fm->LANG['BelongUserDeleted'];
        }
        else {
            $poster = $fm->LANG['Guest'];
        }
        
        include('templates/' . MODULE_SKIN . '/modules/belong/topics_data.tpl');
    }
    
    $topicsByUser = sprintf($fm->LANG['BelongTopicsByUser'], $usernames[$fm->input['to']]);
    
    $fm->_Title = ' :: ' . $topicsByUser;
    include('templates/' . MODULE_SKIN . '/all_header.tpl');
    include('templates/' . MODULE_SKIN . '/logos.tpl');
    include('templates/' . MODULE_SKIN . '/modules/belong/topics_body.tpl');
    include('templates/' . MODULE_SKIN . '/footer.tpl');
}

function viewPosts() {
    global $fm;
    
    applyModuleSkin();
    
    $user = $fm->_Read(EXBB_DATA_DIR_MEMBERS . '/' . $fm->_Intval('to') . '.php');
    
    if (!$user) {
        $fm->_Message($fm->LANG['BelongModuleTitle'], $fm->LANG['BelongUserNotFound']);
    }
    
    $page = ($fm->_Intval('p', 1) > 0) ? $fm->input['p'] : 1;
    
    $belong = new Belong;
    $posts = $belong->getPosts($fm->input['to'], ($page - 1) * $fm->exbb['posts_per_page'], $fm->exbb['posts_per_page'], $allforums, $found);
    
    if ($posts === false) {
        $fm->_Message($fm->LANG['BelongModuleTitle'], sprintf($fm->LANG['BelongNoPosts'], $user['name']));
    }
    else if (!$posts) {
        $fm->_Message($fm->LANG['BelongModuleTitle'], $fm->LANG['BelongIncorrectPage']);
    }
    
    $pages = Print_Paginator($found, "tools.php?action=belong&to={$fm->input['to']}&p={_P_}", $fm->exbb['posts_per_page'], 8, $first, TRUE);
    
    $username = "<a href=\"profile.php?action=show&member={$fm->input['to']}\">{$user['name']}</a>";

    $viewsData = array();
    $postsData = '';
    foreach ($posts as $info) {
        list($post, $forum, $topic) = $info;
        
        $thread = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum . "/{$topic}-thd.php");
        
        if (!isset($thread[$post])) {
            continue;
        }
        
        $first = reset($thread);
        
        if (!isset($viewsData[$forum])) {
            $viewsData[$forum] = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum . "/views.php");
        }
        
        $postdate   = $fm->_DateFormat($post + $fm->user['timedif'] * 3600);
        $topicname  = "<a href=\"topic.php?forum={$forum}&topic={$topic}&postid={$post}#{$post}\">{$first['name']}</a>";
        $forumname  = "<a href=\"forums.php?forum={$forum}\">{$allforums[$forum]['name']}</a>";
        $replies    = count($thread) - 1;
        $views      = (isset($viewsData[$forum][$topic])) ? $viewsData[$forum][$topic] : 0;
        $postText   = ($fm->exbb['exbbcodes'] && $allforums[$forum]['codes']) ?
            $fm->formatpost($thread[$post]['post'], (isset($thread[$post]['html'])) ? $thread[$post]['html'] : false, $thread[$post]['smiles']) : $thread[$post]['post'];
        
        include('templates/' . MODULE_SKIN . '/modules/belong/posts_data.tpl');
    }

 $fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
 <script type=\"text/javascript\" language=\"JavaScript\">
 var LANG = {
 Spoiler: '{$fm->LANG['Spoiler']}',
 SpoilerShow: '{$fm->LANG['SpoilerShow']}',
 SpoilerHide: '{$fm->LANG['SpoilerHide']}'
 };
 </script>";

    $postsByUser = sprintf($fm->LANG['BelongPostsByUser'], $user['name']);
    
    include('templates/' . MODULE_SKIN . '/all_header.tpl');
    include('templates/' . MODULE_SKIN . '/logos.tpl');
    include('templates/' . MODULE_SKIN . '/modules/belong/posts_body.tpl');
    include('templates/' . MODULE_SKIN . '/footer.tpl');
}

?>