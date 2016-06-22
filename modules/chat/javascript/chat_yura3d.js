/*
	Chat for ExBB FM 1.0 RC2
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

var messages, msg, now, online, tmp, last, scroll, scrolling, scroll_px, last_top, last_name;

function start_chat() {
	messages	= document.getElementById('messages');
	msg			= document.getElementById('msg');
	now			= document.getElementById('now');
	online		= document.getElementById('online');
	
	tmp			= document.createElement('div');
	
	last		= '';
	
	msg.focus();
	
	messages.innerHTML	= '';
	scroll 				= 1;
	scrolling			= 0;
	scroll_px			= 3;
	start				= 1;
	
	last_name			= '';
	
	for (var i in smiles)
		smiles[i][0] = new RegExp(smiles[i][0].replace(/([\:\;\)\(\|\.\]\[\<\>\'\"\0\*])/g, '\\$1'), 'g');
	
	update_chat();
}

function update_chat() {
	JsHttpRequest.query('jsloader.php?loader=chat', {action: 'update', last: last}, update, true);
	
	setTimeout(update_chat, chat.update * 1000);
}

function update(data, text) {
	if (data.last)
		last = data.last;
	
	if (data.now != '0') {
		now.innerHTML		= data.now;
		online.innerHTML	= data.online;
	}
	
	var height = messages.scrollHeight;
	var end = height - messages.scrollTop;
	
	tmp.innerHTML = data.messages;
	
	var span = tmp.getElementsByTagName('span'), i, j;
	for (i = 0; i < span.length; i++) {
		if (span[i].id.indexOf('month') == 0) {
			var month = span[i].id.match(/month(\d+)/i);
			
			if (month.length == 2) {
				span[i].innerHTML = LANG.Month[month[1]];
				
				continue;
			}
		}
		
		switch (span[i].id) {
			case 'login':	span[i].innerHTML = LANG.ActLogin + ' ' + span[i].innerHTML;
								break;
			case 'logout':	span[i].innerHTML += ' ' + LANG.ActLogout;
								break;
			case 'msg':		for (j = 0; j < smiles.length; j++)
								span[i].innerHTML = span[i].innerHTML.replace(smiles[j][0], '<img src="im/emoticons/' + smiles[j][1] + '">');
								break;
		}
	}
	
	messages.innerHTML += tmp.innerHTML;
	
	last_top = messages.scrollTop;
	
	if (start || !scroll && (isOpera && (end == chat.height || height <= 300 && messages.scrollHeight > 300) ||
	!isOpera && (end == chat.height + 10 || height <= 310 && messages.scrollHeight > 310))) {
		scroll += messages.scrollHeight - messages.scrollTop - end;
		
		if (!scrolling) {
			scrolling	= 1;
			start		= 0;
			
			scroller();
		}
	}
}

function send_msg() {
	if (msg.value !== '')
		JsHttpRequest.query('jsloader.php?loader=chat', {action: 'send', msg: msg.value}, update, true);
	
	if (msg.value.indexOf(last_name) != -1)
		msg.value = last_name;
	else
		msg.value = '';
}

function scroller() {
	if (scroll && last_top == messages.scrollTop) {
		last_top = messages.scrollTop += scroll_px;
		
		scroll -= scroll_px;
	}
	else {
		scroll = scrolling = 0;
		scroll_px = 1;
		
		return;
	}
	
	setTimeout(scroller, chat.scroll);
}

function pasteN(name) {
	msg.value += last_name = name + ': ';
	
	msg.focus();
	
	return false;
}

function pasteS(smile) {
	msg.value += smile;
	
	msg.focus();
	
	return false;
}