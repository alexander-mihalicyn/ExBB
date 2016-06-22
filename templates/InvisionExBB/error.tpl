<?php
echo <<<DATA
			<table cellspacing="0" cellpadding="0" border="0" height="85%" width="100%">
				<tr>
					<td valign="middle">
						<table align="center" cellpadding="4" class="tablefill" width="70%">
							<tr>
								<td width="100%" align="center" height="40%">
									<b>{$msg_title}</b>
									<br />
									<br />
									<div align="left">
										<ul>
											<li><b>{$msg_text}</b></li>
										</ul>
										<br />
										<br />
										<center>{$return}</center>
									</div>
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
