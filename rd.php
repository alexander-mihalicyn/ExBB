<?php
if (!preg_match('#^(http|https|ftp)%3A%2F%2([' . chr(33) . '-' . chr(127) . ']+)$#is', urlencode($_SERVER['QUERY_STRING']))) {
	die;
}
?>
<script language="JavaScript" type="text/javascript">
	<!--
	var anchor = document.location.href.match(/#\w+/i);
	document.location.href = '<?php echo urldecode($_SERVER['QUERY_STRING']); ?>' + ((anchor) ? anchor[0] : '');
	//-->
</script>