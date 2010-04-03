<div id="tabs">
	<ul>
		<li><a href="<?php echo site_url('admin/stats/');?>"><span><?php echo lang('kb_summary'); ?></span></a></li>
		<li><a href="<?php echo site_url('admin/stats/viewed');?>"><span><?php echo lang('kb_most_viewed'); ?></span></a></li>
		<li><a href="<?php echo site_url('admin/stats/searchlog');?>"><span><?php echo lang('kb_search_log'); ?></span></a></li>
		<li><a href="<?php echo site_url('admin/stats/rating');?>" class="active"><span>Rating</span></a></li>
	</ul>
</div>
<div class="clear"></div>

<div class="wrap">
	
	<table width="100%" class="main" cellpadding="5" cellspacing="1">
		<tr>
			<th>Title</th>
			<th>Rating</th>
		</tr>
		<?php $alt = true; foreach ($query->result() as $row): ?>
		<tr<?php if ($alt) echo ' class="row1"'; else echo ' class="row2"'; $alt = !$alt; ?>>
			<td><a href="<?php echo site_url('article/'.$row->article_uri.'/'); ?>"><?php echo $row->article_title; ?></a></td><td><?php echo $row->article_rating; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	
</div>