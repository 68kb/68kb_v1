<div class="commentarea">
	
	<h1 class="title" id="comments"><?php echo $comments_total; ?> <?php echo lang('kb_user_comments'); ?></h1>

	<div class="commentlist">
		
		<?php  if (isset($comments) && $comments->num_rows() > 0): ?>
			<?php $i=1; foreach($comments->result() as $row): ?>
				<div class="comment clearfix alt"  id="comment-<?php echo $row->comment_ID; ?>">
					<div class="pic">
						<img class="gravatar" src="<?php echo gravatar( $row->comment_author_email, "PG", "40", "wavatar" ); ?>" />							
					</div>
					<div class="thecomment">
						<div class="author"><?php echo $row->comment_author; ?></div>
						<div class="time"><?php echo date($this->config->item('comment_date_format'), $row->comment_date); ?></div>
						<div class="clear"></div>
						<div class="text">
							<p><?php echo nl2br_except_pre(parse_smileys($row->comment_content, $this->config->item('base_url')."/images/")); ?></p>
						</div>
					</div>
				</div>											
			<?php endforeach; ?>
		<?php else: ?>
			<p><?php echo lang('kb_no_comments'); ?></p>
		<?php endif; ?>
	</div>
	
	<a name="comment"></a>
	<h2 class="title"><?php echo lang('kb_leave_comment'); ?></h2>
	
		<?php if(validation_errors()) {
			echo '<div class="error">'.validation_errors().'</div>';
		} ?>
		
		<form action="<?php echo site_url('article/comment'); ?>" method="post" id="comment_form">
		
		<p>
			<input class="text_input" type="text" name="comment_author" id="comment_author" value="<?php echo (isset($comment_author)) ? form_prep($comment_author) : ''; ?>" tabindex="1" />
			<label for="comment_author"><?php echo lang('kb_name'); ?></label>
		</p>
		
		<p>
			<input class="text_input" type="text" name="comment_author_email" id="comment_author_email" value="<?php echo (isset($comment_author_email)) ? form_prep($comment_author_email) : ''; ?>" tabindex="2" /><label for="comment_author_email"><?php echo lang('kb_email'); ?></label>
		</p>
	
		<p>
			<textarea class="text_input text_area" name="comment_content" id="comment_content" rows="7" tabindex="4"></textarea>
		</p>
		
		<p>
			<input name="submit" class="form_submit" type="submit" id="submit" tabindex="5" value="<?php echo lang('kb_save'); ?>" />
			<input type="hidden" name="comment_article_ID" value="<?php echo $article->article_id; ?>" />
			<input type="hidden" name="uri" value="<?php echo $article->article_uri; ?>" />
		</p>
		
		</form>
</div>