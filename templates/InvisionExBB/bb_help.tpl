<?php
echo <<<DATA

			<div class="tableborder">
<table class="tablebasic" cellspacing="1" cellpadding="4" width="100%">
	<tr>
		<td class="maintitle" colspan="2" align="left">{$fm->LANG['bbCodesHelp']}</td>
	</tr>
	<tr>
		<td width="50%" align='center' class="titlemedium" valign="middle">{$fm->LANG['bbUsing']}</td>
		<td width="50%" align='center' class="titlemedium" valign="middle">{$fm->LANG['bbResult']}</td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[b]</span>{$fm->LANG['bbYourText']}<span
					style="color: #ff0000; font-weight: bold;">[/b]</span></td>
		<td align="left" class="row2" valign="middle"><b>{$fm->LANG['bbYourText']}</b></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[i]</span>{$fm->LANG['bbYourText']}<span
					style="color: #ff0000; font-weight: bold;">[/i]</span></td>
		<td align="left" class="row2" valign="middle"><i>{$fm->LANG['bbYourText']}</i></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[u]</span>{$fm->LANG['bbYourText']}<span
					style="color: #ff0000; font-weight: bold;">[/u]</span></td>
		<td align="left" class="row2" valign="middle"><u>{$fm->LANG['bbYourText']}</u></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[email]</span>user@domain.com<span
					style="color: #ff0000; font-weight: bold;">[/email]</span></td>
		<td align="left" class="row2" valign="middle"><a href="mailto:user@domain.com">user@domain.com</a></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[email=user@domain.com]</span>E-mail<span
					style="color: #ff0000; font-weight: bold;">[/email]</span></td>
		<td align="left" class="row2" valign="middle"><a href="mailto:user@domain.com">E-mail</a></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[url]</span>http://www.domain.com<span
					style="color: #ff0000; font-weight: bold;">[/url]</span></td>
		<td align="left" class="row2" valign="middle"><a href="http://www.domain.com" target="_blank">http://www.domain.com</a>
		</td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[url=http://www.domain.com]</span>{$fm->LANG['bbYourText']}
			<span style="color: #ff0000; font-weight: bold;">[/url]</span></td>
		<td align="left" class="row2" valign="middle"><a href="http://www.domain.com"
														 target="_blank">{$fm->LANG['bbYourText']}</a></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[size=14]</span>{$fm->LANG['bbYourText']}<span
					style="color: #ff0000;font-weight: bold;">[/size]</span></td>
		<td align="left" class="row2" valign="middle"><span
					style="font-size: 14pt; line-height: 100%">{$fm->LANG['bbYourText']}</span></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[color=red]</span>{$fm->LANG['bbYourText']}<span
					style="color: #ff0000; font-weight: bold;">[/color]</span></td>
		<td align="left" class="row2" valign="middle"><span style="color: red">{$fm->LANG['bbYourText']}</span></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[img]</span>http://exbb.org/img/logo.gif<span
					style="color: #ff0000; font-weight: bold;">[/img]</span></td>
		<td align="left" class="row2" valign="middle"><img src="admin/logoadmin.gif" border="0"
														   alt="{$fm->LANG['bbPastedImage']}"/></td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[list]</span>[*]{$fm->LANG['bbListPoint1']}
			[*]{$fm->LANG['bbListPoint2']}<span style="color: #ff0000; font-weight: bold;">[/list]</span></td>
		<td align="left" class="row2" valign="middle">
			<ul>
				<li>{$fm->LANG['bbListPoint1']}</li>
				<li>{$fm->LANG['bbListPoint2']}</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[list=1]</span>[*]{$fm->LANG['bbListPoint1']}
			[*]{$fm->LANG['bbListPoint2']}<span style="color: #ff0000; font-weight: bold;">[/list]</span></td>
		<td align="left" class="row2" valign="middle">
			<ol type="1">
				<li>{$fm->LANG['bbListPoint1']}</li>
				<li>{$fm->LANG['bbListPoint2']}</li>
			</ol>
		</td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[list=a]</span>[*]{$fm->LANG['bbListPoint1']}
			[*]{$fm->LANG['bbListPoint2']}<span style="color: #ff0000; font-weight: bold;">[/list]</span></td>
		<td align="left" class="row2" valign="middle">
			<ol type="a">
				<li>{$fm->LANG['bbListPoint1']}</li>
				<li>{$fm->LANG['bbListPoint2']}</li>
			</ol>
		</td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[quote]</span>{$fm->LANG['bbQuotingText']}<span
					style="color: #ff0000; font-weight: bold;">[/quote]</span></td>
		<td align="left" class="row2" valign="middle">
			<div class="block"><b>{$fm->LANG['bbQuote']}</b>

				<div class="quote">{$fm->LANG['bbQuotingText']}</div>
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span
					style="color: #ff0000; font-weight: bold;">[quote={$fm->LANG['bbQuoteName']}
				]</span>{$fm->LANG['bbQuotingText']}<span style="color: #ff0000; font-weight: bold;">[/quote]</span>
		</td>
		<td align="left" class="row2" valign="middle">
			<div class="block"><b>{$fm->LANG['bbQuoteName']} {$fm->LANG['bbQuoteWrote']}</b>

				<div class="quote">{$fm->LANG['bbQuotingText']}</div>
			</div>
		</td>
	</tr>
	<tr>
		<td align="left" class="row1" valign="middle"><span style="color: #ff0000; font-weight: bold;">[code]</span>&lt;?php
			echo "Hello world!" ?&gt;<span style="color: #ff0000; font-weight: bold;">[/code]</span></td>
		<td align="left" class="row2" valign="middle">
			<div class="block"><b>{$fm->LANG['bbCode']}</b>

				<div class="htmlcode">&lt;?php echo "Hello world!" ?&gt;</div>
			</div>
		</td>
	</tr>
</table>
</div>

DATA;
?>