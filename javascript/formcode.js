var txt = '';
var LeftText = '';
var RightText = '';
var selStart;

function bbcode() {
var L = '';
var R = '';
var a = bbcode.arguments;
a[1] = (a[1]) ? a[1]:'';
var code = (a[0] != 0) ? a[0].name:"smile";
var replacement = false; // Флаг полной перезаписи конструкции бб-кода, включая выделенный фрагмент
var pos_to_end = (a[1]) ? true : false; // Флаг позиционирования курсора за закрывающим бб-кодом

switch(code) {
case 'quote': copyQ();
var range = get_range();
if (txt == '' && a[1]) {
alert('Для вставки цитаты надо выделить текст!');
return;
};
var replace = (a[1] != '') ? '='+a[1]:'';
L = bbtags[code][0].replace(/%/, replace) + ((range == '') ? txt : '');
R = bbtags[code][1];

if (txt != '')
pos_to_end = true;
break;
case 'color':
case 'size': L = bbtags[code][0].replace(/%/, a[1]);
R = bbtags[code][1];
break;
case 'smile': L = ' ' +a[1]+ ' ';
break;
case 'spoiler': var title = prompt(bblang.SpoilerTitle, '');
if (title != null) {
title = title.replace(/[\[\]]/g, '');
L = bbtags[code][0].replace(/%/, (title != '') ? '=' + title : '');
R = bbtags[code][1];
}
break;
case 'hide': var msgs = prompt(bblang.HideMsgs, '');
if (msgs != null) {
if (isNaN(msgs = parseInt(msgs)) || msgs < 1)
msgs = '';
L = bbtags[code][0].replace(/%/, (msgs != '') ? '=' + msgs : '');
R = bbtags[code][1];
}
break;
case 'keyboard': L = a[1];
break;
case 'url': var range = get_range(), link, text;

if (range != '') {
var link_prefixes = ['http://', 'https://', 'ftp://', 'callto://', 'www.'];
var is_href = false, key;

for (key in link_prefixes)
if (link_prefixes[key] == range.substring(0, link_prefixes[key].length)) {
is_href = true;

break;
}

if (is_href) {
link = range;
text = prompt(bblang.LinkText, '');
if (text == null)
return false;

L = bbtags[code][0].replace(/%/, (text != '') ? '=' + link : '') + ((text != '') ? text : link);
R = bbtags[code][1];

replacement = true; // Нужно перезаписать в том числе выделенный фрагмент
pos_to_end = true;
}
else {
text = range;
link = prompt(bblang.LinkHref, 'http://');
if (link == null)
return false;

L = bbtags[code][0].replace(/%/, '=' + link);
R = bbtags[code][1];
}
}
else {
link = prompt(bblang.LinkHref, 'http://');
if (link == null)
return false;

text = prompt(bblang.LinkText, '');
if (text == null)
return false;

L = bbtags[code][0].replace(/%/, (text != '') ? '=' + link : '') + ((text != '') ? text : link);
R = bbtags[code][1];

pos_to_end = true;
}
break;
case 'img': var range = get_range(), link;
if (range == '') {
link = prompt(bblang.ImgHref, 'http://');

if (link == null)
return false;

pos_to_end = true;
}
L = bbtags[code][0] + ((range == '') ? link : '');
R = bbtags[code][1];
break;

case 'youtube': var range = get_range(), link = '', title = '';
if (range == '' || range.substring(0, 19) != 'http://youtube.com/') {
title = range;
link = prompt(bblang.YouTubeHref, 'http://youtube.com/');

if (link == null)
return false;
}
if (range == '' || title == '') {
if (link == '')
link = range;
title = prompt(bblang.YouTubeTitle, '');

if (title == null)
return false;
}

L = bbtags[code][0].replace(/%/, (title != '') ? '=' + title : '') + link;
R = bbtags[code][1];

replacement = true; // Нужно перезаписать в том числе выделенный фрагмент
pos_to_end = true;
break;

case 'rutube': var range = get_range(), link = '', title = '';
if (range == '' || range.substring(0, 19) != 'http://rutube.com/') {
title = range;
link = prompt(bblang.RuTubeHref, 'http://rutube.com/');

if (link == null)
return false;
}
if (range == '' || title == '') {
if (link == '')
link = range;
title = prompt(bblang.RuTubeTitle, '');

if (title == null)
return false;
}

L = bbtags[code][0].replace(/%/, (title != '') ? '=' + title : '') + link;
R = bbtags[code][1];

replacement = true; // Нужно перезаписать в том числе выделенный фрагмент
pos_to_end = true;
break;

L = bbtags[code][0].replace(/%/, (title != '') ? '=' + title : '') + link;
R = bbtags[code][1];

replacement = true; // Нужно перезаписать в том числе выделенный фрагмент
pos_to_end = true;
break;

case 'vkvideo': var range = get_range(), link = '', title = '1';
if (range == '' || range.substring(0, 19) != '') {
title = range;
link = prompt(bblang.VkHref, 'вставьте сюда код видео');

if (link == null)
return false;
}
if (range == '' || title == '') {
if (link == '')
link = range;
title = prompt(bblang.VkTitle, '');

if (title == null)
return false;
}
L = bbtags[code][0].replace(/%/, (title != '') ? '=' + title : '') + link;
R = bbtags[code][1];

replacement = true; // Нужно перезаписать в том числе выделенный фрагмент
pos_to_end = true;
break;

default: L = bbtags[code][0] + a[1];
 R = bbtags[code][1] + ((a[1] != '') ? ' , ' : '');
 break;
}

SelectedText = get_range();
if (isMSIE) {
var caret = TextArea.caretPos;
var surround = caret.text;

TextArea.caretPos.text = L + ((!replacement) ? TextArea.caretPos.text : '') + R;

if (surround == '' && pos_to_end)
caret.moveStart('character', (!pos_to_end) ? -R.length : 0);
else {
var fixcr = surround.match(/\r/g); // IE не учитывает \r (или трактует \r\n как единое целое, вообщем хз) при смещении границ каретки
if (fixcr != null)
fixcr = fixcr.length; // Поправка, чтобы избежать слева выход за предел из-за неучтённых \r
else
fixcr = 0;

caret.moveStart('character', - surround.length - R.length + fixcr);
}

caret.moveEnd('character', (!pos_to_end || surround != '') ? -R.length : 0);
caret.select();
} else if (isMozilla || isOpera) {
if (txt != '' && SelectedText != '')
replacement = true;
var scrollTop = TextArea.scrollTop;
TextArea.value = LeftText + L + ((!replacement) ? SelectedText : '') + R + RightText;
TextArea.scrollTop = scrollTop;

if (isMozilla)
L = L.replace(/\r/g, ''); // Mozilla в текстовом поле не оперирует \r
else if (isOpera && L.indexOf('\r') == -1)
L = L.replace(/\n/g, '\r\n'); // Для совместимости с Opera 9 и 10. Opera 9 оперирует только \n, а 10.50 - \r\n

var newPos = selStart + L.length + ((pos_to_end) ? R.length : 0);
TextArea.selectionStart = newPos;

if (SelectedText == '' || pos_to_end)
TextArea.selectionEnd = newPos;
else {
TextArea.selectionEnd = newPos + SelectedText.length;
}

TextArea.focus();
}
SelectedText = txt = '';

return false;
}

