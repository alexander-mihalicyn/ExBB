<?php
$smile_map = '&nbsp;';

if ($fm->exbb['emoticons'] === TRUE) {
$smile_map = <<<DATA
<!-- SMILES TABLE START //-->
<div>
	<img src="im/emoticons/smile24.gif" alt="smilie" onClick="bbcode(0,'::smile24.gif::')" />
	<img src="im/emoticons/biggrin24.gif" alt="smilie" onClick="bbcode(0,'::biggrin24.gif::')" />
	<img src="im/emoticons/laugh24.gif" alt="smilie" onClick="bbcode(0,'::laugh24.gif::')" />
	<img src="im/emoticons/smile5.gif" alt="smilie" onClick="bbcode(0,':lol1:')" />
	<img src="im/emoticons/ironical1.gif" alt="smilie" onClick="bbcode(0,'::wink24.gif::')" />
	<img src="im/emoticons/rolleyes24.gif" alt="smilie" onClick="bbcode(0,'::rolleyes24.gif::')" />
	<img src="im/emoticons/blink.gif" alt="smilie" onClick="bbcode(0,'::blink.gif::')" />
	<img src="im/emoticons/love.gif" alt="smilie" onClick="bbcode(0,'::love::')" />
	<img src="im/emoticons/respect.gif" alt="smilie" onClick="bbcode(0,'::respect::')" />
	<img src="im/emoticons/kiss1.gif" alt="smilie" onClick="bbcode(0,'::kiss1::')" />
	<img src="im/emoticons/dry.gif" alt="smilie" onClick="bbcode(0,'::dry.gif::')" />
	<img src="im/emoticons/odnako.gif" alt="smilie" onClick="bbcode(0,'::huh.gif::')" />
	<img src="im/emoticons/mad24.gif" alt="smilie" onClick="bbcode(0,'::mad24.gif::')" />
	<img src="im/emoticons/ohmy.gif" alt="smilie" onClick="bbcode(0,'::-ohmy.gif::')" />
	<img src="im/emoticons/trouble.gif" alt="smilie" onClick="bbcode(0,'::sad24.gif::')" />
	<img src="im/emoticons/tongue24.gif" alt="smilie" onClick="bbcode(0,'::tongue24.gif::')" />
	<img src="im/emoticons/ph34r.gif" alt="smilie" onClick="bbcode(0,'::-ph34r.gif::')" />
	<img src="im/emoticons/cool24.gif" alt="smilie" onClick="bbcode(0,'::cool24.gif::')" />
	<img src="im/emoticons/eye.gif" alt="smilie" onClick="bbcode(0,'::eye::')" />
	<img src="im/emoticons/shock.gif" alt="smilie" onClick="bbcode(0,'::shock::')" />
	<img src="im/emoticons/confused.gif" alt="smilie" onClick="bbcode(0,'::unsure.gif::')" />
	<img src="im/emoticons/look .gif" alt="smilie" onClick="bbcode(0,'::look::')" />
	<img src="im/emoticons/bomb.gif" alt="smilie" onClick="bbcode(0,'::bomb.gif::')" />
	<img src="im/emoticons/confused1.gif" alt="smilie" onClick="bbcode(0,'::confused1::')" />
	<img src="im/emoticons/atstoy1.gif" alt="smilie" onClick="bbcode(0,'::atstoy1.gif::')" />
	<img src="im/emoticons/inclination.gif" alt="smilie" onClick="bbcode(0,'::inclination::')" />
	<img src="im/emoticons/approve.gif" alt="smilie" onClick="bbcode(0,'::approve::')" />
	<img src="im/emoticons/bravo.gif" alt="smilie" onClick="bbcode(0,'::bravo::')" />
	<img src="im/emoticons/hello.gif" alt="smilie" onClick="bbcode(0,'::hello:: ')" />
	<b><a href=javascript:void(0); onClick=window.open("tools.php?action=smiles","","width=320,height=540,scrollbars=yes")>{$fm->LANG['SmilesOn']}</a></b>
</div>
<!-- SMILES TABLE END //-->
DATA;
}
?>