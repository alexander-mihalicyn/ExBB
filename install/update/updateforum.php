<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
@set_time_limit(3600);
$updatefile = $_ForumRoot.'install/temp/_allforums.php';
if (!file_exists($updatefile)){
	copy($_ForumRoot.'data/allforums.php',$updatefile);
	@chmod($updatefile,$fm->exbb['ch_files']);
}

$allforums = $fm->_Read2Write($fp_upallforums,$updatefile);
if (count($allforums)>0) {
	reset($allforums);
	$forum_id = key($allforums);
	$forum = $allforums[$forum_id];
	$forumname = $allforums[$forum_id]['name'];
	unset($allforums[$forum_id]);
	$fm->_Write($fp_upallforums,$allforums);

	$temp_users = $fm->_Read2Write($fp_tempusers,$_ForumRoot.'install/temp/_users.php');

	$total_posts = 0;
	$list = $fm->_Read($_ForumRoot.'forum'.$forum_id.'/list.php');
	$total_topics = count($list);
	foreach ($list as $topic_id => $topic) {
            $total_posts += $topic['posts'];

            $allposts = $fm->_Read2Write($fp_allposts,$_ForumRoot.'forum'.$forum_id.'/'.$topic_id.'-thd.php');

			$new_allposts = $new_poll = array();
			$attaches = $fm->_Read($_ForumRoot.'_forum'.$forum_id.'/attaches-'.$topic_id.'.php');
			$new_attaches = array();
			foreach ($allposts as $post_id => $post) {
            		$new_allposts[$post_id] = $post;
            		$new_allposts[$post_id]['post']		= htmlspecialchars(pre_replace($post['post']),ENT_QUOTES);
			        $new_allposts[$post_id]['post']		= preg_replace("#(\[q|\[quote):([^\[\]]+?\])#is", "$1=$2", $new_allposts[$post_id]['post']);

			        if (isset($post['attach_id']) && isset($post['attach_file']) && isset($attaches[$post['attach_id']])) {
                        $attach_id = $post['attach_id'];
                        if (file_exists($_ForumRoot.'uploads/'.$attaches[$attach_id]['id'])) {
         					$new_attaches[$attach_id]['id']   = $attaches[$attach_id]['id'];
        					$new_attaches[$attach_id]['hits'] = $attaches[$attach_id]['hits'];
        					$new_attaches[$attach_id]['file'] = $attaches[$attach_id]['file'];
        					$new_attaches[$attach_id]['size'] = filesize($_ForumRoot.'uploads/'.$attaches[$attach_id]['id']);
        					$type = (isset($attaches[$attach_id]['size'])) ? 'image':'file';
        					$type = (isset($attaches[$attach_id]['tared']) && $attaches[$attach_id]['tared'] === TRUE) ? 'gz':$type;
							$type = (isset($attaches[$attach_id]['tared']) && $attaches[$attach_id]['tared'] === FALSE) ? 'tar':$type;

        					$new_attaches[$attach_id]['type'] = $type;
        					if ($type === 'image') {
            					list($width,$height) =  explode(":",$attaches[$attach_id]['size']);
            					if (intval($width) === 0 || intval($height) === 0) {
            						list($width,$height) =  getimagesize($_ForumRoot.'uploads/'.$attaches[$attach_id]['id']);
            					}
            					$new_attaches[$attach_id]['width'] = intval($width);
        						$new_attaches[$attach_id]['height'] = intval($height);
        					}
                    		$new_allposts[$post_id]['attach_id'] 	= $post['attach_id'];
                    		$new_allposts[$post_id]['attach_file'] 	= $post['attach_file'];
                        }
             		}

             		if ($post['p_id'] !== 0) {
             			$poster_id = $post['p_id'];
             			$temp_users[$poster_id]['posts'] = (isset($temp_users[$poster_id]['posts'])) ? $temp_users[$poster_id]['posts']+1:1;
             			$temp_users[$poster_id]['posted'][$forum_id] = (isset($temp_users[$poster_id]['posted'][$forum_id])) ?	$temp_users[$poster_id]['posted'][$forum_id]+1:1;
             			$temp_users[$poster_id]['lastpost']['date'] = (isset($temp_users[$poster_id]['lastpost']['date'])) ? $temp_users[$poster_id]['lastpost']['date']:0;
             			if ($post_id > $temp_users[$poster_id]['lastpost']['date']) {
             				$temp_users[$poster_id]['lastpost']['date'] = $post_id;
             				$temp_users[$poster_id]['lastpost']['link'] = 'topic.php?forum='.$forum_id.'&topic='.$topic_id;
             				$temp_users[$poster_id]['lastpost']['name'] = $list[$topic_id]['name'];
             			}
             		}
			}
			unset($allposts);
			$fm->_Write($fp_allposts,$new_allposts);
            if (count($new_attaches) !== 0) {
            	$fm->_Read2Write($fp_attach,$_ForumRoot.'forum'.$forum_id.'/attaches-'.$topic_id.'.php');
            	$fm->_Write($fp_attach,$new_attaches);
            }

            if ($list[$topic_id]['poll'] === TRUE) {
            	$new_poll = $fm->_Read($_ForumRoot.'_forum'.$forum_id.'/'.$topic_id.'-poll.php');
            	$new_poll['choices'] = unserialize($new_poll['choices']);
            	$new_poll['ids'] = unserialize($new_poll['ids']);
            	$fm->_Read2Write($fp_poll,$_ForumRoot.'forum'.$forum_id.'/'.$topic_id.'-poll.php');
            	$fm->_Write($fp_poll,$new_poll);
            }
	}
	$fm->_Write($fp_tempusers,$temp_users);
	uasort ($list, 'sort_by_postdate');
	reset($list);
	$firstopic_key = key($list);

    $newallforums = $fm->_Read2Write($fp_allforums,$_ForumRoot.'data/allforums.php');
	$newallforums[$forum_id]['topics']			= $total_topics;
	$newallforums[$forum_id]['posts']			= $total_posts;
	$newallforums[$forum_id]['last_poster']		= $list[$firstopic_key]['poster'];
	$newallforums[$forum_id]['last_poster_id'] 	= $list[$firstopic_key]['p_id'];
	$newallforums[$forum_id]['last_post'] 		= (isset($list[$firstopic_key]['tnun'])) ? $list[$firstopic_key]['name'].' - '.$list[$firstopic_key]['tnun']:$list[$firstopic_key]['name'];
	$newallforums[$forum_id]['last_post_id'] 	= $firstopic_key;
	$newallforums[$forum_id]['last_time']		= $list[$firstopic_key]['postdate'];
	$newallforums[$forum_id]['last_key']		= $list[$firstopic_key]['postkey'];
	$fm->_Write($fp_allforums,$newallforums);

	$warning = '<div class="ok">'.$lang['NoError'].'Форум "'.$forumname.'" успешно обновлен!</div>';
	$action = 'updateforum&rand='.mt_rand(0,10000);
} else {
		fclose($fp_upallforums);
		$_SESSION['updateusers'] = TRUE;
		unlink($updatefile);
		header("Location: update.php?action=updateusers");
		exit();
}

/*
	FUNCTIONS
*/
function GetUserName($id) {
		global $fm;

		if ($id === 0) {
			return 'Гость';
		}
        $user = _Getmember($id);
        if($user !== FALSE) {
               $name = $user['name'];
               unset($user);
               return htmlspecialchars(pre_replace($name),ENT_QUOTES);;
        } else {
        		return 'Гость';
        }
}
function check_poll($forum_id,$topic_id){
		global $fm,$_ForumRoot;
		$pollfile = $_ForumRoot.'_forum'.$forum_id.'/'.$topic_id.'-poll.php';
		if (!file_exists($pollfile)) return FALSE;
		$poldata = $fm->_Read($pollfile,FALSE);
		return (count($poldata)===0) ? FALSE:TRUE;
}
function _Getmember($id) {
		global $fm,$_ForumRoot;
		if (!file_exists($_ForumRoot.'_members/'.$id.'.php')) {
			return FALSE;
		}
        return $fm->_Read($_ForumRoot.'_members/'.$id.'.php',FALSE);
}
?>