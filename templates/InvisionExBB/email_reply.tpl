<?php
$email = <<<DATA
Внимание! Это письмо сгенерировано роботом, на него отвечать не надо!
Вы создали новую тему на форуме. Спасибо!{$addfield}
>---------------------------------------------------------------------
Автор: {$inuser['name']}
Дата: {$time}
Текст сообщения:
>------------------------------------------
{$vars['inpost']}
>------------------------------------------
{$exbb['boardurl']}/{$relocurl}
----------------------------------------------------------------------
Вы получили это письмо, т.к подписаны на получение ответов по e-mail в теме на форуме {$forumname} сайта {$exbb['boardname']}
Перестать следить за ответами Вы можете здесь:
{$exbb['boardurl']}/topic.php?action=untrack&forum={$inforum}&topic={$intopic}
DATA;
