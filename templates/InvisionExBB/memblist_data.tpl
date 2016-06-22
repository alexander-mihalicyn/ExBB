<?php
$members_data .= <<<DATA
				<tr onmouseover="className='class_over'" onmouseout="className='class_out'" class="row4" align="center" valign="middle">
					<td style="padding:5px;margin-top:1px"><b><a href="profile.php?action=show&member={$user_id}" title="{$fm->LANG['UserProfile']}{$user['name']}"><img src="im/avatars/{$user['avatar']}"><br />{$user['name']}</a></b></td>
					<td>{$user['title']}</td>
					<td>{$user['posts']}</td>
					<td>{$user['joined']}</td>
					<td>{$user['location']}</td>
					<td>{$user['mail']}</td>
					<td>{$user['www']}</td>
					<td>{$user['icq']}</td>
				</tr>
DATA;
?>
