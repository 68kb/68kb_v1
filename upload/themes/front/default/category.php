<?php if(isset($cat)): ?>
	<h2 class="catHeading">
		<?php echo $cat->cat_name; ?>
		<a rel="nofollow" title="<?php echo $cat->cat_name; ?> RSS Feed" href="<?php echo site_url('rss/category/'.$cat->cat_uri); ?>"><img src="<?php echo base_url(); ?>themes/front/<?php echo $settings['template']; ?>/images/icon-rss.gif" /></a>
	</h2>
	<p class="catDescription"><?php echo $cat->cat_description; ?></p>
<?php endif; ?>

<?php
/**
 * Show sub categories
 */
?>
<?php  if ($parents->num_rows() > 0): ?>
	<table width="100%" border="0">
		<tr>
			<th><?php echo $this->lang->line('kb_categories'); ?></th>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<?php 
					$perline = 2;
					$set = $perline;
					foreach($parents->result() as $row): 
						if(($set%$perline) == 0){
	        			  echo  "<tr>";
	       				}
					?>
						<td width="33%">
							<div class="folder"><a href="<?php echo site_url("category/".$row->cat_uri."/"); ?>"><?php echo $row->cat_name; ?></a></div>
						</td>
					<?php
						if((($set+1)%$perline) == 0){
	           				echo "</tr>";
						}
						$set = $set+1;
					?>
					<?php endforeach; ?>
				</table>
			</td>
		</tr>
	</table>
<?php endif; ?>

<?php
/**
 * Show articles
 */
?>
<?php  if (isset($articles) && $articles->num_rows() > 0): ?>
	<ul class="articles">
	<?php foreach($articles->result() as $row): ?>
		<li>
			<a href="<?php echo site_url("article/".$row->article_uri."/"); ?>"><?php echo $row->article_title; ?></a> 
			
			<?php if($settings['comments'] == 'Y'): ?>
			<?php
				// This is a hack at best. Calling model from view.
				// Bad Eric, no doughnut!
			?>
			<span>(<a class="comments" href="<?php echo site_url("article/".$row->article_uri."/#comments"); ?>"><?php echo $this->comments_model->get_article_comments_count($row->article_id); ?></a>)</span>
			<?php endif; ?>
			
			<br />
			<?php echo $row->article_short_desc; ?>
		</li>
	<?php endforeach; ?>
	</ul>
	
	<?php if($pagination): ?>
		<div class="paginationNum">
			<?php echo lang('kb_pages'); ?>: <?php echo $pagination; ?>
		</div>	
	<?php endif; ?>
		
<?php endif; ?>