<?php
$show_belong = <<<DATA
                <tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['BelongModuleTitle']}</b></td>
					<td class="pformright"><a href="tools.php?action=belong&to={$user_id}&what=topics">{$fm->LANG['BelongFindAllTopics']}</a> &bull; <a href="tools.php?action=belong&to={$user_id}">{$fm->LANG['BelongFindAllPosts']}</a></td>
				</tr>
DATA;
?>