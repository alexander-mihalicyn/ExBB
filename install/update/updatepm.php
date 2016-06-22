<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$d = dir($_ForumRoot.'_messages/');
while (false !== ($file = $d->read())) {
	if (preg_match("#^\d{1,}-(msg|out)\.php$#is",$file, $match)) {
		$allmessages = $fm->_Read($_ForumRoot.'_messages/'.$file);
		foreach ($allmessages as $id => $info) {
				if ($match[1] == 'msg') {
					if (isset($info['ymail']) && $info['ymail'] == 'yes' && isset($info['fmail']) && $info['fmail'] != '') {
						$allmessages[$id]['mail'] = $info['fmail'];
					} elseif (!isset($info['mail']))  {
							$allmessages[$id]['mail'] = FALSE;
					}
					if (isset($allmessages[$id]['ymail'])) unset($allmessages[$id]['ymail']);
					if (isset($allmessages[$id]['fmail'])) unset($allmessages[$id]['fmail']);
				}
				$allmessages[$id]['title'] = htmlspecialchars(pre_replace($info['title']),ENT_QUOTES);
				$allmessages[$id]['msg'] = htmlspecialchars(pre_replace($info['msg']),ENT_QUOTES);
		}
		$fm->_Read2Write($fp_inbox,$_ForumRoot.'messages/'.$file);
		$fm->_Write($fp_inbox,$allmessages);
		if (count($allmessages) == 0) unlink($_ForumRoot.'messages/'.$file);
	}
}
$d->close();
$warning = '<div class="ok">'.$lang['NoError'].'Данные о Личных сообщениях пользователей форума успешно обновлены!</div>';
$action = 'updatestat';
?>