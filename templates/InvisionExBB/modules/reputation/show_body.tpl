<?php
echo <<<DATA
            <br>
            <div id="navstrip" align="left">
                <img name="formtop" src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &raquo; {$rep_history}<br><br>
            </div>
				{$pages}<br><br>
				<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
                    <tr>
                          <td class="maintitle" colspan="5"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$rep_stat}</td>
                    </tr>
                    <tr>
					<th align="center" width="4%" class="titlemedium"></th>
                    <th align="center" width="14%" class="titlemedium">{$fm->LANG['RepWho']}</th>
                   <th align="center" width="14%" class="titlemedium">{$fm->LANG['RepWhen']}</th>
                   <th align="center" width="34%" class="titlemedium">{$fm->LANG['RepForPost']}</th>
                   <th align="center" width="34%" class="titlemedium">{$fm->LANG['RepReason']}</th>
                    </tr>
{$rep_data}
                    </table><br>
					{$pages}
DATA;
?>
