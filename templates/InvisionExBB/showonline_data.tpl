<?php
$output .= <<<DATA
				<tr class="row4" style="height:28px">
					<td align="left">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<b>{$online['n']}</b>
						{$bot}
						&nbsp;&nbsp;&nbsp;
						{$online['ip']}
					</td>
					<td align="center">{$actdate}</td>
					<td align="center">{$online['in']}</td>
				</tr>
DATA;
