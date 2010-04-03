<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>68 KB Administration</title>
<link href="<?php echo base_url();?>themes/admin/default/style/default.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
<!--
function checkform(frm) {
    if (frm.username.value == '') {
    		alert("<?php echo lang('kb_please_enter'); ?> \"<?php echo lang('kb_username');?>\".");
    		frm.username.focus();
    		return(false);
    }
    if (frm.password.value == '') {
    		alert("<?php echo lang('kb_please_enter'); ?> \"<?php echo lang('kb_password');?>\".");
    		frm.password.focus();
    		return(false);
    }
}
function setfocus()
{
    document.forms[0].username.focus()
}
//-->
</script>
</head>

<body onload="setfocus();"> 
	<?php
	$attributes = '';//array('id' => 'login', 'onsubmit' => 'return checkform(this)');
	echo form_open('admin/kb/login', $attributes); 
	?>
		<div id="loginwrapper">
			<div id="header"></div>
				<div id="content">
				<h2><?php echo lang('kb_please_login');?></h2>
				<div class="wrap">
					<fieldset>
						<legend><?php echo lang('kb_enter_details'); ?></legend>
						<?php echo validation_errors(); ?>
						<table width="100%">
							<?php if (isset($error) && $error<>""): ?>
							<tr>
								<td colspan="2" align="center"><span class="problem" style="padding: 5px; color: red; text-align: center;"><?php echo $error; ?></span></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td><label for="username"><?php echo lang('kb_username');?>:</label></td>
								<td><input name="username" type="text" id="username" tabindex="1" /></td>
							</tr>
							<tr>
								<td><label for="password"><?php echo lang('kb_password');?>:</label></td>
								<td><input name="password" type="password" id="password" tabindex="2" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type="checkbox" name="remember" id="remember" value="Y" />&nbsp;<label for="remember"><?php echo lang('kb_remember_me'); ?></label></td>
							</tr>
							<tr>
								<td colspan="2" valign="top">
								<div align="center">
								<input name="action" type="hidden" id="action" value="login" />
								<input name="submit" type="submit" id="submit" value="Submit" />
								</div>
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
					</div>
</div>
<input type="hidden" name="goto" value="<?php //echo $goto; ?>" />
</form>

<div id="footer">
	&copy; 2008 68 KB <br />
	Time: <?=$this->benchmark->elapsed_time();?> - Memory: <?=$this->benchmark->memory_usage();?>
</div>
</body>
</html>