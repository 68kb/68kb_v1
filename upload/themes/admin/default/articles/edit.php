<?php $this->core_events->trigger('articles/form', (isset($art->article_id)) ? $art->article_id : ''); ?>
<h2><?php echo lang('kb_manage_articles'); ?></h2>

<div id="form" class="wrap">
	<?php if(isset($error)) {
		echo '<div class="error">'.$error,'</div>';
	} ?>
	<?php if(validation_errors()) {
		echo '<div class="error">'.validation_errors().'</div>';
	} ?>
	
	<?php
	$attributes = '';//array('id' => 'articles', 'onsubmit' => 'return checkform(this)');
	echo form_open_multipart('admin/articles/edit/'.$art->article_id, $attributes); 
	?>
	
		<p class="row1">
			<label for="article_title"><?php echo lang('kb_title'); ?>: <em>(<?php echo lang('kb_required'); ?>)</em></label>
			<input tabindex="1" type="text"  class="inputtext" name="article_title" id="article_title" value="<?php echo (isset($art->article_title)) ? set_value('article_title', $art->article_title) : set_value('article_title'); ?>" />
		</p>
		
		<p class="row2">
			<label for="article_uri"><?php echo lang('kb_uri'); ?>:</label>
			<input tabindex="2" type="text"  class="inputtext" name="article_uri" id="article_uri" value="<?php echo (isset($art->article_uri)) ? set_value('article_uri', $art->article_uri) : set_value('article_uri'); ?>" />
		</p>
		
		
		<p class="row1">
			<label for="article_keywords"><?php echo lang('kb_keywords'); ?>:</label>
			<input tabindex="3" type="text"  class="inputtext" name="article_keywords" id="article_keywords" value="<?php echo (isset($art->article_keywords)) ? set_value('article_keywords', $art->article_keywords) : set_value('article_keywords'); ?>" />
			<a href="javascript:void(0);" title="<?php echo lang('kb_keywords_desc'); ?>" class="tooltip">
			<img src="<?php echo base_url(); ?>images/help.png" border="0" alt="<?php echo lang('kb_edit'); ?>" />	
			</a>
		</p>
		
		<p class="row2">
			<label for="article_short_desc"><?php echo lang('kb_short_description'); ?>:</label>
			<textarea tabindex="4" name="article_short_desc" id="article_short_desc" cols="15" rows="5" class="inputtext"><?php echo (isset($art->article_short_desc)) ? set_value('article_short_desc', $art->article_short_desc) : set_value('article_short_desc'); ?></textarea>
		</p>				
		
		<p class="row1">
			<label for="article_description"><?php echo lang('kb_content'); ?>: <em>(<?php echo lang('kb_required'); ?>)</em></label>
			<textarea tabindex="5" name="article_description" id="editcontent" cols="35" rows="15" class="inputtext"><?php echo (isset($art->article_description)) ? set_value('article_description', $art->article_description) : set_value('article_description'); ?></textarea>
		</p>
		<?php $this->core_events->trigger('articles/form/description', (isset($art->article_id)) ? $art->article_id : ''); ?>
		
		
		<p class="row2">
			<label for="article_cat"><?php echo lang('kb_category'); ?>:</label>
			<select tabindex="6" id="article_cat" name="cat[]" size="10" multiple="multiple">
				<?php foreach($options as $row): ?>
					<option value="<?php echo $row['cat_id']; ?>"<?php if(isset($row['selected']) && $row['selected']=='Y') echo ' selected'; ?>><?php echo $row['cat_name']; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		
		
		<p class="row1">
			<label for="article_order"><?php echo lang('kb_weight'); ?>:</label>
			<input tabindex="7" type="text" name="article_order" id="article_keywords" value="<?php echo (isset($art->article_order)) ? set_value('article_order', $art->article_order) : set_value('article_order'); ?>" />
			<a href="javascript:void(0);" title="<?php echo lang('kb_article_weight_desc'); ?>" class="tooltip">
			<img src="<?php echo base_url(); ?>images/help.png" border="0" alt="<?php echo lang('kb_edit'); ?>" />	
			</a>
		</p>
		
		<p class="row2">
			<label for="article_display"><?php echo lang('kb_display'); ?>:</label>
			<select tabindex="8" name="article_display" id="article_display">
				<option value="Y"<?php if(isset($art->article_display) && $art->article_display == 'Y') echo ' selected'; ?>><?php echo lang('kb_yes'); ?></option>
				<option value="N"<?php if(isset($art->article_display) && $art->article_display == 'N') echo ' selected'; ?>><?php echo lang('kb_no'); ?></option>
			</select>
		</p>
		
		<p class="row1">
			<label for="userfile"><?php echo lang('kb_attachment'); ?>:</label>
			<input tabindex="9" type="file" id="userfile" name="userfile" size="20" />
		</p>
		
		<a name="attachments"></a>
		<?php if(isset($attach) && $attach->num_rows() > 0): ?>
		<fieldset>
			<legend><?php echo $this->lang->line('kb_attachments'); ?></legend>

				<ul>
			<?php  foreach($attach->result() as $item): ?>
				<li><?php echo $item->attach_name; ?> <a href="<?php echo site_url('admin/articles/upload_delete/'.$item->attach_id); ?>" onclick="return deleteSomething( 'delete', 'You are about to delete this article?\'.\n\'OK\' to delete, \'Cancel\' to stop.' );">Delete</a> </li>
			<?php endforeach; ?>
				</ul>
		</fieldset>
		<?php endif; ?>
		
		<?php $this->core_events->trigger('articles/form', (isset($art->article_id)) ? $art->article_id : ''); ?>
		
		<!--
		<p class="row2">
			<label for="tags"><?php echo lang('kb_tags'); ?>:</label>
			<input tabindex="9.2" type="text" size="55" name="tags" id="tags" value="<?php echo (isset($tags)) ? set_value('tags', $tags) : set_value('tags'); ?>" />
		</p>
		-->
		
	<p style="text-align: right;">
		<input type="submit" tabindex="10" name="submit" class="save" value="<?php echo lang('kb_save'); ?>" /> 
		<input type="submit" tabindex="11" name="save" class="save" value="<?php echo lang('kb_save_and_continue'); ?>" />
	</p>
	
	<?php echo form_close(); ?>
	
	<div class="clear"></div>
		
</div>
