<?php

/**
 * Belong Mod for ExBB FM 1.0 RC2
 * @copyright ExBB Group, http://www.exbb.org/
 *
 * @author Yuri Antonov, http://www.exbb.org/
 *
 * @version 1.0 $Id:$
 */

define('BELONG_DATA_DIR',       'modules/belong/data/');
define('BELONG_CONFIG_FILE',    'modules/belong/data/config.php');

class Belong {
    var $config = array();
    var $_handle = false;
    
    function Belong() {
        global $fm;
        
        $this->config = $fm->_Read(BELONG_CONFIG_FILE);
    }
    
    function saveConfig() {
        global $fm;
        
        $fm->_Read2Write($fpConfig, BELONG_CONFIG_FILE);
        $fm->_Write($fpConfig, $this->config);
    }
    
    function _getDbFilename($userId) {
        global $fm;
        
        return BELONG_DATA_DIR . (intval(($userId - 1) / $this->config['membersPerDb']) + 1) . '.db';
    }
    
    function _createTable() {
        $sql = 'CREATE TABLE posts (member INTEGER, creator INTEGER, post INTEGER, forum INTEGER, topic INTEGER, PRIMARY KEY(member, creator, post))';
        
        sqlite_query($this->_handle, $sql);
    }
    
    function _openSqlite($userId, $createDb = true) {
        global $fm;
        
        $dbname = $this->_getDbFilename($userId);
        
        $existed = file_exists($dbname);
        
        if (!$existed && !$createDb) {
            return false;
        }
        
        if ($this->_handle !== false) {
            sqlite_close($this->_handle);
        }
        
        if (!$existed) {
            @fclose(@fopen($dbname, 'a+'));
            @chmod($dbname, $fm->exbb['ch_files']);
        }
        $this->_handle = sqlite_open($dbname);
        
        if (!$existed) {
            $this->_createTable();
        }
        
        return true;
    }
    
    function _getAllforums() {
        global $fm;
        
        $allforums = $fm->_Read(FM_ALLFORUMS);
        
        foreach ($allforums as $id => $forum) {
            if ($forum['stview'] == 'reged' && !$fm->user['id'] ||
                $forum['stview'] == 'admo' && !defined('IS_ADMIN') && $fm->user['status'] != 'sm' && !isset($forum['moderator'][$fm->user['id']]) ||
                $forum['private'] && !defined('IS_ADMIN') && !isset($fm->user['private'][$id])) {
                
                unset($allforums[$id]);
            }
        }
        
        return $allforums;
    }
    
    function newTopic($user, $forum, $topic, $post) {
        global $fm;
        
        $this->_openSqlite($user);
        
        $sql = "INSERT INTO posts VALUES ({$user}, 1, {$post}, {$forum}, {$topic})";
        
        sqlite_exec($this->_handle, $sql);
    }
    
    function addReply($user, $forum, $topic, $post) {
        global $fm;
        
        $this->_openSqlite($user);
        
        $sql = "INSERT INTO posts VALUES ({$user}, 0, {$post}, {$forum}, {$topic})";
        
        sqlite_exec($this->_handle, $sql);
    }
    
    function deletePost($post) {
        global $fm;
        
        $this->_OpenSqlite($fm->user['id']);
        
        $sql = "DELETE FROM posts WHERE member = {$fm->user['id']} AND post = {$post}";
        
        sqlite_exec($this->_handle, $sql);
    }
    
    function deleteTopic($forum, $topic, $users) {
        ksort($users);
        
        $sql = "DELETE FROM posts WHERE forum = {$forum} AND topic = {$topic}";
        
        $dbname = '';
        foreach ($users as $id => $posts) {
            if ($dbname != $this->_getDbFilename($id)) {
                $this->_openSqlite($id, false);
                
                $dbname = $this->_getDbFilename($id);
                
                sqlite_exec($this->_handle, $sql);
            }
        }
    }
    
    function moveTopic($forum, $topic, $toForum, $toTopic, $users) {
        ksort($users);
        
        $sql = "UPDATE posts SET forum = {$toForum}, topic = {$toTopic} WHERE forum = {$forum} AND topic = {$topic}";
        
        $dbname = '';
        foreach ($users as $id => $posts) {
            if ($dbname != $this->_getDbFilename($id)) {
                $this->_openSqlite($id, false);
                
                $dbname = $this->_getDbFilename($id);
                
                sqlite_exec($this->_handle, $sql);
            }
        }
    }
    
    function deletePosts($users) {
        ksort($users);
        
        $dbname = '';
        foreach ($users as $id => $posts) {
            if ($dbname != $this->_getDbFilename($id)) {
                if ($dbname) {
                    sqlite_exec($this->_handle, $sql);
                }
                
                $this->_openSqlite($id, false);
                
                $dbname = $this->_getDbFilename($id);
                
                $sql = '';
            }
            
            $sql .= "DELETE FROM posts WHERE member = {$id} AND post IN (" . implode(', ', $posts) . ');';
        }
        
        sqlite_exec($this->_handle, $sql);
    }
    
    function inNew($toForum, $toTopic, $users) {
        $author = key($users);
        $authorPost = reset($users[$author]);
        
        ksort($users);
        
        $dbname = '';
        foreach ($users as $id => $posts) {
            if ($dbname != $this->_getDbFilename($id)) {
                if ($dbname) {
                    sqlite_exec($this->_handle, $sql);
                }
                
                $this->_openSqlite($id, false);
                
                $dbname = $this->_getDbFilename($id);
                
                $sql = '';
            }
            
            $sql .= "UPDATE posts SET forum = {$toForum}, topic = {$toTopic} WHERE member = {$id} AND post IN(" . implode(', ', $posts) . ");";
            
            if ($id == $author) {
                $sql .= "UPDATE posts SET creator = 1 WHERE member = {$author} AND post = {$authorPost};";
            }
        }
        
        sqlite_exec($this->_handle, $sql);
    }
    
