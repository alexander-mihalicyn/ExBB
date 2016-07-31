<?php
echo <<<DATA
<script>
function checklength(form) {
if ((form.reason.value.length < {$min}) || (form.reason.value.length > {$max})) {
alert("{$fm->LANG['RepSizeAlert']}");
return false;
}
}
</script>
            <br>
            <div id="navstrip" align="left">
                <img name="formtop" src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &raquo; {$rep_change}<br><br>
            </div>
            <form method="post">
                <table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
                    <tr>
                          <td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$rep_change}</td>
                    </tr>
                    <tr>
                          <td class="pformleft"><b>{$fm->LANG['RepAction']}</b></td>
                          <td class="pformright"><b>{$rep_action}</b></td>
                    </tr>
                    <tr>
                          <td class="pformleft" valign="top"><b>{$fm->LANG['RepReason']}</b><br>{$fm->LANG['RepReasonDesc']}</td>
                          <td class="pformright" valign="top">
                              <textarea cols="50" rows="7" name="reason" class="textinput"></textarea>
                          </td>
                    </tr>
                    <tr>
                          <td class="pformstrip" align="center" style="text-align:center" colspan="2">
                            <input type="submit" value="{$fm->LANG['Save']}" name="send" onclick="return checklength(this.form)"> <input type="reset" name="Clear" value="{$fm->LANG['Clear']}" />
                          </td>
                    </tr>
                  </table>
              </form>
          </div>
          <br>
DATA;
