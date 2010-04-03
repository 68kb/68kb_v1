<?php if(isset($article)):?>
	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			$('a.rating').click(function(event) {
				event.preventDefault();
				var id = this.id.replace('rate_', "");
				var val = (id == 'up') ? "1" : "-1";
				alert(val);
			})
		});
	</script>
	
	
	<h1 id="article_heading"><?php echo $article->article_title; ?></h1>
	<div class="meta">
		<?php echo lang('kb_author'); ?>
		<?php echo $author->firstname; ?> <?php echo $author->lastname; ?>
		<?php echo lang('kb_on'); ?> 
		<?php echo date($this->config->item('article_date_format'), $article->article_date); ?> |
		<a href="<?php echo site_url('article/printer/'.$article->article_uri); ?>"><?php echo lang('kb_print'); ?></a> | 
		<a id="bookmark"><?php echo lang('kb_bookmark'); ?></a>
		<script language="javascript" type="text/javascript">
			$(document).ready(function(){
				$('#bookmark').jFav();
			});
		</script>
	</div>
	
	<?php echo $article->article_description; ?>
	
	
	<div id="rating">
		<h3><?php echo lang('kb_helpful'); ?></h3>
		<?php if ($this->session->flashdata('rating')) { ?>
			<?php echo lang('kb_rate_success'); ?>
		<?php } else { ?>
		<form method="post" action="<?php echo site_url('article/rate'); ?>">
			<?php echo lang('kb_yes'); ?> <input type="radio" name="rating" value="1" />
			<?php echo lang('kb_no'); ?> <input type="radio" name="rating" value="-1" />
			<input type="submit" name="submit" value="<?php echo lang('kb_rate'); ?>" />
			<input type="hidden" name="article_id" value="<?php echo $article->article_id; ?>" />
			<input type="hidden" name="article_uri" value="<?php echo $article->article_uri; ?>" />
		</form>
		<?php } ?>
	</div>
	
	<a name="attachments"></a>
	<?php if ($attach->num_rows() > 0): ?>
	
		<fieldset>
			<legend><?php echo lang('kb_attachments'); ?></legend>
				<ul>
					<?php  foreach($attach->result() as $item): ?>
					<li><a href="<?php echo base_url(); ?>uploads/<?php echo $article->article_id .'/'. $item->attach_name; ?>" target="_blank"><?php echo $item->attach_name; ?></a></li>
					<?php endforeach; ?>
				</ul>
		</fieldset>
	
	<?php endif; //end attachments ?>


	
	<div class="meta">
		
		<?php  if(isset($article_cats) && $article_cats->num_rows() > 0): ?>
		
			<p>
				<strong><?php echo lang('kb_category'); ?>:</strong> 
				<?php 
					// This is a hack to remove final comma.
					$count = count($article_cats->result()); 
					$i=0;
					foreach($article_cats->result() as $row) { 
						$i++;
				?>
					<a href="<?php echo site_url('category/'.$row->cat_uri); ?>"><?php echo $row->cat_name; ?></a><?php if($i < $count) echo ','; ?>
				<?php } ?>
			</p>
		
		<?php endif; //end article_cats ?>
		
		<p><?php echo lang('kb_last_updated'); ?> <?php echo date($this->config->item('article_date_format'), $article->article_modified); ?> with <?php echo $article->article_hits; ?> views</p>
		
	</div>


	<?php if($settings['comments'] == 'Y'): ?>
		<?php echo $comment_template; ?>
	<?php endif; //end allow comments ?>
	
	
<?php else: ?>
	<p><?php echo lang('kb_article_not_available'); ?></p>
<?php endif; ?>
