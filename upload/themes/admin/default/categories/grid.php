<script language="javascript" type="text/javascript">
	<!--
		function deleteSomething(url){
			removemsg = "<?php echo lang('kb_are_you_sure'); ?>"
			if (confirm(removemsg)) {
				document.location = url;
			}
		}
	// -->
</script>

<h2><?php echo lang('kb_categories'); ?> <a class="addnew" href="<?php echo site_url('admin/categories/add');?>"><?php echo lang('kb_add_category'); ?></a></h2>

	
	<table class="main" width="100%" cellpadding="0" cellspacing="0">	
		<tr>
				<th><?php echo lang('kb_id');?></th>
				<th><?php echo lang('kb_title');?></th>
				<th><?php echo lang('kb_description');?></th>
				<th><?php echo lang('kb_actions');?></th>
		</tr>
		<?php  $alt = true; foreach($options as $row): ?>
			<tr<?php if ($alt) echo ' class="second"'; else echo ' class="first"'; $alt = !$alt; ?>>
				<td><?php echo $row['cat_id']; ?></td>
				<td class="title" nowrap><a href="<?php echo site_url('admin/categories/edit/'.$row['cat_id']); ?>"><?php echo $row['cat_name']; ?></a></td>
				<td><?php echo $row['cat_description']; ?></td>
				<td width="15%" nowrap>
					<a href="<?php echo site_url('admin/categories/edit/'.$row['cat_id']); ?>"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo lang('kb_edit'); ?>" title="<?php echo lang('kb_edit'); ?>" /></a>
					&nbsp;
					<a href="<?php echo site_url('admin/categories/duplicate/'.$row['cat_id']); ?>"><img src="<?php echo base_url(); ?>images/page_copy.png" border="0" alt="<?php echo lang('kb_duplicate'); ?>" title="<?php echo lang('kb_duplicate'); ?>" /></a>
					&nbsp;
					<a href="javascript:void(0);" onclick="deleteSomething('<?php echo site_url('admin/categories/delete/'.$row['cat_id']); ?>')"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" title="<?php echo lang('kb_delete'); ?>" /></a>
				</td>
			</tr>
			<?php endforeach; ?>
	</table>
	
	<br />
	
	<table width="30%" align="center">
			<tr>
				<td align="right"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo lang('kb_edit'); ?>" /></td>
				<td align="left"><?php echo lang('kb_edit'); ?></td>  
				<td align="right"><img src="<?php echo base_url(); ?>images/page_copy.png" border="0" alt="<?php echo lang('kb_duplicate'); ?>" /></td>
				<td align="left"><?php echo lang('kb_duplicate'); ?></td> 
				<td align="right"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" /></td>
				<td align="left"><?php echo lang('kb_delete'); ?></td>
				<td align="right"><img src="<?php echo base_url(); ?>images/lock.png" border="0" alt="<?php echo lang('kb_default'); ?>" /></td>
				<td align="left"><?php echo lang('kb_default'); ?></td>
			</tr>
		</table>
