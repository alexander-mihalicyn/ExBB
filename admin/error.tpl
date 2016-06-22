<?php
echo <<<DATA
<table class="forumline" width="100%" cellspacing="1" cellpadding="4" border="0">
	<tr>
		<td>
			<table width="100%" cellspacing="0" cellpadding="1" border="0">
				<tr>
					<th width="100%" align="center" class="thTop" nowrap="nowrap">{$msg_title}</td>
				</tr>
				<tr>
					<td align="left">
						<span class="gen">
							<ul>
								<li><b>{$msg_text}</b></li>
							</ul>
							<br />
							<center>{$return}</center>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br clear="all" />
DATA;
?>
