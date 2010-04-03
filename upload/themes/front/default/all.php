<h1><?php echo lang('kb_all_articles'); ?></h1>

<div id="all">
<?php foreach($parents->result() as $row):  ?>

	<h2>
		<a href="<?php echo site_url("category/".$row->cat_uri."/"); ?>"><?php echo $row->cat_name; ?></a>
		<a rel="nofollow" title="<?php echo $row->cat_name; ?> RSS Feed" href="<?php echo site_url('rss/category/'.$row->cat_uri); ?>"><img src="<?php echo base_url(); ?>themes/front/<?php echo $settings['template']; ?>/images/icon-rss.gif" /></a>
	</h2>
	
	<p><?php echo $row->cat_description; ?></p>
	
	<?php  if (isset($articles[$row->cat_id]) && $articles[$row->cat_id]->num_rows() > 0): ?>
		<ul class="articles">
		<?php foreach($articles[$row->cat_id]->result() as $row): ?>
			<li>
				<a href="<?php echo site_url("article/".$row->article_uri."/"); ?>"><?php echo $row->article_title; ?></a><br />
				<!--<?php echo $row->article_short_desc; ?>-->
			</li>

		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	
<?php endforeach; ?>
</div>