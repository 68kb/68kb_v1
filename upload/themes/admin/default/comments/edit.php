<script language="javascript" type="text/javascript">
<!-- //
function checkform(frm) 
{
	if (frm.comment_author.value == "") {alert("<?php echo lang('kb_please_enter'); ?> '<?php echo lang('kb_name'); ?>'."); frm.comment_author.focus(); return (false);}
	if (frm.comment_author_email.value == "") {alert("<?php echo lang('kb_please_enter'); ?> '<?php echo lang('kb_email'); ?>'."); frm.comment_author_email.focus(); return (false);}
}
//-->
</script>

<h2><?php echo lang('kb_comments'); ?></h2>
<div id="form" class="wrap">
	
	<?php if(validation_errors()) {
		echo '<div class="error">'.validation_errors().'</div>';
	} ?>
	
	<?php echo form_open('admin/comments/edit'); ?>
	
	<table width="100%" cellspacing="0">
		<tr>
			<td class="row1"><label for="comment_author"><?php echo lang('kb_name'); ?>: <em>(<?php echo lang('kb_required'); ?>)</em></label></td>
			<td class="row1"><input tabindex="1" type="text" name="comment_author" id="comment_author" value="<?php echo (isset($art->comment_author)) ? form_prep($art->comment_author) : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="row2"><label for="comment_author_email"><?php echo lang('kb_email'); ?>: <em>(<?php echo lang('kb_required'); ?>)</em></label></td>
			<td class="row2"><input tabindex="2" type="text" name="comment_author_email" id="comment_author_email" value="<?php echo (isset($art->comment_author_email)) ? form_prep($art->comment_author_email) : ''; ?>" /></td>
		</tr>
		<tr>
			<td class="row1" colspan="2">
				<label for="comment_content"><?php echo lang('kb_content'); ?>:</label>
			</td>
		</tr>
		<tr>
			<td class="row1" colspan="2">
				<textarea tabindex="3" name="comment_content" id="comment_content" cols="15" rows="15" class="inputtext"><?php echo (isset($art->comment_content)) ? form_prep($art->comment_content) : ''; ?></textarea>
			</td>
		</tr>
		<tr>
			<td class="row1"><label for="comment_approved"><?php echo lang('kb_display'); ?>:</label></td>
			<td class="row1">
				<select tabindex="4" name="comment_approved" id="comment_approved">
					<option value="1"<?php echo (isset($art->comment_approved) && $art->comment_approved==1) ? ' selected="selected"' : ''; ?>><?php echo lang('kb_active'); ?></option>
					<option value="0"<?php echo (isset($art->comment_approved) && $art->comment_approved==0) ? ' selected="selected"' : ''; ?>><?php echo lang('kb_notactive'); ?></option>
					<option value="spam"<?php echo (isset($art->comment_approved) && $art->comment_approved=='spam') ? ' selected="selected"' : ''; ?>><?php echo lang('kb_spam'); ?></option>
				</select>
			</td>
		</tr>
		<?php $this->core_events->trigger('comment/form', (isset($art->comment_ID)) ? $art->comment_ID : '');?>
	</table>
	
	<p><input type="submit" tabindex="6" name="submit" class="save" value="<?php echo lang('kb_save'); ?>" /></p>
	
	<input type="hidden" name="comment_ID" value="<?php echo (isset($art->comment_ID)) ? $art->comment_ID : ''; ?>" />
	<input type="hidden" name="action" value="<?php echo $action; ?>" />
	<?php echo form_close(); ?>
	
	<div class="clear"></div>
</div>