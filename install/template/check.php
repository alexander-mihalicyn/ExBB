<?php if ($checkingWarnings) : ?>
	<div class="b-alert b-alert__warning"><?php echo lang('checkingWarnings'); ?></div>
<?php endif; ?>

<?php if ($checkingErrors) : ?>
<div class="b-alert b-alert__error"><?php echo lang('checkingErrors'); ?></div>
<?php else : ?>
	<div class="b-alert b-alert__success"><?php echo lang('checkingNotErrors'); ?></div>
<?php endif; ?>

<h2><?php echo lang('checkPHPConfiguration'); ?></h2>
<table class="b-table">
	<thead>
	<tr>
		<th><?php echo lang('phpParameter'); ?></th>
		<th><?php echo lang('phpParameterCurrentValue'); ?></th>
		<th><?php echo lang('phpParameterOptimalValue'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($serverConfigurationCheckList as $object) : ?>
		<tr>
			<td class="b-table__cell_<?php echo ($object['status']) ? 'success' : 'warning'; ?>">
				<?php echo $object['title']; ?>
			</td>
			<td class="b-table__cell_<?php echo ($object['status']) ? 'success' : 'warning'; ?>">
				<?php echo $object['currentValue']; ?>
			</td>
			<td>
				<?php echo $object['optimalValue']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<h2><?php echo lang('checkFilesPermissions'); ?></h2>
<table class="b-table">
	<thead>
		<tr>
			<th><?php echo lang('filePath'); ?></th>
			<th><?php echo lang('fileIsExists'); ?></th>
			<th><?php echo lang('fileIsReadable'); ?></th>
			<th><?php echo lang('fileIsWriteable'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($fileSystemObjectsCheckList as $object) : ?>
		<tr>
			<td class="b-table__cell_<?php echo ($object['isExists'] && $object['isReadable'] && $object['isWriteable']) ? 'success' : 'error'; ?>">
				<?php echo $object['path']; ?>
			</td>
			<td class="b-table__cell_<?php echo ($object['isExists']) ? 'success' : 'error'; ?>">
				<?php echo ($object['isExists']) ? lang('yes') : lang('no'); ?>
			</td>
			<td class="b-table__cell_<?php echo ($object['isReadable']) ? 'success' : 'error'; ?>">
				<?php echo ($object['isReadable']) ? lang('yes') : lang('no'); ?>
			</td>
			<td class="b-table__cell_<?php echo ($object['isWriteable']) ? 'success' : 'error'; ?>">
				<?php echo ($object['isWriteable']) ? lang('yes') : lang('no'); ?>
			</td>

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php if (!$checkingErrors) : ?>
<div class="b-installation-form__buttons">
	<a href="index.php?action=forumSettings" class="b-button"><?php echo lang('continueInstallation'); ?></a>
</div>
<?php endif; ?>
