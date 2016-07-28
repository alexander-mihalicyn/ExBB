<?php

/*
	Reputation Mod for ExBB FM 1.0 RC1
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) die('Emo sucks;)');

$fm->_LoadModuleLang('reputation');

define('CONFIG', 'modules/reputation/data/config.php');

switch ($fm->_String('do')) {

	case 'down':	
	case 'up':		change_rep();
						break;
	case 'show':	show_rep();
						break;
	default:		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
}

// Функция изменения репутации
function change_rep() {
	global $fm;
	
	// Проверка на авторизацию
	if (!$fm->user['id']) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepGuest']);
	
	// Читаем конфиг (^__^)
	$config = $fm->_Read(CONFIG);
	
	// Проверка на запрет админа ;)
	if ($config['denied']) {
		$blacklist = array_map(array($fm, '_LowerCase'), $config['blacklist']);
		if (in_array($fm->_LowerCase($fm->user['name']), $blacklist))
			$fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepDenied']);
	}
	
	// Проверка на наличие необходимого кол-ва сообщений
	if ($fm->user['posts'] < $config['msg'])
		$fm->_Message($fm->LANG['Reputation'], sprintf($fm->LANG['RepMsg'], $config['msg']));
	
	// Проверка по времени последнего изменения репутации
	if (isset($fm->user['rep_time']) && $fm->_Nowtime - $fm->user['rep_time'] < $config['wait_days'] * 86400 + $config['wait_hours'] * 3600 + $config['wait_minutes'] * 60)
		$fm->_Message($fm->LANG['Reputation'], sprintf($fm->LANG['RepWait'], (($config['wait_days']) ? $config['wait_days'].' '.$fm->LANG['RepDays'].' ' : '').
			(($config['wait_hours']) ? $config['wait_hours'].' '.$fm->LANG['RepHours'].' ' : '').
			(($config['wait_minutes']) ? $config['wait_minutes'].' '.$fm->LANG['RepMinutes'] : '')));
			
	// Не находится ли сообщение, за которое мы будем изменять репутацию пользователю, в приватном форуме?
	// Если да, то проверим есть ли у нас доступ в этот форум
	// Также проверим, не доступен ли этот форум только администраторам и модераторам
	$fm->_Intvals(array('forum', 'topic', 'post'));
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (!isset($allforums[$fm->input['forum']])) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepNoMsg']);
	if (!empty($allforums[$fm->input['forum']]['private']) && empty($fm->user['private'][$fm->input['forum']]) && !defined('IS_ADMIN'))
		$fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepMsgPrivate']);
	if ($allforums[$fm->input['forum']]['stview'] == 'admo' && !defined('IS_ADMIN') && $fm->user['status'] != 'sm' && !isset($allforums[$fm->input['forum']]['moderator'][$fm->user['id']]))
		$fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepNoAllows']);
	
	// Ищем сообщение, за которое собираемся изменять репутацию пользователю
	$thread = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $fm->input['forum'].'/'.$fm->input['topic'].'-thd.php');
	if (!isset($thread[$fm->input['post']])) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepNoMsg']);
	
	// Получаем id пользователя, которому собираемся изменять репутацию
	// Проверим id на попытку изменить репутацию гостю или самому себе
	$member = $thread[$fm->input['post']]['p_id'];
	if (!$member) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepTryGuest']);
	if ($member == $fm->user['id']) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepTrySelf']);
	
	// Проверим пользователя на существование, прочитаем профиль пользователя ;)
	$member = $fm->_Getmember($member);
	if (!$member) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepNoMember']);
	
	// Прочитаем историю изменения репутации пользователя и проверим, не пытаемся ли мы накрутить ему репутацию ;)
	// Также проверим, не изменяли ли мы уже репутацию пользователю за это сообщение
	$rep = $fm->_Read('modules/reputation/data/'.$member['id'].'.php');
	foreach ($rep as $time => $info) {
		if ($fm->user['id'] == $info['who'] && $fm->_Nowtime - $time < $config['protect_days'] * 86400 + $config['protect_hours'] * 3600 + $config['protect_minutes'] * 60)
			$fm->_Message($fm->LANG['Reputation'], sprintf($fm->LANG['RepProtect'], (($config['protect_days']) ? $config['protect_days'].' '.$fm->LANG['RepDays'].' ' : '').
			(($config['protect_hours']) ? $config['protect_hours'].' '.$fm->LANG['RepHours'].' ' : '').
			(($config['protect_minutes']) ? $config['protect_minutes'].' '.$fm->LANG['RepMinutes'] : '')));
		if ($fm->user['id'] == $info['who'] && $fm->input['forum'] == $info['forum'] && $fm->input['topic'] == $info['topic'] && $fm->input['post'] == $info['post'])
			$fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepAgain']);
	}
	
	if (($size = strlen($fm->_String('reason'))) < $config['size_min'] || $size > $config['size_max'] || $fm->_POST !== TRUE) {
		// Отображаем форму изменения репутации
		$rep_change	= sprintf($fm->LANG['RepChange'], $member['name']);
		$fm->_Title	= ' :: '.$rep_change;
		$rep_action	= ($fm->input['do'] == 'down') ? $fm->LANG['RepDoDown'] : $fm->LANG['RepDoUp'];
		$min		= $config['size_min'];
		$max		= $config['size_max'];
		$fm->LANG['RepReasonDesc']	= sprintf($fm->LANG['RepReasonDesc'], $min, $max);
		$fm->LANG['RepSizeAlert']	= sprintf($fm->LANG['RepSizeAlert'], $min, $max);
		
		include('./templates/'.DEF_SKIN.'/all_header.tpl');
		include('./templates/'.DEF_SKIN.'/logos.tpl');
		include('./templates/'.DEF_SKIN.'/modules/reputation/change.tpl');
		include('./templates/'.DEF_SKIN.'/footer.tpl');
	}
	else {
		// Изменим численное значение репутации в файле пользователя
		$member = $fm->_Read2Write($fp_member, 'members/'.$member['id'].'.php');
		if (!isset($member['reputation'])) $member['reputation'] = 0;
		if ($fm->input['do'] == 'down') $member['reputation']--;
		else $member['reputation']++;
		$fm->_Write($fp_member, $member);
		
		// Добавим запись об изменении репутации в историю изменения репутации пользователя
		$rep = $fm->_Read2Write($fp_rep, 'modules/reputation/data/'.$member['id'].'.php');
		$time = $fm->_Nowtime;
		while (isset($rep[$time])) $time++;
		$rep[$time] = array(
			'who'		=> $fm->user['id'],
			'change'	=> $fm->input['do'],
			'forum'		=> $fm->input['forum'],
			'topic'		=> $fm->input['topic'],
			'post'		=> $fm->input['post'],
			'reason'	=> trim($fm->input['reason'])
		);
		krsort($rep);
		$fm->_Write($fp_rep, $rep);
		
		// Обновим время изменения репутации для изменяющего пользователя
		$name = $member['name'];
		$member = $fm->_Read2Write($fp_member, 'members/'.$fm->user['id'].'.php');
		$member['rep_time'] = $time;
		$fm->_Write($fp_member, $member);
		
		// Всё! ;)
		$fm->_Message($fm->LANG['Reputation'], sprintf($fm->LANG['RepOk'], $name),
			'topic.php?forum='.$fm->input['forum'].'&topic='.$fm->input['topic'].'&postid='.$fm->input['post'].'#'.$fm->input['post']);
	}
}

// Функция просмотра истории изменения репутации пользователей
function show_rep() {
	global $fm;
	
	// Читаем конфиг и список форумов, а также узнаем, являемся ли мы модератором в одном из них :)
	$config = $fm->_Read(CONFIG);
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (!defined('IS_ADMIN') && $fm->user['status'] != 'sm')
		foreach ($allforums as $forum)
			$fm->_GetModerators($forum['id'], $allforums);
	
	// Проверяем разрешение гостям просматривать историю изменения репутации
	if ($config['guest'] && !$fm->user['id'])
		$fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepGuestDenied']);
	
	// Проверим существование пользователя, заодно прочитаем его профиль
	$fm->_Intval('member');
	if (!file_exists('members/'.$fm->input['member'].'.php')) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepNoMember']);
	
	// Читаем историю изменения репутации, при её отсутствии выводим сообщение об ошибке
	$rep = $fm->_Read('modules/reputation/data/'.$fm->input['member'].'.php');
	if (!$rep) $fm->_Message($fm->LANG['Reputation'], $fm->LANG['RepEmpty']);
	krsort($rep);
	
	// Посчитаем сколько всего было положительных и отрицательных изменений
	$down = $up = 0;
	foreach ($rep as $time => $info)
		if ($info['change'] == 'down') $down++;
		else $up++;
	$total = $down + $up;
	
	// Прочитаем профиль пользователя и выполним синхронизацию его репутации при необходимости
	$member = $fm->_Read2Write($fp_member, 'members/'.$fm->input['member'].'.php');
	if ($member['reputation'] != $up - $down) {
		$member['reputation'] = $up - $down;
		$fm->_Write($fp_member, $member);
	}
	else $fm->_Fclose($fp_member);
	
	// Сформируем список записей
	$rep_keys = array_keys($rep);
	$pages = Print_Paginator($total, 'tools.php?action=reputation&do=show&member='.$member['id'].'&p={_P_}',
		$config['per_page'], 8, $first, TRUE);
	$rep_keys = array_slice($rep_keys, $first, $config['per_page']);
	
	$members = $topics = array();
	$rep_data = '';
	foreach ($rep_keys as $time) {
		$rep_row = $rep[$time];
		$id_topic = $rep_row['forum'].':'.$rep_row['topic'];
		
		// Получаем информацию о пользователях, изменявших репутацию
		if (!isset($members[$rep_row['who']]) && ($_member = $fm->_Getmember($rep_row['who'])))
			$members[$rep_row['who']] = array(
				'name'		=> $_member['name'],
				'status'	=> $_member['status']
			);
		
		// Получаем информацию о сообщениях, за которые пользователю изменили репутацию
		if (!isset($topics[$id_topic]) && ($_thread = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $rep_row['forum'].'/'.$rep_row['topic'].'-thd.php'))) {
			$_keys = array_keys($_thread);
			$firstkey = reset($_keys);
			$topics[$id_topic] = array(
				'name'	=> $_thread[$firstkey]['name'],
				'posts'	=> $_keys
			);
		}
		
		// Заполняем колонку с картинкой, обозначающей понижение / повышение репутации
		$rep_did = ($rep_row['change'] == 'down') ? $fm->LANG['RepDidDown'] : $fm->LANG['RepDidUp'];
		
		// Заполняем колонку пользователя
		if (isset($members[$rep_row['who']])) {
			switch ($members[$rep_row['who']]['status']) {
				case 'ad':	$class = ' class="admin"';
								break;
				case 'sm':	$class = ' class="supmoder"';
								break;
				default:	$class = '';
			}
			$rep_who = '<a href="profile.php?action=show&member='.$rep_row['who'].'"'.$class.'>'.$members[$rep_row['who']]['name'].'</a>';
		}
		else $rep_who = $fm->LANG['RepMemberDeleted'];
		
		// Заполняем колонку даты
		$rep_when = date('d.m.Y, H:i', $time + $fm->user['timedif'] * 3600);
		
		// Заполняем колонку ссылки на сообщение, за которое пользователю изменили репутацию
		if (!isset($topics[$id_topic]) || !in_array($rep_row['post'], $topics[$id_topic]['posts']))
			$rep_forpost = $fm->LANG['RepPostMovedDeleted'];
		elseif (!empty($allforums[$rep_row['forum']]['private']) && empty($fm->user['private'][$rep_row['forum']]) && !defined('IS_ADMIN'))
			$rep_forpost = $fm->LANG['RepTopicPrivate'];
		elseif (!$fm->user['id'] && $allforums[$rep_row['forum']]['stview'] == 'reged' || !defined('IS_ADMIN') && $fm->user['status'] != 'sm' && empty($fm->_Moderator) && $allforums[$rep_row['forum']]['stview'] == 'admo')
			$rep_forpost = $fm->LANG['RepNoPerms'];
		else
			$rep_forpost = '<a href="topic.php?forum='.$rep_row['forum'].'&topic='.$rep_row['topic'].'&postid='.$rep_row['post'].'#'.$rep_row['post'].'">'.$topics[$id_topic]['name'].'</a>'
			.' &mdash; <a href="forums.php?forum='.$rep_row['forum'].'">'.$allforums[$rep_row['forum']]['name'].'</a>';
		
		// Заполняем колонку причины
		$rep_reason = nl2br($fm->chunk_split($rep_row['reason']));
		
		include('templates/'.DEF_SKIN.'/modules/reputation/show_data.tpl');
	}
	
	// Выводим историю изменения репутации ;) Всё!
	$rep_history = sprintf($fm->LANG['RepHistory'], $member['name']);
	$fm->_Title = ' :: '.$rep_history;
	$rep_stat = sprintf($fm->LANG['RepStat'], $member['name'], $up - $down, $total, $up, $down);
	include('./templates/'.DEF_SKIN.'/all_header.tpl');
	include('./templates/'.DEF_SKIN.'/logos.tpl');
	include('./templates/'.DEF_SKIN.'/modules/reputation/show_body.tpl');
	include('./templates/'.DEF_SKIN.'/footer.tpl');
}

?>