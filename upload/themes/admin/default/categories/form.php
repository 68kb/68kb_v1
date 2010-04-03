<h2><?php echo lang('kb_categories'); ?></h2>
<div id="form" class="wrap">
	<?php if(validation_errors()) {
		echo '<div class="error">'.validation_errors().'</div>';
	} ?>
	<form method="post" action="<?php echo $action; ?>" class="searchform">
		
		<p class="row1">
			<label for="cat_name"><?php echo lang('kb_title'); ?>: <em>(<?php echo lang('kb_required'); ?>)</em></label>
			<input tabindex="1" type="text" class="inputtext" name="cat_name" id="cat_name" value="<?php echo (isset($art->cat_name)) ? set_value('cat_name', $art->cat_name) : set_value('cat_name'); ?>" />
		</p>
		<p class="row2">
			<label for="cat_uri"><?php echo lang('kb_uri'); ?>:</label></td>
			<input tabindex="2" type="text" class="inputtext" name="cat_uri" id="cat_uri" value="<?php echo (isset($art->cat_uri)) ? set_value('cat_uri', $art->cat_uri) : set_value('cat_uri'); ?>" />
		</p>
		
		<p class="row1">
			<label for="cat_description"><?php echo lang('kb_description'); ?>:</label>
			<textarea tabindex="3" id="editcontent" name="cat_description" id="cat_description" cols="15" rows="15" class="inputtext"><?php echo (isset($art->cat_description)) ? set_value('cat_description', $art->cat_description) : set_value('cat_description'); ?></textarea>
		</p>
		
		<?php $this->core_events->trigger('category/form');?>
		
	<table width="100%" cellspacing="0">
		<tr>
			<td class="row2"><label for="cat_parent"><?php echo lang('kb_parent_cat'); ?>:</label></td>
			<td class="row2">
			
				<select tabindex="4" name="cat_parent" id="cat_parent">
					<option value="0"><?php echo lang('kb_no_parent'); ?></option>
					<?php foreach($options as $row): ?>
					<?php $default = ((isset($art->cat_parent) && $art->cat_parent == $row['cat_id'])) ? true : false; ?>
					<option value="<?php echo $row['cat_id']; ?>" <?php echo set_select('cat_parent', $row['cat_id'], $default); ?>><?php echo $row['cat_name']; ?></option>
					<?php endforeach; ?>
				</select>
			
			</td>
		</tr>
		<tr>
			<td class="row1"><label for="cat_order"><?php echo lang('kb_weight'); ?>:</label></td>
			<td class="row1">
				<input tabindex="5" type="text" name="cat_order" id="cat_order" value="<?php echo (isset($art->cat_order)) ? set_value('cat_order', $art->cat_order) : set_value('cat_order'); ?>" />
				<a href="javascript:void(0);" title="<?php echo lang('kb_weight_desc'); ?>" class="tooltip">
				<img src="<?php echo base_url(); ?>images/help.png" border="0" alt="<?php echo lang('kb_edit'); ?>" />	
				</a>
			</td>
		</tr>
		<tr>
			<td class="row2"><label for="cat_display"><?php echo lang('kb_display'); ?>:</label></td>
			<td class="row2">
				<select tabindex="6" name="cat_display" id="cat_display">
					<option value="Y"<?php if(isset($art->cat_display) && $art->cat_display == 'Y') echo ' selected'; ?>><?php echo lang('kb_yes'); ?></option>
					<option value="N"<?php if(isset($art->cat_display) && $art->cat_display == 'N') echo ' selected'; ?>><?php echo lang('kb_no'); ?></option>
				</select>
			</td>
		</tr>
		<?php $this->core_events->trigger('categories/form', (isset($art->cat_id)) ? $art->cat_id : ''); ?>
	</table>
	
	<p><input type="submit" tabindex="7" name="submit" class="save" value="<?php echo lang('kb_save'); ?>" /></p>
	
	<input type="hidden" name="cat_id" value="<?php echo (isset($art->cat_id)) ? $art->cat_id : ''; ?>" />
	<?php echo form_close(); ?>
	
	<div class="clear"></div>
</div>