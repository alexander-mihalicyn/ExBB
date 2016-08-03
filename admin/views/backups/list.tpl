<h1><?php echo exLang('admin_backups', 'backups'); ?></h1>
<p class="gensmall"><?php echo exLang('admin_backups', 'backupsManagerDescription'); ?></p>

<table class="forumline" align="center" border="0" cellpadding="4" cellspacing="1" width="99%">
	<tbody><tr>
		<th class="thHead" colspan="2"><?php echo exLang('admin_backups', 'backupsList'); ?></th>
	</tr>

	<?php foreach ($backupsList as $backup) : ?>
	<tr class="gen">
		<td class="row1" width="70%">
			<a href="<?php echo exUrl(['backups', 'download', 'admincenter'], ['backup'=>$backup['filename']]); ?>">
				<?php echo $backup['date']; ?> (<?php echo exLang('admin_backups', 'backupKbSize', [round($backup['size']/1024, 2)]); ?>)
			</a>
		</td>
		<td class="row2">
			<a href="<?php echo exUrl(['backups', 'download', 'admincenter'], ['backup'=>$backup['filename']]); ?>" class="b-btn">
				<?php echo exLang('admin_backups', 'downloadBackup'); ?>
			</a>

			<a href="<?php echo exUrl(['backups', 'delete', 'admincenter'], ['backup'=>$backup['filename']]); ?>" class="b-btn">
				<?php echo exLang('admin_backups', 'deleteBackup'); ?>
			</a>
		</td>
	</tr>
	<?php endforeach; ?>

	<tr>
		<td class="catBottom" colspan="2" align="center">
			<a class="b-btn" href="<?php echo exUrl(['backups', 'create', 'admincenter']); ?>"><?php echo exLang('admin_backups', 'createBackupButtonText'); ?></a>
		</td>
	</tr>
	</tbody>
</table>