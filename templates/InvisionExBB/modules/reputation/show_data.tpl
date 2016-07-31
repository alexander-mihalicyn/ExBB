<?php
$rep_class = (empty($rep_class) || $rep_class == 'row2') ? 'row1' : 'row2';
$rep_data .= '<tr style="padding: 4px; font-weight: normal;">
					<td align="center" class="'.$rep_class.'">'.$rep_did.'</td>
                    <td align="center" class="'.$rep_class.'">'.$rep_who.'</td>
                    <td align="center" class="'.$rep_class.'">'.$rep_when.'</td>
                    <td align="center" class="'.$rep_class.'">'.$rep_forpost.'</td>
                    <td class="'.$rep_class.'">'.$rep_reason.'</td>
                </tr>';
