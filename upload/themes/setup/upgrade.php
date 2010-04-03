<h2>Upgrade Complete</h2>
<div id="form" class="wrap">

	<p><strong>Everything upgraded succesfully !</strong></p>
	
	<div class="warning"><p><font color="#FF0000">Now please delete the <strong>includes/application/controllers/setup</strong> directory</font></p></div>
	
	<p><a href="<?php echo site_url('admin'); ?>">Click here</a> to visit your admin panel to modify the site settings.</p>

</div>
<hr />

<h2>Upgrade Log</h2>
<div id="form" class="wrap">
	<ul>
	<?php foreach($log as $row): ?>
		<?php foreach($row as $message): ?>
			<li><?php echo $message; ?></li>
		<?php endforeach; ?>
	<?php endforeach; ?>
	</ul>
</div>