function get_range() {
if (isMSIE) {
if (document.activeElement.name != 'inpost')
TextArea.focus();
IEOP();
return TextArea.caretPos.text;
}
else if (isMozilla || isOpera)
return NNMOZ();
}

function help(obj) {
document.getElementById('help').innerHTML = bbtags[obj.name][2];
return;
}

function NNMOZ() {
var selLength = TextArea.textLength;
selStart = TextArea.selectionStart;
var selEnd = TextArea.selectionEnd;
if (selEnd == 1 || selEnd == 2)
selEnd = selLength;

LeftText = (TextArea.value).substring(0,selStart);
RightText = (TextArea.value).substring(selEnd, selLength);
return (TextArea.value).substring(selStart, selEnd);
}

function copyQ() {
	if (document.getSelection) {txt=document.getSelection();}
	else if (document.selection) {txt=document.selection.createRange().text;}
	return;
}

function IEOP() {
	if (TextArea.createTextRange) {
		TextArea.caretPos = document.selection.createRange().duplicate();
	}
}


function FormChecker(fcForm){
	for (var key in error) {
		if (fcForm.elements[key].value.length == 0 || (n = fcForm.elements[key].value.search(/[^\s]/i)) == -1){
			alert(error[key]);
			return false;
		}
	}
	return true;
}

function Preview(form, act) {
	if (!FormChecker(form)) return;
	var htmltags = (form.html && form.html[0].checked == true) ? form.html[0].value:'no';
	document.getElementById('preview').style.display = "block";
	document.getElementById('prevtext').innerHTML='Ждите! Идет загрузка...';
	scroll(0,0);
	JsHttpRequest.query('jsloader.php?loader=preview', {action: act, html: htmltags, text: TextArea.value}, function(data,text) {
		if (data.error == 1) {
			document.getElementById('preview').style.display = "none";
			alert(text);
			return;
		}
		document.getElementById('prevtext').innerHTML=text;
		}, false);
}

function myFor(obj) {
	for (var key in obj)alert(key + ": " + obj[key]);
}

function ctrlEnter(event, form) {
if (event.ctrlKey && (event.keyCode == 13 || event.keyCode == 10)) {
if (form.name == 'TopicForm' || form.name == 'ReplyFofm' || form.name == 'NewTopic' || form.name == 'EditPost')
form.submit.click();
else if (form.name == 'PM')
form.dosend.click();
}
}