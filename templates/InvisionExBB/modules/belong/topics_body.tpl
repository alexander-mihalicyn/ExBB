<?php
echo <<<DATA
            <br>
            <div id="navstrip" align="left">
                <img name="formtop" src="templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &raquo; {$topicsByUser}<br><br>
            </div>
            {$pages}<br><br>
            <table class="tableborder" cellpadding="4" cellspacing="1" width="100%">
                <tr>
                    <td class="maintitle" colspan="6"><img src="templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['SearchTotalTopics']} {$found}</td>
                </tr>
                <tr>
                    <th align="center" class="titlemedium"><img src="templates/InvisionExBB/im/spacer.gif" width="20" height="1" /></th>
                    <th width="47%" align="left" nowrap="nowrap" class="titlemedium">{$fm->LANG['Topics']}</th>
                    <th width="17%" align="enter" nowrap="nowrap" class="titlemedium">{$fm->LANG['Forum']}</th>
                    <th width="7%" align="center" nowrap="nowrap" class="titlemedium">{$fm->LANG['Replies']}</th>
                    <th width="7%" align="center" nowrap="nowrap" class="titlemedium">{$fm->LANG['BelongViews']}</th>
                    <th width="22%" align="left" nowrap="nowrap" class="titlemedium">{$fm->LANG['Updates']}</th>
                </tr>
                {$topicsData}
            </table><br>
            {$pages}
DATA;
?>