function PostId(a,postid){
var result = a.href.match(/^(.+\/)(.+)$/i);
var path = result[1]; var script = result[2];
var forum = script.match(/forum=(\d+)/i); forum = forum[1];
var topic = script.match(/topic=(\d+)/i); topic = topic[1];
prompt(LANG.ThisPostWWW, path + 'topic.php?forum=' + forum + '&topic=' + topic + '&postid=' + postid + '#' + postid);
return false;
}
function Karma(act, userid) {
JsHttpRequest.query('jsloader.php?loader=karma', {action: act, user: userid}, function (data, text) {
alert(text);if (data.error == 0) {var spans = document.getElementsByTagName("SPAN");for (var i=0; i < spans.length; i++) {var span = spans[i];if (span.id.indexOf("karma"+data.user + "_")!=-1) {span.innerHTML = data.karma;}}}},true);
}
function spoiler(el) {
var sp = document.getElementById('sp' + el);
var spoiler = document.getElementById('spoiler' + el);
if (spoiler.style.display == 'none') {
spoiler.style.display = 'block';
sp.innerHTML = '(<a href="#" onClick="spoiler(\'' + el + '\'); return false">' + LANG.SpoilerHide + '</a>)';
}
else {
spoiler.style.display = 'none';
sp.innerHTML = '(<a href="#" onClick="spoiler(\'' + el + '\'); return false">' + LANG.SpoilerShow + '</a>)';
}
}