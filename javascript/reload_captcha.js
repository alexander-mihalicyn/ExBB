function reload_captcha() {
	var currentTime = new Date();
	
	document.getElementById('captcha').src = 'regimage.php?rnd=' + currentTime.getTime();
}