<?php
$print_data .= <<<DATA
		<div class="blok">
			<div class="title">
				{$first}. <span>{$autorname}</span>{$date} - <a href="topic.php?forum={$forum_id}&topic={$topic_id}&postid={$key}#{$key}"><b>перейти к сообщению</b></a>
			</div>
			<div class="text">
				{$post}
			</div>
		</div>
DATA;
