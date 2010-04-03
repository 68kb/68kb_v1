<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Title      : Emporium
Version    : 1.0
Released   : 20090222
Description: A two-column, fixed-width and lightweight template ideal for 1024x768 resolutions. Suitable for blogs and small websites.

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
<meta name="description" content="<?php echo $meta_description; ?>" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/tooltip.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jfav.js"></script>
<link href="<?php echo base_url();?>themes/front/emporium/default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
<!-- start header -->
<div id="logo">
	<h1><a href="<?php echo site_url(); ?>"><?php echo $settings['site_name']; ?></a></h1>
	<h2> &raquo;&nbsp;&nbsp;&nbsp;<?php echo $settings['site_description']; ?></h2>
</div>
<div id="header">
	<div id="menu">
		<ul>
			<li class="current_page_item"><a href="<?php echo site_url(); ?>">Homepage</a></li>
			<li><a href="<?php echo site_url('all'); ?>">All Articles</a></li>
			<li><a href="<?php echo site_url('glossary'); ?>">Glossary</a></li>
			<li class="last"><a href="<?php echo site_url('contact'); ?>">Contact</a></li>
		</ul>
	</div>
</div>
<!-- end header -->
</div>
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<div class="post">
			<?php echo $body; ?>
		</div>
	</div>
	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		<ul>
			<li id="search">
				<h2>Search</h2>
				<form method="post" action="<?php echo site_url('search'); ?>" class="searchform">
					<fieldset>
					<input type="text" id="s" name="searchtext" value="" />
					<input type="submit" id="x" value="<?php echo lang('kb_search'); ?>" />
					</fieldset>
				</form>
			</li>
			<li>
				<h2><?php echo lang('kb_categories'); ?></h2>
				<?php echo list_categories('orderby=name&show_count=1&exclude=10'); ?>
			</li>
			
		</ul>
	</div>
	<!-- end sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end page -->
<!-- start footer -->
<div id="footer">
	<p id="legal">( c ) 2009. All Rights Reserved. Powered by <a href="http://68kb.com/">68kb</a> and designed by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
<!-- end footer -->
</body>
</html>
