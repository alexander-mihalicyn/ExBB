<?php
$row_inexists =<<<INEXISTS
					<tr>
						<td class="pformleft"><b>� ����:</b></td>
						<td class="pformleft">
							<select name="newtopic" style="width:250px;">{jumptopichtml}</select>
						</td>
					</tr>
INEXISTS;
$row_innew =<<<INNEW
					<tr>
						<td class="pformleft"><b>�������� ����:</b></td>
						<td class="pformleft"><input type="text" name="topictitle" size="45" maxlength="255" style="width:400px" value="" /></td>
					</tr>
					<tr>
						<td class="pformleft"><b>�������� ����:</b></td>
						<td class="pformleft"><input type="text" name="description" size="45" maxlength="255" style="width:400px" value="" /></td>
					</tr>
INNEW;
?>
