<?php
$datashow .= <<<DATA
					<tr class="genmed">
						<td class="{$back_clr}">{$code}</td>
						<td class="{$back_clr}"><img src="./im/emoticons/{$data['img']}" alt="{$code}" /></td>
						<td class="{$back_clr}">{$data['emt']}</td>
						<td class="{$back_clr}"><a href="setsmiles.php?action=edit&amp;id={$data['id']}&amp;cat={$curcatid}">{$fm->LANG['Change']}</a></td>
						<td class="{$back_clr}"><a href="setsmiles.php?action=delete&amp;id={$data['id']}&amp;cat={$curcatid}">{$fm->LANG['Delete']}</a></td>
					</tr>
DATA;
?>