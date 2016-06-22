<?php
echo <<<DATA

			<table align="center">
				<tr id="formstyle">
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="l1" /></td>
					<td width="36"><input type="button" onclick="tab(); self.focus();" style="width: 36px;" name="keyboard" value="Tab" id="tab" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="q" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="w" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="e" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="r" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="t" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="y" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="u" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="i" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="o" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="p" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="r1" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="rr1" /></td>
					<td width="32"><input type="button" onclick="backspace(); self.focus();" style="width: 32px;" value="<--" id="back" /></td>
				</tr>
			</table>
			<table align="center">
				<tr id="formstyle">
					<td width="74" align="right"><input type="button" onclick="caps(); self.focus();" style="width: 46px;" value="Caps" id="caps" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="a" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="s" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="d" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="f" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="g" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="h" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="j" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="k" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="l" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="r2" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="rr2" /></td>
					<td width="50"><input type="button" onclick="enter(); self.focus();" style="width: 50px;" value="Enter" id="enter" /></td>
				</tr>
			</table>
			<table align="center">
				<tr id="formstyle">
					<td width="60"><input type="button" onclick="shift(); self.focus();" style="width: 60px;" value="Shift" id="shift" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="z" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="x" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="c" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="v" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="b" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="n" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="m" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="r3" /></td>
					<td width="24"><input type="button" onclick="paste(this); self.focus();" style="width: 24px;" id="rr3" /></td>
					<td width="24"><input type="button" onclick="comma(); self.focus();" style="width: 24px;" value="," id="comma" /></td>
					<td width="36"><input type="button" onclick="dot(); self.focus();" style="width: 24px;" value="." id="dot" /></td>
				</tr>
			</table>
			<table align="center">
				<tr id="formstyle">
					<td width="180"><input type="button" onclick="space(); self.focus();" style="width: 180px;" value="Space" /></td>
				</tr>
			</table>
			<script language="JavaScript" type="text/javascript">
			<!--
				var upper = 0;
				var symbols = {
					l1:		['¸', '¨'],
					q:		['é', 'É'],
					w:		['ö', 'Ö'],
					e:		['ó', 'Ó'],
					r:		['ê', 'Ê'],
					t:		['å', 'Å'],
					y:		['í', 'Í'],
					u:		['ã', 'Ã'],
					i:		['ø', 'Ø'],
					o:		['ù', 'Ù'],
					p:		['ç', 'Ç'],
					r1:		['õ', 'Õ'],
					rr1:	['ú', 'Ú'],
					a:		['ô', 'Ô'],
					s:		['û', 'Û'],
					d:		['â', 'Â'],
					f:		['à', 'À'],
					g:		['ï', 'Ï'],
					h:		['ð', 'Ð'],
					j:		['î', 'Î'],
					k:		['ë', 'Ë'],
					l:		['ä', 'Ä'],
					r2:		['æ', 'Æ'],
					rr2:	['ý', 'Ý'],
					z:		['ÿ', 'ß'],
					x:		['÷', '×'],
					c:		['ñ', 'Ñ'],
					v:		['ì', 'Ì'],
					b:		['è', 'È'],
					n:		['ò', 'Ò'],
					m:		['ü', 'Ü'],
					r3:		['á', 'Á'],
					rr3:	['þ', 'Þ']
				};
				
				var keyboard_button = document.getElementById('tab');
				
				function fill_buttons() {
					var key;
					for (key in symbols)
						document.getElementById(key).value = symbols[key][upper];
				}
				
				function paste(button) {
					return opener.bbcode(keyboard_button, symbols[button.id][upper]);
				}
				
				function tab() {
					return opener.bbcode(keyboard_button, '\\t');
				}
				
				function backspace() {
					if (opener.document.activeElement.name != 'inpost')
						opener.TextArea.focus();
					
					if (isMSIE || isOpera) {
						var sel = opener.document.selection.createRange();
						
						if (sel.text == '')
							sel.moveStart('character', -1);
						
						sel.text = '';
					}
					else if (isMozilla) {
						var length	= opener.TextArea.textLength;
						var start	= opener.TextArea.selectionStart;
						var end		= opener.TextArea.selectionEnd;
						
						if (start == end)
							if (start != 0)
								start--;
							else
								return false;
						
						opener.TextArea.value = opener.TextArea.value.substring(0, start) + opener.TextArea.value.substring(end, length);
					}
				}
				
				function caps() {
					upper = (upper == 0) ? 1 : 0;
					
					fill_buttons();
				}
				
				function enter() {
					return opener.bbcode(keyboard_button, '\\r\\n');
				}
				
				function shift() {
					
				}
				
				function comma() {
					return opener.bbcode(keyboard_button, ',');
				}
				
				function dot() {
					return opener.bbcode(keyboard_button, '.');
				}
				
				function space() {
					return opener.bbcode(keyboard_button, ' ');
				}
				
				fill_buttons();
			//-->
			</script>

DATA;
?>