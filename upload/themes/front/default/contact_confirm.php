<?php
/**
 * Show articles
 */
?>
<?php  if (isset($articles) && $articles->num_rows() > 0): ?>
	<h2><?php echo lang('kb_contact_related'); ?></h2>
	<ul class="articles">
		<?php foreach($articles->result() as $row): ?>
		<li>
			<a href="<?php echo site_url("article/".$row->article_uri."/"); ?>"><?php echo $row->article_title; ?></a><br />
			<?php echo $row->article_short_desc; ?>
		</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>




<form action="<?php echo site_url('contact/submit'); ?>" method="post">
	
	<input type="hidden" name="subject" id="subject" value="<?php echo $subject; ?>" />
	<input type="hidden" name="name" id="name" value="<?php echo $name; ?>" />
	<input type="hidden" name="email" id="email" value="<?php echo $email; ?>" />
	<input type="hidden" name="content" id="content" value="<?php echo $content; ?>" />
	
	<p><?php echo lang('kb_contact_confirm'); ?></p>
	<p><input name="submit" class="form_submit" type="submit" id="submit" tabindex="5" value="<?php echo lang('kb_submit_message'); ?>" /></p>				
</form>