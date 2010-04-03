<div id="tabs">
	<ul>
		<li><a href="<?php echo site_url('admin/stats/');?>"><span><?php echo $this->lang->line('kb_summary'); ?></span></a></li>
		<li><a href="<?php echo site_url('admin/stats/viewed');?>"><span><?php echo $this->lang->line('kb_most_viewed'); ?></span></a></li>
		<li><a href="<?php echo site_url('admin/stats/searchlog');?>" class="active"><span><?php echo $this->lang->line('kb_search_log'); ?></span></a></li>
		<li><a href="<?php echo site_url('admin/stats/rating');?>"><span>Rating</span></a></li>
	</ul>
</div>
<div class="clear"></div>

<div class="wrap">
	
	<table width="100%" class="main" cellpadding="5" cellspacing="1">
		<tr>
			<th><?php echo $this->lang->line('kb_term'); ?></th><th><?php echo $this->lang->line('kb_searches'); ?></th>
		</tr>
		<?php $alt = true; foreach ($query->result() as $row): ?>
		<tr<?php if ($alt) echo ' class="row1"'; else echo ' class="row2"'; $alt = !$alt; ?>>
			<td><?php echo $row->searchlog_term; ?></td><td><?php echo $row->total; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	
</div>