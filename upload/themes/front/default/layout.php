<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<link href="<?php echo base_url();?>themes/front/default/css/960.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>themes/front/default/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/tooltip.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jfav.js"></script>
</head>
<body>

<div id="content_bg">

<div class="container_16" id="header">
	<div class="grid_16">
	<h1><?php echo $settings['site_name']; ?></h1>
	</div>
</div>

<div class="container_16 clearfix">
	
	<div class="grid_12">
		<div id="content">
			<?php echo $body; ?>
		</div>
	</div>
	
	<div class="grid_4">
		<div id="sidebar">
			<ul>
				<li><h2>Navigation</h2>
					<ul>
						<li><a href="<?php echo site_url(); ?>">Knowledge Base Home</a></li>
						<li><a href="<?php echo site_url('all'); ?>"><?php echo lang('kb_all_articles'); ?></a></li>
						<li><a href="<?php echo site_url('glossary'); ?>"><?php echo lang('kb_glossary'); ?></a></li>
					</ul>
				</li>
				
				<li><h2><?php echo lang('kb_categories'); ?></h2>
				<?php echo list_categories(); ?>
				</li>
			</ul>
		</div>
	</div>
	
</div>
</div>

</body>
</html>