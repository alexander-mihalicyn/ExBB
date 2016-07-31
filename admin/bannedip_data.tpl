<?php
$ipdata .=<<<DATA
				<tr class="gen">
					<td class="row2" width="40%"><span class="cattitle">{$info['ipb']}</span></td>
					<td class="row2" width="60%"><span class="cattitle">{$info['ipbd']}</span</td>
					<td class="row2" width="40%"><span class="cattitle"><a href="setbannedip.php?action=modify&amp;id={$id}">{$fm->LANG['Change']}</a></span</td>
					<td class="row2" width="60%"><span class="cattitle"><a href="setbannedip.php?action=delet&amp;id={$id}">{$fm->LANG['Delete']}</a></span</td>
				</tr>
DATA;
