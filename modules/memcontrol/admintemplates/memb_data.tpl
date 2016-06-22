<?php
$memb_data .= <<<DATA
				<tr class="{$class}" align="center" valign="middle">
          			<td class="gen"><a href="setmembers.php?action=edit_user&userid={$user_id}" class="nav">{$name}</a></td>
          			<td class="gen">{$status}</td>
					<td class="gen">{$email}</td>
					<td class="gen">{$location}</td>
					<td class="gen">{$joined}</td>
					<td class="gen">{$posts}</td>
					<td><input name="del[{$user_id}]" type="checkbox" value="{$user_id}"></td>
				</tr>
DATA;
?>
