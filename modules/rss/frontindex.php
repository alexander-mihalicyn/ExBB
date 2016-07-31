<?php
/***************************************************************************
 * "RSS Lastposts" mods for  ExBB Full Mods v.0.1.5                        *
 * Copyright (c) 2005 by Alisher Mutalov aka MarkusR                       *
 *                                                                         *
 * http://tvoyweb.ru                                                       *
 * http://tvoyweb.ru/forums/                                               *
 * email: tvoyweb@tvoyweb.ru                                               *
 *                                                                         *
 ***************************************************************************/

global $lastmodifiedtime;

$lastmodifiedtime = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) ? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']):0;

$allforums = array_filter($fm->_Read(EXBB_DATA_FORUMS_LIST),"FilterForum");

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && count($allforums) === 0){
	header("HTTP/1.1 304 Not Modified");
    exit;
} else {
		$rss_data = '';
		uasort($allforums, 'sort_lastforum');
        $_RSS_lastposttime = time();

		if (count($allforums)) {
			reset($allforums);
			$_RSS_lastposttime	=  $allforums[key($allforums)]['last_time'];
			$num	= $fm->_Intval('num', 10);

			$alllist = array();
			
			foreach ($allforums as $forum_id => $forum) {
					$list		= array_filter($fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/list.php'),"newposts");
					$alllist	= array_merge ($alllist, $list);
					uasort($alllist, 'sort_by_lastpost');
					$alllist = array_slice($alllist,0,$num);
					unset($list);
			}

			$search = array('"./im/emoticons',
							'class="block"',
							'class="htmlcode"',
							'class="phpcode"',
							'class="quote"',
							'class="offtop"'
						);

			$replace = array('"'.$fm->exbb['boardurl'].'/im/emoticons',
							'style="margin-left: 20px;"',
							'style="width: 98%;background-color: #FAFCFE; border: 1px solid #000; padding: 4px;color: #00008B;font: 15px \'Courier New\';"',
							'style="width: 98%;background-color: #FAFCFE; border: 1px solid #000; padding: 4px;color: Teal;font: 15px \'Courier New\';"',
							'style="background-color: #FAFCFE; border: 1px solid #000;  padding: 4px; white-space:normal; font-family: Verdana, Arial; font-size: 11px; color: #465584;"',
							'style="background-color: #E4EAF2; border: 1px solid #ffffff; padding: 4px;font-family: Comic Sans MS;"'
						);


			foreach ($alllist as $topic) {
					$topicdata		= $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $topic['fid'].'/'.$topic['id'].'-thd.php');
					if (!isset($topic['postkey'])) continue;
					$post_id		= $topic['postkey'];
					$lastpost		= $topicdata[$post_id];
					$postlink		= $fm->exbb['boardurl'].'/topic.php?'.htmlentities('forum='.$topic['fid'].'&topic='.$topic['id'].'&postid='.$post_id.'#'.$post_id);
					$postlink1		= $fm->exbb['boardurl'].'/topic.php?'.htmlentities('forum='.$topic['fid'].'&topic='.$topic['id'].'&postid='.$post_id.'#'.$post_id);
					$post 			= str_replace($search, $replace, $fm->formatpost($lastpost['post'],$lastpost['html'],$lastpost['smiles']));
					$description	= "<![CDATA[<b>В форуме: ".$allforums[$topic['fid']]['name']."</b><br />Автор: ".$topic['poster']."<br />----------<br />".$post."]]>";
					$pubDate		= date("r",$topic['postdate']);
					unset($topicdata);

	$rss_data .= <<<RSSDATA
			<item>
				<title>Тема: {$topic['name']}</title>
				<link>{$postlink}</link>
				<description>{$description}</description>
				<pubDate>{$pubDate}</pubDate>
				<guid>{$postlink1}</guid>
			</item>
RSSDATA;
			}
		}
		$date	= date("r",$_RSS_lastposttime);
		$MyETag = '"RSS'.gmdate("YmdHis", $_RSS_lastposttime).'"';
		$MyGMTtime=gmdate("D, d M Y H:i:s", $_RSS_lastposttime)." GMT";

		header ('Content-Type: text/xml; charset=windows-1251');
		header("Last-Modified: ".$MyGMTtime);
		header("Etag: ".$MyETag);
echo <<<RSS
<?xml version="1.0" encoding="windows-1251"?>
<!-- generator="ExBB FeedCreator 1.0" -->
<rss version="2.0">
	<channel>
		<title>Последние сообщения на форуме {$fm->exbb['boardname']}</title>
		<link>{$fm->exbb['boardurl']}</link>
		<description>{$fm->exbb['boarddesc']}</description>
		<generator>ExBB Full Mods 0.1.5 FeedCreator 1.1</generator>
		<image>
			<url>{$fm->exbb['boardurl']}/im/logo_ExBB.gif</url>
			<link>{$fm->exbb['boardurl']}</link>
			<title>Последние сообщения на форуме</title>
		</image>
		<lastBuildDate>{$date}</lastBuildDate>
		$rss_data
	</channel>
</rss>
RSS;
		ob_end_flush();
		unset($GLOBALS['fm']);
		exit;
}

function FilterForum($var) {
		global $fm, $lastmodifiedtime;

        if ($var['stview'] == 'reged' && !$fm->user['id'] ||
		$var['stview'] == 'admo' && !($fm->user['status'] == 'ad' || $fm->user['status'] == 'sm' || isset($var['moderator'][$fm->user['id']])) ||
		$var['private'] && !($fm->user['status'] == 'ad' || isset($fm->user['private'][$var['id']]))) return 0;
		return ($var['last_time'] > $lastmodifiedtime) ? 1:0;
}

function newposts($var){
		global $lastmodifiedtime;
        if ($var['state'] === 'moved') return 0;
        return ($var['postdate'] > $lastmodifiedtime) ?1:0;
}

function sort_by_lastpost($a, $b) {
		if ($a['postdate'] == $b['postdate']) {
           return 0;
        }
        return ($a['postdate'] > $b['postdate']) ? -1 : 1;
}

function sort_lastforum($a, $b) {
		$a['last_time'] = isset($a['last_time']) ? $a['last_time']:0;
        $b['last_time'] = isset($b['last_time']) ? $b['last_time']:0;
        if ($a['last_time'] == $b['last_time']) {
           return 0;
        }
        return ($a['last_time'] > $b['last_time']) ? -1 : 1;
}
?>