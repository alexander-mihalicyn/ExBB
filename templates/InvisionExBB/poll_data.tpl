<?php
$pollch .= <<<POLL
					<tr align="left" class="tablepad">
						<td><span class="moder">{$ptext}</span></td>
						<td><img src="./templates/InvisionExBB/im/bar_left.gif" width="6" alt="" height="12" hspace="0" /><img src="./templates/InvisionExBB/im/bar.gif" width="{$width}" height="12" alt="{$percent}" hspace="0" /><img src="./templates/InvisionExBB/im/bar_right.gif" width="6" alt="" height="12" hspace="0" /></td>
						<td><b><span class="moder">&nbsp;{$percent}&nbsp;</span></b></td>
						<td><span class="moder">[ {$votes} ]</span></td>
					</tr>
POLL;
