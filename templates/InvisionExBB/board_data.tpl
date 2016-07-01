<br/>

<?php if ($catrow) : ?>
<table class="tableborder" width="100%" border="0" cellspacing="1" cellpadding="4">
	<tr>
		<th colspan="5" class="maintitle" align="left"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"
															alt="&gt;" width="8" height="8"/>&nbsp;<a
					href="index.php?c=<?php echo $in_cat; ?>"><?php echo $category; ?></a></th>
	</tr>
	<tr>
		<th align="center" width="2%" class="titlemedium"><img src="./templates/InvisionExBB/im/spacer.gif" alt=""
															   width="28" height="1"/></th>
		<th align="left" width="59%" class="titlemedium"><?php echo $fm->LANG['ForumInfo']; ?></th>
		<th align="center" width="7%" class="titlemedium"><?php echo $fm->LANG['TopicsTotal']; ?></th>
		<th align="center" width="7%" class="titlemedium"><?php echo $fm->LANG['Replies']; ?></th>
		<th align="left" width="25%" class="titlemedium"><?php echo $fm->LANG['Updates']; ?></th>
	</tr>
	<?php endif; ?>

	<tr>
		<td class="row4" align="center"><?php echo $folderpicture; ?></td>
		<td class="row4">

			<?php if ($sponsor) : ?>
			<div style="float: right">
				<?php echo $sponsor; ?>
			</div>
			<?php endif; ?>

			<b><?php echo $forumname; ?></b><i><?php echo $viewing; ?></i>
			<br/>
				<span class="desc">
					<?php echo $forumdescription; ?>
					<br/>
					<?php echo $fm->_Modoutput; ?>
					<?php echo $sub; ?>
				</span>
		</td>

		<td class="row2" align="center"><?php echo $threads; ?></td>
		<td class="row2" align="center"><?php echo $posts; ?></td>
		<td class="row2">
			<?php echo $fm->LANG['Date']; ?> <b><?php echo $LastTopicDate; ?></b>
			<br/>
			<?php echo $LastTopicName; ?>
			<br/>
			<?php echo $LastPosterName; ?>
		</td>
	</tr>

	<?php if ($last) : ?>
	<tr>
		<td class="darkrow2" colspan="5">&nbsp;</td>
	</tr>
</table>
<?php endif; ?>