function DelPost(a,ID) {
	var result = a.href.match(/forum=(\d+)\&topic=(\d+)/i);
	if (confirm(LANG.Sure)) {window.location.href='postings.php?action=processedit&deletepost=yes&forum='+result[1]+'&topic='+result[2]+'&postid='+ID;}
	else {alert (LANG.Canceled);}
}
function ChekUncheck(){
	ch = document.ModOptions.chek;
	el = document.getElementsByTagName('input');
	if (ch.checked==true && confirm(LANG.SureSelectAll)==false){
		ch.checked = false;
		return;
	}
	for (var i = 0; i < el.length; i++){
		if (el[i].type.toLowerCase()=='checkbox' && el[i].name.toLowerCase()=='postkey[]') {
			el[i].checked = (ch.checked==true) ? true:false;
		}
	}
}

function CheckFormAction() {
	act = document.ModOptions.action;
	if (act.value == 'delselected' || act.value == 'innew' || act.value == 'inexists' || act.value == 'delattach'){
		el = document.getElementsByTagName('input');
		arr = ''; ii = 0;
		for (var i = 0; i < el.length; i++){
			if (el[i].type.toLowerCase()=='checkbox' && el[i].name.toLowerCase()=='postkey[]' && el[i].checked == true) {
				arr = arr + 'i:'+ii+';i:'+el[i].value+';';
				ii++;
			}
		}
		arr = 'a:'+ii+':{'+arr+'}';
		document.ModOptions.postkey.value = arr;
		if (ii == 0) {alert(LANG.EmptySelect);}
		else if (act.value == 'delselected') {
				if (confirm(LANG.SureDelSelected)) {document.ModOptions.submit();}
				else {alert (LANG.Canceled);}
		}
		else {document.ModOptions.submit();}
  }
  else if (act.value == '-1'){alert (LANG.ActNotSelected);}
  else {document.ModOptions.submit();}
}