    function inExists($toForum, $toTopic, $users, $newUsers) {
        ksort($users);
        ksort($newUsers);
        
        $dbname = '';
        foreach ($users as $id => $posts) {
            if ($dbname != $this->_getDbFilename($id)) {
                if ($dbname) {
                    sqlite_exec($this->_handle, $sql);
                }
                
                $this->_openSqlite($id, false);
                
                $dbname = $this->_getDbFilename($id);
                
                $sql = '';
            }
            
            foreach ($posts as $offset => $post) {
                $sql .= "UPDATE posts SET post = {$newUsers[$id][$offset]}, forum = {$toForum}, topic = {$toTopic} WHERE member = {$id} AND post = {$post};";
            }
        }
        
        sqlite_exec($this->_handle, $sql);
    }
    
    function deleteForums($forums) {
        ;
    }
    
    function deleteUsers($users) {
        ;
    }
    
    function getTopics($userId, $offset, $length, &$allforums, &$found) {
        $allforums = $this->_getAllforums();
        $forums = implode(', ', array_keys($allforums));
        
        if (!$this->_openSqlite($userId, false)) {
            return false;
        }
        
        $sql = "SELECT COUNT(*) AS found FROM posts WHERE member = {$userId} AND creator = 1 AND forum IN({$forums})";
        
        $result = sqlite_query($this->_handle, $sql);
        $found = sqlite_column($result, 'found');
        
        if (!$found) {
            return false;
        }
        
        $sql = "SELECT post, forum, topic FROM posts WHERE member = {$userId} AND creator = 1 AND forum IN({$forums}) ORDER BY post DESC LIMIT {$offset}, {$length}";
        $topics = sqlite_array_query($this->_handle, $sql, SQLITE_NUM);
        
        return $topics;
    }
    
    function getPosts($userId, $offset, $length, &$allforums, &$found) {
        $allforums = $this->_getAllforums();
        $forums = implode(', ', array_keys($allforums));
        
        if (!$this->_openSqlite($userId, false)) {
            return false;
        }
        
        $sql = "SELECT COUNT(*) AS found FROM posts WHERE member = {$userId} AND forum IN({$forums})";
        
        $result = sqlite_query($this->_handle, $sql);
        $found = sqlite_column($result, 'found');
        
        if (!$found) {
            return false;
        }
        
        $sql = "SELECT post, forum, topic FROM posts WHERE member = {$userId} AND forum IN({$forums}) ORDER BY post DESC LIMIT {$offset}, {$length}";
        $posts = sqlite_array_query($this->_handle, $sql, SQLITE_NUM);
        
        return $posts;
    }
    
    function _deleteDbs() {
        $dir = opendir(BELONG_DATA_DIR);
        
        while (($file = readdir($dir)) !== false) {
            if (!preg_match('#\.db$#is', $file, $tst)) {
                continue;
            }
            
            unlink(BELONG_DATA_DIR . $file);
        }
        
        closedir($dir);
    }
    
    function _writePosts($index) {
        ksort($index);
        
        $dbname = '';
        foreach ($index as $id => $posts) {
            ksort($posts);
            
            if ($dbname != $this->_getDbFilename($id)) {
                if ($dbname) {
                    sqlite_exec($this->_handle, $sql);
                }
                
                $this->_openSqlite($id);
                
                $dbname = $this->_getDbFilename($id);
                
                $sql = '';
            }
            
            foreach ($posts as $post => $info) {
                $sql .= "INSERT INTO posts VALUES ({$id}, {$info[0]}, {$post}, {$info[1]}, {$info[2]});";
            }
        }
        
        sqlite_exec($this->_handle, $sql);
    }
    
    function index() {
        global $fm;
        
        $allforumsKeys = array_keys($fm->_Read(FM_ALLFORUMS));
        $percent = count($allforumsKeys);
        
        if (isset($this->config['last'])) {
            $allforumsKeys = array_slice($allforumsKeys, array_search($this->config['last'][0], $allforumsKeys));
        }
        else {
            $this->_deleteDbs();
        }
        
        $index = array();
        $count = 0;
        foreach ($allforumsKeys as $forum) {
            $listKeys = array_keys($fm->_Read("forum{$forum}/list.php"));
            
            if (isset($this->config['last'])) {
                $listKeys = array_slice($listKeys, array_search($this->config['last'][1], $listKeys));
                
                unset($this->config['last']);
            }
            
            foreach ($listKeys as $topic) {
                if ($count >= 200) {
                    $this->_writePosts($index);
                    
                    $this->config['last'] = array($forum, $topic);
                    $this->saveConfig();
                    
                    $fm->_Message($fm->LANG['ModuleTitle'], sprintf($fm->LANG['BelongIndexingProgress'],
                        ($percent - count($allforumsKeys)) / $percent * 100, $forum, $topic), 'setmodule.php?module=belong&execute=index', 1);
                }
                
                $thread = $fm->_Read("forum{$forum}/{$topic}-thd.php");
                
                foreach ($thread as $post => $info) {
                    if (!$info['p_id'] && !file_exists("members/{$info['p_id']}.php")) {
                        continue;
                    }
                    
                    $index[$info['p_id']][$post] = array((isset($info['name'])) ? 1 : 0, $forum, $topic);
                    
                    ++$count;
                }
            }
        }
        
        if ($index) {
            $this->_writePosts($index);
        }
        
        unset($this->config['last']);
        $this->saveConfig();
        
        $fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['BelongIndexingOk'], 'setmodule.php?module=belong', 1);
    }
}

?>