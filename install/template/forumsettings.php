<?php if (!empty($messages)) : ?>
	<?php foreach ($messages as $message) : ?>
		<div class="b-alert b-alert__<?php echo $message['type']; ?>"><?php echo $message['text']; ?></div>
	<?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="index.php?action=forumSettings">
	<fieldset>
		<legend><?php echo lang('forumSettings'); ?></legend>

		<!--div class="b-form__group">
			<label for="install_forum_settings_url"><?php echo lang('forumSettingUrlLabel'); ?></label>
			<input id="install_forum_settings_url" class="b-form__group_control" type="text" name="settings[forum][url]" value="<?php echo $settings['forum']['url']; ?>" required/>
		</div-->

		<div class="b-form__group">
			<label for="install_forum_settings_title"><?php echo lang('forumSettingTitle'); ?></label>
			<input id="install_forum_settings_title" class="b-form__group_control" type="text" name="settings[forum][title]" value="<?php echo $settings['forum']['title']; ?>" required/>
		</div>

		<div class="b-form__group">
			<label for="install_forum_settings_description"><?php echo lang('forumSettingDescription'); ?></label>
			<input id="install_forum_settings_description" class="b-form__group_control" type="text" name="settings[forum][description]" value="<?php echo $settings['forum']['description']; ?>" required/>
		</div>

		<div class="b-form__group">
			<label for="install_forum_settings_email"><?php echo lang('forumSettingEmail'); ?></label>
			<input id="install_forum_settings_email" class="b-form__group_control" type="text" name="settings[forum][email]" value="<?php echo $settings['forum']['email']; ?>" required/>
		</div>

		<div class="b-form__group">
			<label for="install_forum_settings_chmod_dirs"><?php echo lang('forumSettingChmodNewDirectoriesLabel'); ?></label>
			<input id="install_forum_settings_chmod_dirs" class="b-form__group_control" type="text" name="settings[forum][chmodDirs]" value="<?php echo $settings['forum']['chmodDirs']; ?>" required/>
		</div>

		<div class="b-form__group">
			<label for="install_forum_settings_chmod_files"><?php echo lang('forumSettingChmodNewFilesLabel'); ?></label>
			<input id="install_forum_settings_chmod_files" class="b-form__group_control" type="text" name="settings[forum][chmodFiles]" value="<?php echo $settings['forum']['chmodFiles']; ?>" required/>
		</div>

		<div class="b-form__group">
			<label for="install_forum_settings_chmod_uploads"><?php echo lang('forumSettingChmodUploadsLabel'); ?></label>
			<input id="install_forum_settings_chmod_uploads" class="b-form__group_control" type="text" name="settings[forum][chmodUploads]" value="<?php echo $settings['forum']['chmodUploads']; ?>" required/>
		</div>
	</fieldset>

	<!--fieldset>
		<legend><?php echo lang('otherSettings'); ?></legend>

		<div class="b-form__group">
			<label for="install_forum_settings_demo_data"><?php echo lang('forumSettingInstallDemoData'); ?></label>
			<select id="install_forum_settings_demo_data" class="b-form__group_control" name="settings[forum][demodata]">
				<option value="1"<?php echo ($settings['forum']['demodata']) ? 'selected' : ''; ?>><?php echo lang('yes'); ?></option>
				<option value="0"<?php echo (!$settings['forum']['demodata']) ? 'selected' : ''; ?>><?php echo lang('no'); ?></option>
			</select>
		</div>
	</fieldset-->

	<div class="b-installation-form__buttons">
		<button type="submit" class="b-button"><?php echo lang('continueInstallation'); ?></button>
	</div>
</form>