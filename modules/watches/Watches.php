<?php

/*
	Watches Mod for ExBB FM 1.0 RC2
	Copyright (c) 2004 - 2011 by Yuri Antonov aka yura3d
	Copyright (c) 2009 - 2011 by ExBB Group
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) {
	die;
}

define('FM_WATCHES_DIR',			'modules/watches/');
define('FM_WATCHES_DATA_DIR',		FM_WATCHES_DIR . 'data/');
define('FM_WATCHES_CONFIG_FILE',	FM_WATCHES_DATA_DIR . 'config.php');

class Watches {
	private $config = array();
	private $_dbname = '';
	private $_handle = false;
	private $_result = false;
	private $_filter;
	
	public function __construct() {
		$this->getConfig();
	}
	
	function getConfig() {
		global $fm;
		
		$this->config = $fm->_Read(FM_WATCHES_CONFIG_FILE);
		
		return true;
	}
	
	function watchingForums($forums, $lasts) {
		global $fm;
		
		if (!$forums) {
			return false;
		}
		
		$this->_openSqlite();
		
		$deadlines = $this->_getDeadlines($forums);
		
		$forums = array_flip($forums);
		$where = array();
		foreach (array_keys($forums) as $forum) {
			$this->_filter = max($deadlines[0], $deadlines[$forum]);
			$list[$forum] = array_filter($fm->_Read("forum{$forum}/list.php"), array($this, '_filterTopics'));
			array_walk($list[$forum], array($this, '_walkTopics'));
			
			$forums[$forum] = array();
			
			if ($list[$forum]) {
				$where[] = "forum = {$forum} AND topic IN (" . implode(', ', array_keys($list[$forum])) . ") AND time > {$deadlines[$forum]}";
			}
		}
		if ($where) {
			$sql = 'SELECT * FROM watches WHERE (' . implode(' OR ', $where) . ") AND time > {$deadlines[0]};";
			$this->_querySqlite($sql);
			while ($row = $this->_fetchSqlite()) {
				$forum	= $row['forum'];
				$topic	= $row['topic'];
				
				if (isset($list[$forum][$topic])) {
					$forums[$forum][$topic] = ($list[$forum][$topic] > $row['time']);
				}
			}
		}
		
		foreach ($forums as $forum => $topics) {
			$forums[$forum] = array_diff_key($list[$forum], $topics) + array_filter($topics);
			
			$forums[$forum] = array(
				count($forums[$forum]),
				($lasts[$forum] && isset($forums[$forum][$lasts[$forum]]))
			);
			
			unset($list[$forum]);
		}
		
		return $forums;
	}
	
	function watchingTopics($topics) {
		global $fm;
		
		$this->_openSqlite();
		
		$deadlines = $this->_getDeadlines(array_keys($topics));
		
		foreach ($topics as $forum => $list) {
			$where[] = "forum = {$forum} AND topic IN (" . implode(', ', array_keys($list)) . ") AND time > {$deadlines[$forum]}";
		}
		$sql = 'SELECT * FROM watches WHERE (' . implode(' OR ', $where) . ") AND time > {$deadlines[0]};";
		$this->_querySqlite($sql);
		while ($row = $this->_fetchSqlite()) {
			$forum	= $row['forum'];
			$topic	= $row['topic'];
			
			$topics[$forum][$topic] = ($topics[$forum][$topic]['postdate'] > $row['time'] && $topics[$forum][$topic]['p_id'] != $fm->user['id']);
		}
		
		foreach ($topics as $forum => $list) {
			foreach ($list as $topic => $info) {
				if (!is_bool($info)) {
					$topics[$forum][$topic] = ($topics[$forum][$topic]['postdate'] > max($deadlines[0], $deadlines[$forum]) &&
						$topics[$forum][$topic]['p_id'] != $fm->user['id']);
				}
			}
		}
		
		return $topics;
	}
	
	function watchingTopic($forum, $topic, $postdate, $postkey, $watchkey) {
		$this->_openSqlite();
		
		$deadline = $this->_getDeadline($forum);
		
		$sql = "SELECT time FROM watches WHERE forum = {$forum} AND topic = {$topic};";
		$this->_querySqlite($sql);
		$time = $this->_singleSqlite();
		
		if ($postdate > $deadline && $postdate > $time) {
			$newTime = ($watchkey != $postkey) ? $watchkey : $postdate;
			
			$sql = "INSERT OR REPLACE INTO watches VALUES ({$forum}, {$topic}, {$newTime});";
			$this->_execSqlite($sql);
		}
		
		return ($time) ? $time : $deadline;
	}
	
	function watchingNewTopics($forums) {
		global $fm;
		
		if (!$forums) {
			return false;
		}
		
		$this->_openSqlite();
		
		$deadlines = $this->_getDeadlines($forums);
		
		$forums = array_flip($forums);
		$where = array();
		foreach (array_keys($forums) as $forum) {
			$this->_filter = max($deadlines[0], $deadlines[$forum]);
			$list[$forum] = array_filter($fm->_Read("forum{$forum}/list.php"), array($this, '_filterTopics'));
			
			$forums[$forum] = array();
			
			if ($list[$forum]) {
				$where[] = "forum = {$forum} AND topic IN (" . implode(', ', array_keys($list[$forum])) . ") AND time > {$deadlines[$forum]}";
			}
		}
		if ($where) {
			$sql = 'SELECT * FROM watches WHERE (' . implode(' OR ', $where) . ") AND time > {$deadlines[0]};";
			$this->_querySqlite($sql);
			while ($row = $this->_fetchSqlite()) {
				$forum	= $row['forum'];
				$topic	= $row['topic'];
				
				if (isset($list[$forum][$topic])) {
					$forums[$forum][$topic] = $list[$forum][$topic] + array('watched' => ($list[$forum][$topic]['postdate'] > $row['time']));
				}
				
				unset($list[$forum][$topic]);
			}
		}
		
		$new = array();
		foreach ($forums as $forum => $topics) {
			$new = array_merge($new, $topics + array_diff_key($list[$forum], $topics));
			
			unset($list[$forum], $forums[$forum]);
		}
		
		return $new;
	}
	
	function markForums($forums = array()) {
		global $fm;
		
		$this->_openSqlite();

		if (!$forums) {
			$sql = 'DELETE FROM watches;' .
				"INSERT INTO watches VALUES (0, 0, {$fm->_Nowtime});";
			$this->_execSqlite($sql);
			
			return true;
		}
		
		$sql = 'DELETE FROM watches WHERE forum IN (' . implode(', ', $forums) . ');';
		foreach ($forums as $forum) {
			$sql .= "INSERT INTO watches VALUES ({$forum}, 0, {$fm->_Nowtime});";
		}
		$this->_execSqlite($sql);
		
		return true;
	}
	
	function upDeadlines($uid = 0) {
		global $fm;

		if (!$uid) {
			$uid = $fm->user['id'];
		}
		
		$this->_openSqlite($uid);
		
		$deadline		= $this->_getDeadline();
		$newDeadline	= $fm->_Nowtime - $this->config['days'] * 86400;
		
		if (ceil($deadline / 86400) >= ceil($newDeadline / 86400)) {
			return false;
		}
		
		$sql = "DELETE FROM watches WHERE time <= {$newDeadline};" .
			"UPDATE watches SET time = {$newDeadline} WHERE forum = 0 AND topic = 0;";
		$this->_execSqlite($sql);
		
		return true;
	}
	
	function _filterTopics($topic) {
		global $fm;
		
		if ($topic['postdate'] > $this->_filter && $topic['p_id'] != $fm->user['id'] && $topic['state'] != 'moved') {
			return true;
		}
		
		return false;
	}
	
	function _walkTopics(&$topic) {
		$topic = $topic['postdate'];
	}
	
	function _getDeadlines($forums) {
		$forums = array_unique(array_merge(array(0), $forums));
		
		$sql = 'SELECT forum, time FROM watches WHERE forum IN (' . implode(', ', $forums) . ') AND topic = 0;';
		$this->_querySqlite($sql);
		while ($row = $this->_fetchSqlite()) {
			$deadlines[$row['forum']] = $row['time'];
		}
		
		foreach ($forums as $forum) {
			if (!isset($deadlines[$forum])) {
				$deadlines[$forum] = 0;
			}
		}
		
		return $deadlines;
	}
	
	function _getDeadline($forum = 0) {
		$sql = "SELECT MAX(time) FROM watches WHERE forum IN (0, {$forum}) AND topic = 0 GROUP BY time;";
		$this->_querySqlite($sql);
		
		return intval($this->_singleSqlite());
	}
	
	function _openSqlite($uid = 0) {
		global $fm;
		
		if (!$uid) {
			$uid = $fm->user['id'];
		}
		
		$dbname = FM_WATCHES_DATA_DIR . "member{$uid}.db";
		
		if ($dbname == $this->_dbname) {
			return true;
		}
		
		if ($this->_handle) {
			$this->_handle->close();
		}
		
		$this->_dbname = $dbname;
		
		if (!$exists = file_exists($this->_dbname)) {
			@fclose(@fopen($this->_dbname, 'a+'));
			@chmod($this->_dbname, $fm->exbb['ch_files']);
		}
		
		$this->_handle = new SQLite3($this->_dbname);
		
		if (!$exists) {
			$sql = 'CREATE TABLE watches (forum INTEGER, topic INTEGER, time INTEGER, PRIMARY KEY (forum, topic));' .
				'CREATE INDEX time ON watches (time);' .
				"INSERT INTO watches VALUES (0, 0, {$fm->_Nowtime});";
			$this->_execSqlite($sql);
		}
		
		return true;
	}
	
	function _execSqlite($sql) {
		$this->_result = $this->_handle->exec($sql) or $this->_error($sql);
		
		return $this->_result;
	}
	
	function _querySqlite($sql) {
		$this->_result = $this->_handle->query($sql) or $this->_error($sql);
		
		return $this->_result;
	}
	
	function _singleSqlite($result = false) {
		$result = ($result) ? $result : $this->_result;
		
		$row = $result->fetchArray(SQLITE3_NUM);
		
		return (isset($row[0])) ? $row[0] : null;
	}
	
	function _fetchSqlite($result = false) {
		$result = ($result) ? $result : $this->_result;
		
		return $result->fetchArray(SQLITE3_ASSOC);
	}
	
	function _error($sql = '') {
		$errno	= $this->_handle->lastErrorCode();
		$error	= $this->_handle->lastErrorMsg();
		echo "<b>SQLite error #{$errno}:</b> {$error}<!--";
		
		print_r(debug_backtrace());
		
		die('//-->');
	}
}

?>