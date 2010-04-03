<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>js/jquery.fancybox/jquery.fancybox.css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox/jquery.fancybox-1.2.1.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("a.ajax").fancybox();
	});
</script>
<script language="javascript" type="text/javascript">
	<!--
		function deleteSomething(url){
			removemsg = "<?php echo lang('kb_delete_module'); ?>"
			if (confirm(removemsg)) {
				document.location = url;
			}
		}
	// -->
</script>

		<h2><?php echo lang('kb_active_modules'); ?></h2>

			
			<?php if(isset($msg) && $msg != ''): ?>
				<div id="message" class="updated fade"><p><?php echo $msg; ?></p></div>
			<?php elseif($this->session->flashdata('msg')): ?>
				<div id="message" class="updated fade"><p><?php echo $this->session->flashdata('msg'); ?></p></div>
			<?php endif; ?>
			
				<table width="100%"  border="0" cellspacing="0" cellpadding="3" class="main">
					<tr>
						<th><?php echo lang('kb_name'); ?></th>
						<th><?php echo lang('kb_description'); ?></th>
						<th width="5%"><?php echo lang('kb_version'); ?></th>
						<th><?php echo lang('kb_actions'); ?></th>
					</tr>
					<?php $alt = true; foreach($modules as $row): ?>
						<tr<?php if ($alt) echo ' class="second"'; else echo ' class="first"'; $alt = !$alt; ?>>
							<td nowrap>
								<?php echo $row['displayname']; ?>
								<?php if(file_exists(KBPATH.'my-modules/'.$row['name'].'/admin.php')): ?>
									<br /><a href="<?php echo site_url('admin/modules/show/'.$row['name']); ?>"><?php echo lang('kb_admin'); ?></a>
								<?php endif; ?>
							</td>
							<td>
								<?php echo $row['description']; ?> 
								<?php if(isset($row['help_file'])) echo '<a class="ajax" title="'.$row['displayname'].'" href="'.$row['help_file'].'">Help</a>'; ?>
							</td>
							<td>
								<?php echo $row['version']; ?>
								<?php if($row['server_version'] > $row['version']): ?>
									<br /><a href="<?php echo site_url('admin/modules/upgrade/'.$row['id']); ?>"><?php echo lang('kb_upgrade_module'); ?></a>
								<?php endif; ?>
							</td>
							<td nowrap>
								<?php if($row['active'] == 1): ?>
									<span class="active">
										<a href="<?php echo site_url('admin/modules/manage/'.$row['id'].'/deactivate'); ?>"><?php echo lang('kb_deactivate'); ?></a>
									</span>
								<?php else: ?>
									<span class="inactive"><a href="<?php echo site_url('admin/modules/manage/'.$row['id'].'/activate'); ?>"><?php echo lang('kb_activate'); ?></a></span>
								<?php endif; ?>
								
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				
				
			<h2><?php echo lang('kb_deactive_modules'); ?></h2>

					<table width="100%"  border="0" cellspacing="0" cellpadding="3" class="main">
						<tr>
							<th><?php echo lang('kb_name'); ?></th>
							<th><?php echo lang('kb_description'); ?></th>
							<th width="5%"><?php echo lang('kb_version'); ?></th>
							<th><?php echo lang('kb_actions'); ?></th>
						</tr>
						<?php $alt = true; foreach($unactive as $row): ?>
							<tr<?php if ($alt) echo ' class="second"'; else echo ' class="first"'; $alt = !$alt; ?>>
								<td nowrap>
									<?php echo $row['displayname']; ?>
								</td>
								<td><?php echo $row['description']; ?></td>
								<td><?php echo $row['version']; ?></td>
								<td nowrap>
										<span class="inactive"><a href="<?php echo site_url('admin/modules/activate/'.$row['name']); ?>"><?php echo lang('kb_activate'); ?></a>
										&nbsp; 
										<a href="javascript:void(0);" onclick="deleteSomething('<?php echo site_url('admin/modules/manage/'.$row['name'].'/delete'); ?>')"><img src="<?php echo base_url(); ?>images/page_delete.png" border="0" alt="<?php echo lang('kb_delete'); ?>" title="<?php echo lang('kb_delete'); ?>" /></a>
										</span>
									
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
		