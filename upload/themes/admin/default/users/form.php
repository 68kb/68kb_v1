<h2><?php echo lang('kb_manage_users'); ?></h2>

<div id="form">
	<?php if(validation_errors()) {
		echo '<div class="error">'.validation_errors().'</div>';
	} ?>
	<form method="post" action="<?php echo $action; ?>" class="searchform">
	
	
	<p class="row1">
		<label for="username"><?php echo lang('kb_username'); ?>:</label>
		<input type="text" name="username" id="username" class="inputtext" value="<?php echo (isset($art->username)) ? set_value('username', $art->username) : set_value('username'); ?>" />
	</p>
	
	<p class="row2">
		<label for="firstname"><?php echo lang('kb_firstname'); ?>:</label>
		<input type="text" name="firstname" id="firstname" class="inputtext" value="<?php echo (isset($art->firstname)) ? set_value('firstname', $art->firstname) : set_value('firstname'); ?>" />
	</p>
	
	<p class="row1">
		<label for="lastname"><?php echo lang('kb_lastname'); ?>:</label>	
		<input type="text" name="lastname" id="lastname" class="inputtext" value="<?php echo (isset($art->lastname)) ? set_value('lastname', $art->lastname) : set_value('lastname'); ?>" />
	</p>
	
	<p class="row2">
		<label for="email"><?php echo lang('kb_email'); ?>:</label>	
		<input type="text" name="email" id="email" class="inputtext" value="<?php echo (isset($art->email)) ? set_value('email', $art->email) : set_value('email'); ?>" />
	</p>
	
	<p class="row1">
		<label for="level"><?php echo lang('kb_level'); ?>:</label>
		<select name="level" id="level">
			<option value="1"<?php if (isset($art->level) && $art->level == 1) echo ' selected="selected"'; ?>>Administrator</option>
			<option value="2"<?php if (isset($art->level) && $art->level == 2) echo ' selected="selected"'; ?>>Moderator</option>
			<option value="3"<?php if (isset($art->level) && $art->level == 3) echo ' selected="selected"'; ?>>Editor</option>
			<option value="4"<?php if (isset($art->level) && $art->level == 4) echo ' selected="selected"'; ?>>Author</option>
		</select>
	</p>
	
		<?php if(isset($art->id)): ?>
		<div class="warning"><p><?php echo lang('kb_password_change'); ?></p></div></td>
		<?php endif; ?>
	
	<p class="row2">
		<label for="password"><?php echo lang('kb_password'); ?>:</label>	
		<input type="passconf" name="password" id="password" class="inputtext" value="" />
	</p>
	
	<p class="row1">
		<label for="passconf"><?php echo lang('kb_confirmpassword'); ?>:</label>	
		<input type="passconf" name="passconf" id="passconf" class="inputtext" value="" />
	</p>
	
	
	<?php $this->core_events->trigger('users/form', (isset($art->id)) ? $art->id : '');?>
	
	<p style="text-align: center;">
		<input type="submit" name="submit" class="save" value="<?php echo lang('kb_save'); ?>" /> 
	</p>
	
	<input type="hidden" name="id" value="<?php echo (isset($art->id)) ? $art->id : ''; ?>" />
	<input type="hidden" name="action" value="<?php echo $action; ?>" />
	<?php echo form_close(); ?>
	
	<div class="clear"></div>
</div>


