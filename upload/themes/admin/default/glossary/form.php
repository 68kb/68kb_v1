<h2><?php echo lang('kb_glossary'); ?></h2>
<div id="form" class="wrap">
	
	<?php if(validation_errors()) {
		echo '<div class="error">'.validation_errors().'</div>';
	} ?>
	
	<form method="post" action="<?php echo $action; ?>" class="searchform">
	
	<label for="g_term"><?php echo lang('kb_term'); ?>:</label>
	<input type="text" name="g_term" id="cName" class="inputtext" value="<?php echo (isset($art->g_term)) ? set_value('g_term', $art->g_term) : set_value('g_term'); ?>" />
	
	<label for="g_definition"><?php echo lang('kb_definition'); ?>:</label>	
	<textarea name="g_definition" id="editcontent" cols="15" rows="15" class="inputtext"><?php echo (isset($art->g_definition)) ? set_value('g_definition', $art->g_definition) : set_value('g_definition'); ?></textarea>
	
	<?php $this->core_events->trigger('glossary/form');?>
	
	<p><input type="submit" name="submit" class="save" value="<?php echo lang('kb_save'); ?>" /></p>
	
	<input type="hidden" name="g_id" value="<?php echo (isset($art->g_id)) ? set_value('g_id', $art->g_id) : set_value('g_id'); ?>" />
	<?php echo form_close(); ?>
	
	<div class="clear"></div>
</div>