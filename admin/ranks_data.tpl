<?php
$ranksdata .= <<<DATA
					<tr class="genmed">
						<td class="{$back_clr}">{$rank['title']}</td>
						<td class="{$back_clr}" align="center">{$rank['posts']}</td>
						<td class="{$back_clr}"><a href="setranks.php?action=edit&amp;id={$id}">{$fm->LANG['Change']}</a></td>
						<td class="{$back_clr}"><a href="setranks.php?action=delete&amp;id={$id}">{$fm->LANG['Delete']}</a></td>
					</tr>
DATA;
