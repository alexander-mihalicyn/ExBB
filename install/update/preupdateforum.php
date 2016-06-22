<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
@set_time_limit(3600);
$updatefile = $_ForumRoot.'install/temp/_allforums.php';
if (!file_exists($updatefile)){
	copy($_ForumRoot.'data/allforums.php',$updatefile);
	@chmod($updatefile,$fm->exbb['ch_files']);
}

$allforums = $fm->_Read2Write($fp_allforums,$updatefile);
if (count($allforums)>0) {
	reset($allforums);
	$forum_id = key($allforums);
	$forum = $allforums[$forum_id];
	$forumname = $allforums[$forum_id]['name'];
	unset($allforums[$forum_id]);
	$fm->_Write($fp_allforums,$allforums);

	$new_list = array();
	$new_views = array();
	$list = $fm->_Read($_ForumRoot.'_forum'.$forum_id.'/list.php');
	$pinned = $fm->_Read($_ForumRoot.'_forum'.$forum_id.'/_pinned.php');
	$views 	= $fm->_Read($_ForumRoot.'_forum'.$forum_id.'/views.php');
	foreach ($list as $topic_id => $topic) {
            if ($topic['state'] == 'moved') continue;
            $topic_posts = 0;

            $allposts = $fm->_Read($_ForumRoot.'_forum'.$forum_id.'/'.$topic_id.'-thd.php');
            $i = 1;
            while (file_exists($_ForumRoot.'_forum'.$forum_id.'/'.$topic_id.'-thd'.$i.'.php')) {
            	$allposts = $allposts + $fm->_Read($_ForumRoot.'_forum'.$forum_id.'/'.$topic_id.'-thd'.$i.'.php');
            	$i++;
            }
            if (count($allposts) === 0) continue;
            ksort($allposts,SORT_NUMERIC);
            reset($allposts);
            $firspost_key = key($allposts);

            end($allposts);
            $lastpost_key = key($allposts);
            reset($allposts);

			$new_list[$topic_id]['name']		= htmlspecialchars(pre_replace($topic['name']),ENT_QUOTES);
			$new_list[$topic_id]['id']			= $topic_id;
			$new_list[$topic_id]['fid']			= $forum_id;
			$new_list[$topic_id]['desc']		= (isset($topic['desc']) && $topic['desc'] != '') ? htmlspecialchars(pre_replace($topic['desc']),ENT_QUOTES):'';
			$new_list[$topic_id]['state']		= $topic['state'];
			$new_list[$topic_id]['pinned']		= (isset($pinned[$topic_id])) ? TRUE:FALSE;

			$new_allposts = array();
			foreach ($allposts as $post_id => $post) {
            		if ($post_id === $firspost_key) {
            			$new_allposts[$post_id]['name']		= $new_list[$topic_id]['name'];
						$new_allposts[$post_id]['desc']		= $new_list[$topic_id]['desc'];
						$new_allposts[$post_id]['state']	= $new_list[$topic_id]['state'];
						$new_allposts[$post_id]['pinned']	= $new_list[$topic_id]['pinned'];
						if (isset($post['tnun']) && $post['tnun'] !== 0) {
							$new_list[$topic_id]['tnun']	= $post['tnun'];
							$new_allposts[$post_id]['tnun']	= $post['tnun'];
						}
						$author_id 	= (isset($post['p_id']) && boolean($post['p_id']) !== FALSE) ? $post['p_id']:0;
						$author		= ($author_id === 0) ? FALSE:GetUserName($author_id);
					}

                    if (isset($post['attach_id']) && isset($post['attach_file'])) {
                    	$new_allposts[$post_id]['attach_id'] = $post['attach_id'];
                    	$new_allposts[$post_id]['attach_file'] = $post['attach_file'];
                    }

					if ($post_id === $lastpost_key) {
							$poster_id 	= (isset($post['p_id']) && boolean($post['p_id']) !== FALSE) ? $post['p_id']:0;
							$poster		= ($poster_id === 0) ? FALSE:GetUserName($poster_id);
					}
					$new_allposts[$post_id]['p_id']		= (isset($post['p_id']) && boolean($post['p_id']) !== FALSE) ? $post['p_id']:0;
					$new_allposts[$post_id]['post']		= $post['post'];
					$new_allposts[$post_id]['ip']		= $post['ip'];
					$new_allposts[$post_id]['smiles']	= (isset($post['smiles']) && boolean($post['smiles']) === TRUE) ? TRUE:FALSE;
					$new_allposts[$post_id]['html']		= FALSE;
					$topic_posts++;
			}
            $fm->_Read2Write($fp_allposts,$_ForumRoot.'forum'.$forum_id.'/'.$topic_id.'-thd.php');
			$fm->_Write($fp_allposts,$new_allposts);

			$new_list[$topic_id]['posts']		= ($topic_posts != 0) ? $topic_posts-1:0;
			$new_list[$topic_id]['views']		= (isset($views[$topic_id])) ? $views[$topic_id]:0;
			$new_list[$topic_id]['author']		= $author;
			$new_list[$topic_id]['a_id']		= $author_id;
			$new_list[$topic_id]['date']		= $firspost_key;
			$new_list[$topic_id]['poster']		= $poster;
			$new_list[$topic_id]['p_id']		= $poster_id;
			$new_list[$topic_id]['postdate']	= $lastpost_key;
			$new_list[$topic_id]['postkey']		= $lastpost_key;
			$new_list[$topic_id]['poll']		= (isset($topic['poll']) && check_poll($forum_id,$topic_id) === TRUE) ? TRUE:FALSE;

			$new_views[$topic_id] = $new_list[$topic_id]['views'];
	}
	uasort ($new_list, 'sort_by_postdate');
	$fm->_Read2Write($fp_newlist,$_ForumRoot.'forum'.$forum_id.'/list.php');
	$fm->_Write($fp_newlist,$new_list);

    $fm->_Read2Write($fp_newviews,$_ForumRoot.'forum'.$forum_id.'/views.php');
	$fm->_Write($fp_newviews,$new_views);
	$warning = '<div class="ok">'.$lang['NoError'].'Форум "'.$forumname.'" подготовлен к обновлению!</div>';
	$action = 'preupdateforum&rand='.mt_rand(0,10000);
} else {
		fclose($fp_allforums);
		$_SESSION['updateforum'] = TRUE;
		unlink($_ForumRoot.'install/temp/_allforums.php');
		header("Location: update.php?action=updateforum&first=yes");
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