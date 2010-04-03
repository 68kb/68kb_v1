	<div id="tabs">
		<ul>
			<li><a href="<?php echo site_url('admin/stats/');?>" class="active"><span><?php echo $this->lang->line('kb_summary'); ?></span></a></li>
			<li><a href="<?php echo site_url('admin/stats/viewed');?>"><span><?php echo $this->lang->line('kb_most_viewed'); ?></span></a></li>
			<li><a href="<?php echo site_url('admin/stats/searchlog');?>"><span><?php echo $this->lang->line('kb_search_log'); ?></span></a></li>
			<li><a href="<?php echo site_url('admin/stats/rating');?>"><span>Rating</span></a></li>
		</ul>
	</div>
	<div class="clear"></div>
	
	<div class="wrap">
	
		<table>
			<tr>
				<td>Total Articles: </td><td><?php echo $articles; ?></td>
			</tr>
			<tr>
				<td>Total Article Views: </td><td><?php echo $views; ?></td>
			</tr>
			<tr>
				<td>Total Categories: </td><td><?php echo $cats; ?></td>
			</tr>
		</table>

	</div>