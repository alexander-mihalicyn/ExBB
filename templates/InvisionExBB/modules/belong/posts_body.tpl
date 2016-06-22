<?php
echo <<<DATA
            <br>
            <div id="navstrip" align="left">
                <img name="formtop" src="templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &raquo; {$postsByUser}<br><br>
            </div>
            {$pages}<br><br>
            <table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
                <tr>
                    <td class="maintitle" colspan="2"><img src="templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['BelongPostsFound']} {$found}</td>
                </tr>
                <tr>
                    <td>{$postsData}</td>
                </tr>
            </table><br>
            {$pages}
DATA;
?>