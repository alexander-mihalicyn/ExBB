<?php if (!empty($messages)) : ?>
	<?php foreach ($messages as $message) : ?>
		<div class="b-alert b-alert__<?php echo $message['type']; ?>"><?php echo $message['text']; ?></div>
	<?php endforeach; ?>
<?php endif; ?>

<form method="POST" action="index.php?action=adminAccountSettings">
	<fieldset>
		<legend><?php echo lang('adminAccountSettings'); ?></legend>

		<div class="b-form__group">
			<label for="install_admin_account_settings_login"><?php echo lang('adminAccountSettingsLogin'); ?></label>
			<input id="install_admin_account_settings_login" class="b-form__group_control" type="text" name="settings[account][login]" value="<?php echo $settings['account']['login']; ?>" required/>
		</div>

		<div class="b-form__group">
			<label for="install_admin_account_settings_password"><?php echo lang('adminAccountSettingsPassword'); ?></label>
			<input id="install_admin_account_settings_password" class="b-form__group_control" type="password" name="settings[account][password]" value="" required/>
		</div>

		<div class="b-form__group">
			<label for="install_admin_account_settings_confirm_password"><?php echo lang('adminAccountSettingsConfirmPassword'); ?></label>
			<input id="install_admin_account_settings_confirm_password" class="b-form__group_control" type="password" name="settings[account][confirmPassword]" value="" required/>
		</div>

		<div class="b-form__group">
			<label for="install_admin_account_settings_email"><?php echo lang('adminAccountSettingsEmail'); ?></label>
			<input id="install_admin_account_settings_email" class="b-form__group_control" type="text" name="settings[account][email]" value="<?php echo $settings['account']['email']; ?>" required/>
		</div>

	</fieldset>

	<div class="b-installation-form__buttons">
		<button type="submit" class="b-button"><?php echo lang('finishInstallation'); ?></button>
	</div>
</form>