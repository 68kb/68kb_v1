<?php if (isset($not_allowed)): ?>
	<?php echo lang('kb_not_allowed'); ?>
<?php else: ?>
<meta http-equiv="refresh" content="2;URL=<?php echo site_url($goto);?>" />
<fieldset>
	<legend><?php echo lang('kb_success'); ?></legend>
	<p>
		<?php echo lang('kb_forward'); ?>
		<?php echo anchor($goto, 'click here', array('title' => lang('ads_click_here'))); ?>. 
	
	</p>
</fieldset>
<?php endif; ?>