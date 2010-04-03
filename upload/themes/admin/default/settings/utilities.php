<h2><?php echo lang('kb_utilities'); ?></h2>
	<div class="wrap">
		<table>
			<tr>
				<td><a href="<?php echo site_url('admin/utility/optimize'); ?>"><?php echo lang('kb_optimize_db'); ?></a></td>
			</tr>
			<tr>
				<td><a href="<?php echo site_url('admin/utility/repair'); ?>"><?php echo lang('kb_repair_db'); ?></a></td>
			</tr>
			<tr>
				<td><a href="<?php echo site_url('admin/utility/delete_cache'); ?>"><?php echo lang('kb_delete_cache'); ?></a></td>
			</tr>
				<tr>
					<td><a href="<?php echo site_url('admin/utility/export'); ?>"><?php echo lang('kb_export_html'); ?></a></td>
				</tr>
			<tr>
				<td><a href="<?php echo site_url('admin/utility/backup'); ?>"><?php echo lang('kb_backup_db'); ?></a></td>
			</tr>
		</table>
	</div>
	
	<br />
	
	<?php if($this->session->flashdata('message')) echo '<h2>'.$this->session->flashdata('message').'</h2>'; ?>
	
	<?php if(isset($table)): ?>
		<h2><?php echo lang('kb_repair_success'); ?></h2>
		<div class="wrap">
		<ul>
		<?php foreach($table as $key): ?>
			<li><?php echo $key; ?></li>
		<?php endforeach; ?>
		</ul>
		</div>
	<?php endif;?>
	
	<?php if(isset($optimized)): ?>
		<h2><?php echo lang('kb_optimize_success'); ?></h2>
		<div class="wrap">
		<ul>
		<?php foreach($optimized as $key): ?>
			<li><?php echo $key; ?></li>
		<?php endforeach; ?>
		</ul>
		</div>
	<?php endif;?>
	
	<?php if(isset($export)): ?>
		<h2>Export Complete</h2>
		<div class="wrap">
		<ul>
		<?php foreach($export as $key): ?>
			<li><?php echo $key; ?></li>
		<?php endforeach; ?>
		</ul>
		</div>
	<?php endif;?>
	