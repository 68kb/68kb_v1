<h2>Installation Complete</h2>
<div id="form" class="wrap">

	<p><strong>Everything installed succesfully !</strong></p>
	
	<div class="warning"><p><font color="#FF0000">Now please delete the <strong>includes/application/controllers/setup</strong> directory</font></p></div>
	
	<p><a href="<?php echo site_url('admin'); ?>">Click here</a> to visit your admin panel to modify the site settings.</p>
	
	<strong>Install Log</strong>
	<ul>
	<?php foreach($log as $row): ?>
		<li><?php echo $row; ?></li>
	<?php endforeach; ?>
	</ul>
</div>