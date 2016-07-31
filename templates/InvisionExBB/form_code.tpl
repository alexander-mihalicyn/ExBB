<?php
$form_code = '&nbsp;';

if ($fm->exbb['exbbcodes'] === TRUE) {
$form_code = <<<COD
<script type="text/javascript" language="JavaScript">
	<!--
	var bblang = {
		SpoilerTitle: '{$fm->LANG['SpoilerTitle']}',
		HideMsgs: '{$fm->LANG['HideMsgs']}',
		HelpKeyboard: '{$fm->LANG['HelpKeyboard']}',
		HelpBBCode: '{$fm->LANG['HelpBBCode']}',
		HelpSmiles: '{$fm->LANG['HelpSmiles']}',
		LinkHref: '{$fm->LANG['LinkHref']}',
		LinkText: '{$fm->LANG['LinkText']}',
		ImgHref: '{$fm->LANG['ImgHref']}',
		YouTubeHref: '{$fm->LANG['YouTubeHref']}',
		YouTubeTitle: '{$fm->LANG['YouTubeTitle']}',
		RuTubeHref: '{$fm->LANG['RuTubeHref']}',
		RuTubeTitle: '{$fm->LANG['RuTubeTitle']}',
		VkHref: '{$fm->LANG['VkHref']}',
		VkTitle: '{$fm->LANG['VkTitle']}',
	};

	var bbtags = {
		bold: {0:'[b]',1:'[/b]',2:'{$fm->LANG['HelpBold']}'},
		italic: {0:'[i]',1:'[/i]',2:'{$fm->LANG['HelpItalic']}'},
		underline: {0:'[u]',1:'[/u]',2:'{$fm->LANG['HelpUnderLine']}'},
		strikeout: {0:'[s]',1:'[/s]',2:'{$fm->LANG['HelpStrikeOut']}'},
		left: {0:'[left]',1:'[/left]',2:'{$fm->LANG['HelpLeft']}'},
		center: {0:'[center]',1:'[/center]',2:'{$fm->LANG['HelpCenter']}'},
		right: {0:'[right]',1:'[/right]',2:'{$fm->LANG['HelpRight']}'},
		justify: {0:'[justify]',1:'[/justify]',2:'{$fm->LANG['HelpJustify']}'},
		sub: {0:'[sub]',1:'[/sub]',2:'{$fm->LANG['HelpSub']}'},
		sup: {0:'[sup]',1:'[/sup]',2:'{$fm->LANG['HelpSup']}'},
		h1: {0:'[h1]',1:'[/h1]',2:'{$fm->LANG['HelpH1']}'},
		h2: {0:'[h2]',1:'[/h2]',2:'{$fm->LANG['HelpH2']}'},
		big: {0:'[big]',1:'[/big]',2:'{$fm->LANG['HelpBig']}'},
		small: {0:'[small]',1:'[/small]',2:'{$fm->LANG['HelpSmall']}'},
		list: {0:'[list]',1:'[/list]',2:'{$fm->LANG['HelpList']}'},
		listn: {0:'[list=1]',1:'[/list]',2:'{$fm->LANG['HelpListN']}'},
		bullet: {0:'[*]',1:'',2:'{$fm->LANG['HelpBullet']}'},
		spoiler: {0:'[spoiler%]',1:'[/spoiler]',2:'{$fm->LANG['HelpSpoiler']}'},
		hide: {0:'[hide%]',1:'[/hide]',2:'{$fm->LANG['HelpHide']}'},
		code: {0:'[code]',1:'[/code]',2:'{$fm->LANG['HelpCode']}'},
		php: {0:'[php]',1:'[/php]',2:'{$fm->LANG['HelpPHP']}'},
		offtop: {0:'[off]',1:'[/off]',2:'{$fm->LANG['HelpOfftop']}'},
		rus: {0:'[rus]',1:'[/rus]',2:'{$fm->LANG['HelpRus']}'},
		quot: {0:'«',1:'»',2:'{$fm->LANG['HelpQuot']}'},
		marquee: {0:'[marquee]',1:'[/marquee]',2:'{$fm->LANG['HelpMarquee']}'},
		hr: {0:'[hr]',1:'',2:'{$fm->LANG['HelpHr']}'},
		search: {0:'[search]',1:'[/search]',2:'{$fm->LANG['HelpSearch']}'},
		url: {0:'[url%]',1:'[/url]',2:'{$fm->LANG['HelpUrl']}'},
		img: {0:'[img]',1:'[/img]',2:'{$fm->LANG['HelpImage']}'},
		youtube: {0:'[youtube%]',1:'[/youtube]',2:'{$fm->LANG['HelpYouTube']}'},
		rutube: {0:'[rutube%]',1:'[/rutube]',2:'{$fm->LANG['HelpRuTube']}'},
		vkvideo: {0:'[vkvideo%]',1:'[/vkvideo]',2:'{$fm->LANG['HelpVk']}'},
		quote: {0:'[quote%]',1:'[/quote]',2:'{$fm->LANG['HelpQuote']}'},
		color: {0:'[color=%]',1:'[/color]',2:'{$fm->LANG['HelpFontColor']}'},
		size: {0:'[size=%]',1:'[/size]',2:'{$fm->LANG['HelpFontSize']}'}
	};
	//-->
</script>
<!-- CODE BUTTONS TABLE START //-->
<table>
	<tr>
		<td><a href="#" name="bold" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/bold.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="italic" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/italic.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="underline" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/underline.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="strikeout" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/strikeout.gif" width="25" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><a href="#" name="left" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/left.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="center" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/center.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="right" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/right.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="justify" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/justify.gif" width="25" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><a href="#" name="sub" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/sub.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="sup" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/sup.gif" width="25" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><a href="#" name="h1" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/h1.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="h2" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/h2.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="big" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/big.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="small" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/small.gif" width="25" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><a href="#" name="list" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/list.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="listn" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/listn.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="bullet" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/bullet.gif" width="25" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><a href="#" name="rus" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/rus.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="quot" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/quot.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="marquee" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/marquee.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="hr" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/hr.gif" width="25" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><a href="#" name="search" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/search.gif" width="25" height="25"/></a></td>
		<td><a href="#" onclick="window.open('tools.php?action=keyboard','','width=650,height=180'); return false;"
			   onmouseover="document.getElementById('help').innerHTML = bblang.HelpKeyboard;"><img
						src="templates/InvisionExBB/im/russian/keyboard.gif" width="25" height="25"/></a></td>
		<td><a href="#" onclick="window.open('tools.php?action=bbcode','','width=600,height=600'); return false;"
			   onmouseover="document.getElementById('help').innerHTML = bblang.HelpBBCode;"><img
						src="templates/InvisionExBB/im/russian/help.gif" width="25" height="25"/></a></td>
	</tr>
</table>
<table>
	<tr>
		<td><a href="#" name="url" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/url.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="img" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/img.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="youtube" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/youtube.gif" height="25"/></a></td>
		<td><a href="#" name="rutube" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/rutube.gif" height="25"/></a></td>
		<td><a href="#" name="vkvideo" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/vk.png" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><a href="#" name="quote" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/qte.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="code" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/code.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="php" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/php.gif"/></a></td>
		<td><a href="#" name="spoiler" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/spoiler.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="hide" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/hide.gif" width="25" height="25"/></a></td>
		<td><a href="#" name="offtop" onclick="return bbcode(this);" onmouseover="help(this);"><img
						src="templates/InvisionExBB/im/russian/offtop.gif" width="25" height="25"/></a></td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><img src="templates/InvisionExBB/im/russian/color.gif" width="25" height="25" name="color"
				 onmouseover="help(this);"/></a></td>
		<td>
			<select name="color"
					onchange="bbcode(this, this.options[this.selectedIndex].value); this.selectedIndex = 0;"
					onmouseover="help(this);">
				<option style="color: black;" value="black">{$fm->LANG['Default']}</option>
				<option style="color: darkred;" value="darkred">{$fm->LANG['DarkRed']}</option>
				<option style="color: red;" value="red">{$fm->LANG['Red']}</option>
				<option style="color: orange;" value="orange">{$fm->LANG['Orange']}</option>
				<option style="color: brown;" value="brown">{$fm->LANG['Brown']}</option>
				<option style="color: yellow;" value="yellow">{$fm->LANG['Yellow']}</option>
				<option style="color: green;" value="green">{$fm->LANG['Green']}</option>
				<option style="color: olive;" value="olive">{$fm->LANG['Olive']}</option>
				<option style="color: aqua;" value="aqua">{$fm->LANG['Cyan']}</option>
				<option style="color: blue;" value="blue">{$fm->LANG['Blue']}</option>
				<option style="color: darkblue;" value="darkblue">{$fm->LANG['DarkBlue']}</option>
				<option style="color: indigo;" value="indigo">{$fm->LANG['Indigo']}</option>
				<option style="color: violet;" value="violet">{$fm->LANG['Violet']}</option>
				<option style="color: white;" value="white">{$fm->LANG['White']}</option>
				<option style="color: black;" value="black">{$fm->LANG['Black']}</option>
			</select>
		</td>
		<td><img src="templates/InvisionExBB/im/panel_line.gif" width="5" height="25"/></td>
		<td><img src="templates/InvisionExBB/im/russian/size.gif" width="25" height="25" name="size"
				 onmouseover="help(this);"/></a></td>
		<td>
			<select name="size" onChange="bbcode(this,this.options[this.selectedIndex].value);this.selectedIndex=0;"
					onMouseOver="help(this)">
				<option value="12" selected>{$fm->LANG['Default']}</option>
				<option value="7">{$fm->LANG['FontVSmall']}</option>
				<option value="9">{$fm->LANG['FontSmall']}</option>
				<option value="18">{$fm->LANG['FontBig']}</option>
				<option value="24">{$fm->LANG['FontVBig']}</option>
			</select>
		</td>
	</tr>
</table>
<!-- CODE BUTTONS TABLE END //-->
COD;
}
?>