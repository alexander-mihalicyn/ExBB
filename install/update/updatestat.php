<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$allforums = $fm->_Read($_ForumRoot.'data/allforums.php');

$total_posts = $total_topics = $total_users = 0;
foreach ($allforums as $forum_id => $forums) {
		$total_posts += $forums['posts'];
		$total_topics += $forums['topics'];
}
unset($allforums);

$stat = file($_ForumRoot.'_data/max_online.php');
$max_time = intval(trim($stat[0]));
$max_online = intval(trim($stat[1]));

$users = $fm->_Read($_ForumRoot.'data/users.php');
$totalmembers = count($users);
ksort($users, SORT_NUMERIC);
end($users);
$last_id = key($users);
unset($users);
$last_user = $fm->_Read($_ForumRoot.'members/'.$last_id.'.php');
$lastreg = $last_user['name'];
unset($last_user);

$fm->_SAVE_STATS(array (
				'max_online' => array($max_online, 0),
				'max_time' => array($max_time, 0),
				'lastreg' => array($lastreg, 0),
				'last_id' => array($last_id, 0),
				'totalmembers'=> array($totalmembers, 0),
				'totalposts'=> array($total_posts, 0),
				'totalthreads'=> array($total_topics, 0),
				)
);

$warning = '<div class="ok">'.$lang['NoError'].'—татистика форума успешно обновлена!</div>';
$action = 'updateend';

?>