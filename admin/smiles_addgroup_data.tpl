<?php
$datashow .= <<<DATA
        <tr class="genmed">
                <td class="row1">
                	<select name="smile[$a][catid]">
						{$smoption}
					</select>
                </td>
                <td class="row1">
                	<input name="smile[$a][code]" type="text" value="$code">
                </td>
                <td class="row1" align="center">
                	<input name="smile[$a][file]" type="hidden" value="$file">
                	<img src="{$olddir}{$file}" alt="$code" />
                </td>
                <td class="row1">
                	<input name="smile[$a][desc]" type="text" value="$desc">
                </td>
        </tr>
DATA;
