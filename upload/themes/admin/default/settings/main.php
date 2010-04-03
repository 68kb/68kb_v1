<h2><?php echo lang('kb_main_settings'); ?></h2>

<div id="form" class="">
	
	<?php if(validation_errors()) {
		echo '<div class="error">'.validation_errors().'</div>';
	} ?>
	
	<?php echo form_open('admin/settings/main'); ?>
	
	<table width="100%" cellspacing="0">
		<tr>
			<td class="row1"><label for="site_name"><?php echo lang('kb_site_title'); ?>:</label></td>
			<td class="row1"><input type="text" size="50" name="site_name" id="site_name" value="<?php echo (isset($settings['site_name'])) ? form_prep($settings['site_name']) : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="row2"><label for="site_keywords"><?php echo lang('kb_site_keywords'); ?>:</label></td>
			<td class="row2"><input type="text" size="50" name="site_keywords" id="site_keywords" value="<?php echo (isset($settings['site_keywords'])) ? form_prep($settings['site_keywords']) : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="row1"><label for="site_description"><?php echo lang('kb_site_description'); ?>:</label></td>
			<td class="row1"><input type="text" size="50" name="site_description" id="site_description" value="<?php echo (isset($settings['site_description'])) ? form_prep($settings['site_description']) : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="row2"><label for="site_email"><?php echo lang('kb_email'); ?>:</label></td>
			<td class="row2"><input type="text" size="50" name="site_email" id="site_email" value="<?php echo (isset($settings['site_email'])) ? form_prep($settings['site_email']) : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="row1"><label for="max_search"><?php echo lang('kb_max_search'); ?>:</label></td>
			<td class="row1"><input type="text" size="50" name="max_search" id="max_search" value="<?php echo (isset($settings['max_search'])) ? form_prep($settings['max_search']) : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="row2"><label for="max_search"><?php echo lang('kb_allow_comments'); ?>:</label></td>
			<td class="row2">
				<select name="comments">
					<option value="Y"<?php echo (isset($settings['comments']) && $settings['comments'] == 'Y') ? ' selected' : ''; ?>><?php echo lang('kb_yes'); ?></option>
					<option value="N"<?php echo (isset($settings['comments']) && $settings['comments'] == 'N') ? ' selected' : ''; ?>><?php echo lang('kb_no'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1"><label for="cache_time"><?php echo lang('kb_cache_time'); ?>:</label></td>
			<td class="row1">
				<input type="text" size="4" name="cache_time" id="cache_time" value="<?php echo (isset($settings['cache_time'])) ? form_prep($settings['cache_time']) : ''; ?>" />
				<a href="javascript:void(0);" title="<?php echo lang('kb_cache_desc'); ?>" class="tooltip">
				<img src="<?php echo base_url(); ?>images/help.png" border="0" alt="<?php echo lang('kb_edit'); ?>" />	
				</a>
			</td>
		</tr>
		<?php $this->core_events->trigger('settings/form');?>
	</table>
	
	
	<p><input type="submit" name="submit" class="save" value="<?php echo lang('kb_save'); ?>" /></p>
	
	<?php echo form_close(); ?>
	
	<div class="clear"></div>
</div>