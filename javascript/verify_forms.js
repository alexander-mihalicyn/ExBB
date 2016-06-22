/*
	Ajax Verification Forms Mod for ExBB FM 1.0 RC2
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

var ok_class		= 'verify_ok';
var wrong_class		= 'verify_wrong';

function verify_register(element) {
	JsHttpRequest.query('jsloader.php?loader=verify', {form: 'register', name: element.name, value: element.value}, verify_result, 1);
}

function verify_result(data, text) {
	var verify	= document.getElementById('verify_' + data.name);
	if (verify == null)
		return;
	
	verify.className = (data.result == 1) ? ok_class : wrong_class;
	
	verify.innerHTML = (data.text != '') ? data.text : '&nbsp;';
	
	if (data.alert != '')
		alert(data.alert);
}