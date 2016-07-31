<?php
echo <<<DATA
			<h1>{$fm->LANG['ModuleTitle']}</h1>
            <form action="setmodule.php" method="post">
			<input type="hidden" name="module" value="belong">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
        			<tr>
          				<th class="thHead" width="70%">{$fm->LANG['Variable']}</th>
          				<th class="thHead">{$fm->LANG['VariableValue']}</th>
       	 			</tr>
                    <tr class="gen">
						<td class="row1">{$fm->LANG['BelongMembersPerDb']}<br /><span class="gensmall">{$fm->LANG['BelongMembersPerDbDesc']}</span></td>
						<td class="row2"><input class="post" type="text" name="membersPerDb" maxlength="4" size="4" value="{$config['membersPerDb']}" /></td>
					</tr>
                    <tr class="gen">
						<td class="row1">{$fm->LANG['BelongViewTopics']}</td>
						<td class="row2"><input type="radio" name="viewTopics" value="yes" {$viewTopicsYes}/> {$fm->LANG['Yes']}&nbsp;&nbsp;
                        <input type="radio" name="viewTopics" value="no" {$viewTopicsNo}/> {$fm->LANG['No']}</td>
					</tr>
                    <tr class="gen">
						<td class="row1">{$fm->LANG['BelongViewPosts']}</td>
						<td class="row2"><input type="radio" name="viewPosts" value="yes" {$viewPostsYes}/> {$fm->LANG['Yes']}&nbsp;&nbsp;
                        <input type="radio" name="viewPosts" value="no" {$viewPostsNo}/> {$fm->LANG['No']}</td>
					</tr>
                    <tr class="gen">
                        <td class="row1">{$fm->LANG['BelongIndexDbs']}<br /><span class="gensmall">{$fm->LANG['BelongIndexDbsDesc']}</span></td>
                        <td class="row2"><a href="setmodule.php?module=belong&execute=index">{$fm->LANG['BelongExecute']}</a></td>
                    </tr>
                    <tr>
						<td class="catBottom" colspan="2" align="center"><input type="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
                </table>
            </form>
            <div align="center" class="gensmall"><br />Belong Mod for ExBB FM {$fm->exbb['version']} by 
			<a href="http://www.exbb.org/">yura3d</a></div>
DATA;
