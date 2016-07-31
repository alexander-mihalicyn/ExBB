<?php
$banmembers_data .= <<<DATA
<tr align="center">
<td class="row2"><b><a href="profile.php?action=show&member={$user_id}"
					   title="{$fm->LANG['UserProfile']} {$user['user_name']}">{$user_name}</a></b></td>
<td class="row2">{$user['reason']}</td>
<td class="row2">{$user['date']}</td>
<td class="row2">{$user['days']}</td>
<td class="row2">{$user['end']}</td>
DATA;
if ( isset( $user['who_id'] ) ) {
$banmembers_data .= <<<DATA
<td class="row2"><b><a href="profile.php?action=show&member={$user['who_id']}"
					   title="{$fm->LANG['UserProfile']} {$user['who_name']}">{$user['who_name']}</a></b></td>
DATA;
} else {
$banmembers_data .= <<<DATA
<td class="row2"><b>N/A</b></td>
DATA;
}
$banmembers_data .= <<<DATA
<td class="row2">{$user['whounban']}</td>
</tr>
DATA;
