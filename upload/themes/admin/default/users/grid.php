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

<h2><?php echo lang('kb_manage_users'); ?> <a class="addnew" href="<?php echo site_url('admin/users/add');?>"><?php echo lang('kb_add_user'); ?></a></h2>

	<table class="main" width="100%" cellpadding="5" cellspacing="1">		
			<tr>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="id") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/id/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/id/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_id'); ?>
					</a>
				</th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="username") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/username/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/username/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_username'); ?>
					</a>
				</th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="lastname") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/lastname/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/lastname/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_name'); ?>
					</a>
				</th>
				<th>
					<?php 
						$class='';
						if(isset($orderby) && isset($opp) && $orderby=="email") {
							$class = $opp;
						}
					?>
					<?php if(isset($sort) && $sort == 'desc'): ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/email/asc'); ?>" class="<?php echo $class; ?>">
					<?php else: ?>
					<a href="<?php echo site_url('admin/users/grid/orderby/email/desc'); ?>" class="<?php echo $class; ?>">
					<?php endif; ?>
					<?php echo lang('kb_email'); ?>
					</a>
				</th>
				<th><?php echo lang('kb_actions'); ?></th>
			</tr>
			<?php  $alt = true;  $total=count($items); foreach($items as $item): ?>
			<tr<?php if ($alt) echo ' class="second"'; else echo ' class="first"'; $alt = !$alt; ?>>
				<td><?php echo $item['id']; ?></td>
				<td><?php echo $item['username']; ?></td>
				<td><?php echo $item['lastname']; ?>, <?php echo $item['firstname']; ?></td>
				<td><?php echo $item['email']; ?></td>
				<td>
					<a href="<?php echo site_url('admin/users/edit/'.$item['id']); ?>"><img src="<?php echo base_url(); ?>images/page_edit.png" border="0" alt="<?php echo lang('kb_edit'); ?>" title="<?php echo lang('kb_edit'); ?>" /></a>
					&nbsp;
					<?php if($total > 1): ?>
					<a href="javascript:void(0);" onclick="return deleteSomething('<?php echo site_url('admin/users/delete/'.$item['id']); ?>');"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" title="<?php echo lang('kb_delete'); ?>" /></a>
					<?php else: ?>
					<img src="<?php echo base_url(); ?>images/lock.png" alt="<?php echo lang('kb_default'); ?>" title="<?php echo lang('kb_default'); ?>"/>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
	<div class="paginationNum">
		<?php echo lang('kb_pages'); ?>: <?php echo $pagination; ?>
	</div>
	