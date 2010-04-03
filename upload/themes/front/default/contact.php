<script language="javascript" type="text/javascript">
<!-- //
function checkform(frm) 
{
	if (frm.subject.value == "") {alert("<?php echo lang('kb_please_enter'); ?> '<?php echo lang('kb_subject'); ?>'."); frm.subject.focus(); return (false);}
	if (frm.email.value == "") {alert("<?php echo lang('kb_please_enter'); ?> '<?php echo lang('kb_email'); ?>'."); frm.email.focus(); return (false);}
	if (frm.content.value == "") {alert("<?php echo lang('kb_please_enter'); ?> '<?php echo lang('kb_content'); ?>'."); frm.content.focus(); return (false);}
}
//-->
</script>

<?php echo validation_errors(); ?>

<form action="<?php echo site_url('contact/index'); ?>" method="post" id="comment_form" onsubmit="return checkform(this)">
	
	<p><input class="text_input" type="text" name="subject" id="subject" value="<?php echo set_value('subject'); ?>" tabindex="1" /><label for="subject"><strong><?php echo lang('kb_subject'); ?></strong></label></p>
	<p><input class="text_input" type="text" name="name" id="name" value="<?php echo set_value('name'); ?>" tabindex="2" /><label for="name"><strong><?php echo lang('kb_name'); ?></strong></label></p>
	<p><input class="text_input" type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" tabindex="3" /><label for="email"><strong><?php echo lang('kb_email'); ?></strong></label></p>
	<p><textarea class="text_input text_area" name="content" id="content" rows="7" tabindex="4"><?php echo set_value('content'); ?></textarea></p>
	<p><?php echo $cap['image']; ?></p>
	<p><input type="text" name="captcha" value="" /><label for="captcha"><strong><?php echo lang('kb_captcha'); ?></strong></label></p>
	<p>
		<input name="submit" class="form_submit" type="submit" id="submit" tabindex="5" value="Submit" />
	</p>				
</form>