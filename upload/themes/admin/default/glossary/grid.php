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

<h2><?php echo lang('kb_glossary'); ?> <a class="addnew" href="<?php echo site_url('admin/glossary/add');?>"><?php echo lang('kb_add_term'); ?></a></h2>

	
	<table class="main" width="100%" cellpadding="5" cellspacing="1">		
			<tr>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="g_id") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/glossary/grid/orderby/g_id/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/glossary/grid/orderby/g_id/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_id'); ?>
					</a>
				</th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="g_term") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/glossary/grid/orderby/g_term/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/glossary/grid/orderby/g_term/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_term'); ?>
					</a>
				</th>
				<th><?php echo lang('kb_actions'); ?></th>
			</tr>
			<?php  $alt = true; foreach($items as $item): ?>
			<tr<?php if ($alt) echo ' class="second"'; else echo ' class="first"'; $alt = !$alt; ?>>
				<td width="5%" nowrap><?php echo $item['g_id']; ?></td>
				<td class="title"><a href="<?php echo site_url('admin/glossary/edit/'.$item['g_id']); ?>"><?php echo $item['g_term']; ?></a></td>
				
				<td width="10%" nowrap>
					<a href="<?php echo site_url('admin/glossary/edit/'.$item['g_id']); ?>"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo lang('kb_edit'); ?>" title="<?php echo lang('kb_edit'); ?>" /></a>
					&nbsp;
					<a href="javascript:void(0);" onclick="deleteSomething('<?php echo site_url('admin/glossary/delete/'.$item['g_id']); ?>')"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" title="<?php echo lang('kb_delete'); ?>" /></a>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	<div class="paginationNum">
		<?php echo lang('kb_pages'); ?>: <?php echo $pagination; ?>
	</div>
	
	<table width="20%" align="center">
			<tr>
				<td align="right"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo lang('kb_edit'); ?>" /></td>
				<td align="left"><?php echo lang('kb_edit'); ?></td>  
				<td align="right"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" /></td>
				<td align="left"><?php echo lang('kb_delete'); ?></td>
			</tr>
		</table>
