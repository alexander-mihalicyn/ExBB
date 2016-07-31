<?php
include('./templates/InvisionExBB/form_code.tpl');
include('./templates/InvisionExBB/smile_map.tpl');
echo <<<DATA
<script language="Javascript" src="include/js/board.js"></script>
<form name="postform1">
    <input type=hidden name="action" value="processedit">
    <input type=hidden name="id" value="1161940136:1">
    <input type=hidden name="forum" value="3">
    <input type=hidden name="topic" value="479">
    <table class='tableborder' cellpadding="0" cellspacing="0" width="100%" align="center">
      <tr>
        <td class='pformright'> <table>
            <tr align="center" valign="middle">
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" onMouseOver="helpline('bold')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" onMouseOver="helpline('italic')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" onMouseOver="helpline('underline')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" onMouseOver="helpline('quote')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode8" value="Code" style="width: 40px" onClick="bbstyle(8)" onMouseOver="helpline('code')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode10" value="List" style="width: 40px" onClick="bbstyle(10)" onMouseOver="helpline('list')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode12" value="List=" style="width: 40px" onClick="bbstyle(12)" onMouseOver="helpline('numericlist')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode14" value="Img" style="width: 40px"  onClick="bbstyle(14)" onMouseOver="helpline('image')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode16" value="URL" style="width: 40px" onClick="bbstyle(16)" onMouseOver="helpline('url')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode18" value="RUS" style="width: 40px" onClick="bbstyle(18)" onMouseOver="helpline('rus')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode20" value="OFFTOP" style="width: 60px" onClick="bbstyle(20)" onMouseOver="helpline('offtop')" />
                </span></td>
              <td><span class="dats">
                <input type="button" class="button" name="addbbcode20" value="SEARCH" style="width: 60px" onClick="bbstyle(22)" onMouseOver="helpline('search')" />
                </span></td>
            </tr>
            <tr>
              <td colspan="12"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td nowrap><span class="dats"> &nbsp;Цвет шрифта:
                      <select name="addbbcodecolor" onChange="bbfontstyle('[color=' + this.form.addbbcodecolor.options[this.form.addbbcodecolor.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;" onMouseOver="helpline('fontcolor')">
                        <option style="color:black; background-color: #FAFAFA" value="#444444" class="dats">По
                        умолчанию</option>
                        <option style="color:darkred; background-color: #FAFAFA" value="darkred" class="dats">Тёмно-красный</option>
                        <option style="color:red; background-color: #FAFAFA" value="red" class="dats">Красный</option>
                        <option style="color:orange; background-color: #FAFAFA" value="orange" class="dats">Оранжевый</option>
                        <option style="color:brown; background-color: #FAFAFA" value="brown" class="dats">Коричневый</option>
                        <option style="color:yellow; background-color: #FAFAFA" value="yellow" class="dats">Жёлтый</option>
                        <option style="color:green; background-color: #FAFAFA" value="green" class="dats">Зелёный</option>
                        <option style="color:olive; background-color: #FAFAFA" value="olive" class="dats">Оливковый</option>
                        <option style="color:cyan; background-color: #FAFAFA" value="cyan" class="dats">Голубой</option>
                        <option style="color:blue; background-color: #FAFAFA" value="blue" class="dats">Синий</option>
                        <option style="color:darkblue; background-color: #FAFAFA" value="darkblue" class="dats">Тёмно-синий</option>
                        <option style="color:indigo; background-color: #FAFAFA" value="indigo" class="dats">Индиго</option>
                        <option style="color:violet; background-color: #FAFAFA" value="violet" class="dats">Фиолетовый</option>
                        <option style="color:white; background-color: #FAFAFA" value="white" class="dats">Белый</option>
                        <option style="color:black; background-color: #FAFAFA" value="black" class="dats">Чёрный</option>
                      </select>
                      &nbsp;Размер шрифта:
                      <select name="addbbcodesize" onChange="bbfontstyle('[size=' + this.form.addbbcodesize.options[this.form.addbbcodesize.selectedIndex].value + ']', '[/size]')" onMouseOver="helpline('fontsize')">
                        <option value="7" class="dats">Очень маленький</option>
                        <option value="9" class="dats">Маленький</option>
                        <option value="12" selected class="dats">По умолчанию</option>
                        <option value="18" class="dats">Большой</option>
                        <option  value="24" class="dats">Огромный</option>
                      </select>
                      </span></td>
                    <td nowrap="nowrap" align="right"><span class="dats"> <a href="javascript:bbstyle(-1)" class="dats" onMouseOver="helpline('close')">Закрыть
                      тэги</a></span></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td colspan="12"> <span class="dats">
                <input type="text" name="helpbox" size="45" maxlength="100" style="width:450px; font-size:10px" class="helpline" value="Подсказка: Можно быстро применить стили к выделенному тексту" />
                </span></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td class="pformright" valign='top'> <textarea cols='90' rows='11' wrap="virtual"  name="inpost" tabindex='3' class='textinput' onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">[quote:Anastasia]...а чего дальше то делать?[/quote]
найди файл readme или файл с указаниями по инсталяции и действуйте согласно инструкциям в файле.</textarea></td>
      </tr>
      <tr>
        <td class='pformright'><input type=checkbox name="inshowsignature" value="yes" checked>
          Хотите добавить свою подпись?<br> <input type=checkbox name="inshowemoticons" value="yes" checked>
          Вы хотите <b>разрешить</b> <a href=javascript:void(0); onClick=window.open("tools.php?action=smiles","","width=320,height=400,scrollbars=yes")>смайлики</a>
          в этом сообщении? </td>
      </tr>
      <tr>
        <td class='pformstrip' align='center' style='text-align:left'> <b>Опции
          модератора</b></td>
      </tr>
      <tr>
        <td class='pformright'><b>Добавить подпись редактора?</b><br> <input name="modertext" type="radio" value="yes" checked>
          &nbsp;да&nbsp;&nbsp;&nbsp; <input name="modertext" type="radio" value="no">
          &nbsp;нет </td>
      </tr>
      <tr>
        <td class='pformright'> <input class="tab" type=checkbox name="deletepost" value="yes">
          Удалить это сообщение? Только для администратора или модератора форума<br>
          <input class="tab" type=checkbox name="lockedit" value="1">
          Запретить правку сообщения? </td>
      </tr>
      <tr>
        <td class='pformright'><TEXTAREA class="textarea1"  cols=60 name=mo_edit rows=3 wrap=VIRTUAL></TEXTAREA></td>
      </tr>
      <tr>
        <td class='pformstrip' align='center' style='text-align:center'> <input type="submit" value="Отправить" name="submit" onClick="return Formchecker(this.form)" tabindex='4' class='forminput' accesskey='s' />
          &nbsp; <input type="reset"  value="Очистить"  tabindex='5'  class='forminput' />
        </td>
      </tr>
    </table>
  </form>
DATA;

