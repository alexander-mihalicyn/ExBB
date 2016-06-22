var currentForum=null, currentTopic=null, clickLeft, clickTop;
window.onload=processHints;
var hintWidth=400;
function processHints() {
document.body.onload=null;
var spans=document.getElementsByTagName('span'), i, currentFirst, currentLast;
var span=document.createElement('span');
span.className ='spaninfo';
var first=span.cloneNode(true);
first.innerHTML='&laquo;';
first.setAttribute('title', LANG.firstTitle);
var last=span.cloneNode(true);
last.innerHTML='&raquo;';
last.setAttribute('title', LANG.lastTitle);
var space=document.createTextNode(' ');
var div=document.createElement('div');
div.setAttribute('id', 'HintsBlock');
div.className = 'hintclass';
document.body.appendChild(div);
for (i=0; i<spans.length; i++) {
if (spans[i].className=='hint') {
currentFirst=first.cloneNode(true);
currentFirst.onclick=function(evt) {showHint(getEvt(evt), this, 1);};
currentLast=last.cloneNode(true);
currentLast.onclick=function(evt) {showHint(getEvt(evt), this, 2);};
spans[i].appendChild(space.cloneNode(true));
spans[i].appendChild(currentFirst);
spans[i].appendChild(space.cloneNode(true));
spans[i].appendChild(currentLast);
}
}
document.body.onclick=hideHint;
document.getElementById('HintsBlock').onclick=preventHide;
}
function showHint(evt, span, type) {
evt.cancelBubble=true;
var result = span.parentNode.firstChild.href.match(/forum=(\d+)\&topic=(\d+)/i);
var forum=result[1], topic=result[2];
//clickLeft	= (isMSIE||isOpera) ? evt.clientX+document.body.scrollLeft:evt.pageX;
//clickTop	= (isMSIE||isOpera) ? evt.clientY+document.body.scrollTop:evt.pageY;
clickLeft	= (isMSIE||isOpera) ? evt.clientX+document.documentElement.scrollLeft:evt.pageX;
clickTop	= (isMSIE||isOpera) ? evt.clientY+document.documentElement.scrollTop:evt.pageY;

var hinter=document.getElementById('HintsBlock');
hinter.innerHTML=(type==1 ? LANG.firstText : LANG.lastText);
hinter.style.visibility = "visible";
hinter.style.height	= "auto"
resizeHinter();
currentTopic=topic;
currentForum=forum;
JsHttpRequest.query('jsloader.php?loader=threadstop', {inforum: forum, intopic: topic, mode: type}, processMessage,false);
resizeHinter();
}
function resizeHinter() {
	var hinter=document.getElementById('HintsBlock');

	if (hinter.style.visibility != "visible") return;

	var windowHeight = (window.innerHeight != null) ? innerHeight:document.body.clientHeight;
	var windowWidth = (window.innerHeight != null) ? innerWidth:document.body.clientWidth;
	//var ScrolledTop = (window.innerHeight != null) ? pageYOffset:document.body.scrollTop;
	//var ScrolledLeft = (window.innerHeight != null) ? pageXOffset:document.body.scrollLeft;
	var ScrolledTop = (window.innerHeight != null) ? pageYOffset:document.documentElement.scrollTop;
	var ScrolledLeft = (window.innerHeight != null) ? pageXOffset:document.documentElement.scrollLeft;

	if (clickLeft + hintWidth >= windowWidth+ScrolledLeft)
		hinter.style.left=(clickLeft-hintWidth-20)+'px';
	else if (clickLeft - ScrolledLeft <= hintWidth+30)
		hinter.style.left=(clickLeft + 20)+'px';
	else
		hinter.style.left=(clickLeft + 20)+'px';

	var height=hinter.offsetHeight;
	
	hinter.style.width = hintWidth + 'px';
	
	if (height > 500) {
		hinter.style.height	= "500px";height = 500;
	}

	if (clickTop + height >= windowHeight + ScrolledTop) {
		hinter.style.top=(windowHeight+ScrolledTop-height - 20)+'px';

	}
	else if (clickTop - ScrolledTop <= 20) {
		hinter.style.top=(ScrolledTop + 10)+'px';

	}
	else {
		hinter.style.top=(clickTop-10)+'px';
	}
}
function processMessage(data, text) {if (data.error == 1) {hideHint();alert(data.errortext);}else if ((data.topic==currentTopic)&&(data.forum==currentForum)) {document.getElementById('HintsBlock').innerHTML=data.divtext;resizeHinter();}}
function preventHide(evt) {getEvt(evt).cancelBubble=true;}
function getEvt(evt) {return (evt ? evt : window.event);}
function hideHint() {currentForum=currentTopic=null;document.getElementById('HintsBlock').style.visibility = "hidden";}