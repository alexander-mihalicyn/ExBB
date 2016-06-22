<?php
$postsData .= <<<DATA
                    <table class="topic">
                        <tr class="row4">
                            <td style="padding-top: 5px; padding-bottom: 5px;" class="normalname">{$username}</td>
                            <td>{$fm->LANG['PostDate']} {$postdate} &bull; {$fm->LANG['BelongTopic']} <b>{$topicname}</b> &bull; {$fm->LANG['Forum']}: <b>{$forumname}</b></td>
                        </tr>
                        <tr class="post2">
                            <td valign="top"><br>{$fm->LANG['Replies']}: <b>{$replies}</b><br>{$fm->LANG['BelongViews']}: <b>{$views}</b><br>
                            <img src="templates/InvisionExBB/im/spacer.gif" width="160" height="1" /></td>
                            <td class="postcolor">{$postText}</td>
                        </tr>
                    </table>
                    <div class="delemiter"></div>
DATA;
?>