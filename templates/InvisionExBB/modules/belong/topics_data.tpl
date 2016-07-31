<?php
$topicsData .= <<<DATA
                <tr>
                    <td align="center" class="row4">{$topicicon}</td>
                    <td class="row4"><b>{$topicname}</b><br>{$topicdesc}</td>
                    <td align="center" class="row2">{$forumname}</td>
                    <td align="center" class="row2">{$replies}</td>
                    <td align="center" class="row2">{$views}</td>
                    <td class="row2"><span class="desc">{$postdate}<br>{$fm->LANG['Author']}: <b>{$poster}</b></span></td>
                </tr>
DATA;
