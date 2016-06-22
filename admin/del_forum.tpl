<?php
echo <<<DATA
			<form action="setforums.php" method="post">
				<input type="hidden" name="action" value="$action">
				<input type="hidden" name="forum" value="$forum_id">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead">{$TableTitle}</th>
					</tr>
					<tr class="gen" align="center">
						<td class="row1">
							{$RequestText}
						</td>
					</tr>
					<tr>
						<td class="catBottom" align="center">
							<input type="submit" name="submit" value="{$ButtonValue}" class="mainoption" />
						</td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
?>
