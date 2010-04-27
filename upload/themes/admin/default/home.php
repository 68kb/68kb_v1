<script language="javascript" type="text/javascript">
<!--
	$(document).ready(function() {
		$.ajax({
			url: '<?php echo site_url('/admin/kb/get_news'); ?>',
			type: 'get',
			success: function (msg) {
				$("#rssnews").html(msg);
			}
		});
	});
-->
</script>


	<?php if(isset($first_time)) { ?>
		<div class="warning"><p><?php echo lang('kb_first_time'); ?></p></div>
    <?php } elseif($install==TRUE) { ?>
    	<div class="warning"><p>Please delete the includes/application/controllers/setup folder.</p></div>
    <?php } ?>
    <?php if($settings['version'] <> $latest) { ?>
		<div class="warning"><p><?php echo $latest; ?> <?php echo lang('kb_update_1'); ?> <a href="http://68kb.com/download/"><?php echo lang('kb_update_2'); ?></a>.</p></div>
	<?php } ?>
	
    
<h2><?php echo lang('kb_welcome'); ?> <?php //echo $username; ?></h2>
<div class="wrap">

    <table width="100%"  border="0" cellspacing="3" cellpadding="3">
			<tr>
				<td width="50%" valign="top">
		  
					<table width="100%"  border="0" cellspacing="3" cellpadding="3">
						<tr>
							<td width="25%"><?php echo lang('kb_running'); ?></td>
							<td width="18%"><?php echo $settings['version']; ?></td>
						</tr>
						<tr>
							<td><?php echo lang('kb_total_articles'); ?></td>
							<td><?php echo $articles; ?></td>
						</tr>
						<tr>
							<td><?php echo lang('kb_total_categories'); ?></td>
							<td><?php echo $cats; ?></td>
						</tr>
						<tr>
							<td><?php echo lang('kb_total_comments'); ?></td>
							<td><?php echo $comment_count; ?></td>
						</tr>
					</table>
					<form name="listings" id="listings" method="post" action="<?php echo site_url('admin/articles/grid'); ?>">
						<table width="100%"  border="0" cellpadding="3" cellspacing="0" class="modules">
								<tr>
									<td colspan="2" class="moduleslinks" align="left"><strong><?php echo lang('kb_article_search'); ?></strong></td>
								</tr>
								<tr>
									<td align="left"><?php echo lang('kb_search_text'); ?></td>
									<td align="left"><input type="text" name="searchtext" /> <input type="submit" id="submit" value="<?php echo lang('kb_search'); ?> &#187;" class="button" /></td>
								</tr>
							</table>
					</form>
				</td>
				<td width="58%" valign="top">
					
					
					<table width="100%"  border="0" cellpadding="3" cellspacing="0" class="modules">
							<tr>
								<td class="moduleslinks" align="left"><strong><?php echo lang('kb_latest_news'); ?></strong></td>
							</tr>
							<tr>
								<td>
									<div id="rssnews"><img src="<?php echo base_url();?>images/ajax-loader.gif" alt="Loading" /> <?php echo lang('kb_loading'); ?></div>
								</td>
							</tr>
					</table>
					
				</td>
			</tr>
		</table>

	
	<?php if($settings['comments'] == 'Y' && $comments->num_rows() > 0): ?>
		<h2><?php echo lang('kb_recent_comments'); ?></h2>
		<table class="main" width="100%" cellpadding="3" cellspacing="1">
			<tr>
				<th><?php echo lang('kb_name'); ?></th>
				<th><?php echo lang('kb_content'); ?></th>
				<th><?php echo lang('kb_article'); ?></th>
				<th><?php echo lang('kb_status'); ?></th>
			</tr>
			<?php  $alt = true; foreach($comments->result_array() as $item): ?>
			<tr<?php if ($alt) echo ' class="row1"'; else echo ' class="row2"'; $alt = !$alt; ?>>
				<td>
					<div class="gravatar"><img class="gravatar2" src="<?php echo gravatar( $item['comment_author_email'], "PG", "24", "wavatar" ); ?>" /></div>
					<strong>
						<?php echo $item['comment_author']; ?>
					</strong><br />
					<?php echo $item['comment_author_email']; ?><br />
					<?php echo $item['comment_author_IP']; ?>
				</td>
				<td>
					<div class="submitted"><?php echo lang('kb_date_added') .' '. date($this->config->item('comment_date_format'), $item['comment_date']); ?></div><br />
						
					<?php echo word_limiter($item['comment_content'], 15); ?><br />
					<a href="<?php echo site_url('admin/comments/edit/'.$item['comment_ID']); ?>"><?php echo lang('kb_edit'); ?></a>
				</td>
				<td valign="top">
					<a href="<?php echo site_url('article/'.$item['article_uri']); ?>/#comment-<?php echo $item['comment_ID']; ?>"><?php echo $item['article_title']; ?></a>
				</td>
				<td>
					<?php
					if($item['comment_approved'] == 'spam') {
						echo '<span class="spam">'.lang('kb_spam').'</span>';
					} elseif($item['comment_approved'] == 0) {
						echo '<span class="inactive">'.lang('kb_notactive').'</span>';
					} else {
						echo '<span class="active">'.lang('kb_active').'</span>';
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>

	<div class="clear"></div>
</div>
<br />

