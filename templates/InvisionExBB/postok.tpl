<?php
echo <<<DATA
			<br />
			<table width="100%" align="center">
				<tr>
					<td valign="middle">
						<table align="center" cellpadding="4" class="tablefill">
							<tr>
								<td width="100%" align="center">
									<b>{$ok_title}</b>
									<br />
									<br />
									{$lang['relocate']}
									<br />
								</td>
							</tr>
							<tr>
								<td width="100%" align="left">
									<ul>{$url1}{$url2}{$url3}</ul>
									<br />
									<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
DATA;
?>
