<?php
$select_birstday =<<<BIRSTDAY
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['BirstDate']}</b><br /><span class="desc">{$fm->LANG['BirstDateDesc']}</span></td>
						<td class="profilright">
							<select name="d">
								{$dayselecthtml}
							</select>
							<select name="m">
								{$monthselecthtml}
							</select>
							<select name="y">
								{$yearselecthtml}
							</select>
						</td>
					</tr>
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['ShowYear']}</b><br /><span class="desc">{$fm->LANG['ShowYearDesc']}</span></td>
						<td class="profilright"><input class="tab" type="radio" name="showyear" value="yes" {$show_yes} />{$fm->LANG['Yes']}&nbsp;&nbsp;<input class="tab" type="radio" name="showyear" value="no" {$show_no} />{$fm->LANG['No']}</td>
					</tr>
BIRSTDAY;
