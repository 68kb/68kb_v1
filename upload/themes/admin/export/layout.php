<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php echo $title; ?> : <?php echo $settings['site_name']; ?></title>
<link href="css/960.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/master.css" rel="stylesheet" type="text/css" media="all" />
<script src="jquery-treeview/lib/jquery.js"></script>
<script src="jquery-treeview/lib/jquery.cookie.js"></script>
<script src="jquery-treeview/jquery.treeview.js"></script>

<link rel="stylesheet" href="jquery-treeview/jquery.treeview.css" type="text/css" />

<script>
	$(document).ready(function(){
		$("#browser").treeview({
			control: "#treecontrol",
			persist: "cookie",
			cookieId: "treeview-black"
		});
	});
</script>

<meta http-equiv='expires' content='-1' />
<meta http-equiv= 'pragma' content='no-cache' />
<meta name='robots' content='all' />

</head>
<body>

	<!-- START NAVIGATION -->
	
	<div id="nav">
		<div class="container_12">
			<div id="logo" class="grid_6">
				<h1><?php echo $settings['site_name']; ?></h1>
			</div>
			<div id="search" class="grid_6">
				<form method="get" action="http://www.google.com/search"><input type="hidden" name="as_sitesearch" id="as_sitesearch" value="68kb.com/user_guide/" />Search User Guide&nbsp; <input type="text" class="input" style="width:200px;" name="q" id="q" size="31" maxlength="255" value="" />&nbsp;<input type="submit" class="submit" name="sa" value="Go" /></form>
			</div>
		</div>
	</div>
	<!-- END NAVIGATION -->
	
	
	<div class="container_12">
		<div class="grid_12">
			<a name="top">&nbsp;</a>
				<div id="breadcrumb" class="box">
					<a href="<?php echo site_url(); ?>">68KB Home</a> &nbsp;&#8250;&nbsp;
					<a href="./index.html">User Guide Home</a> &nbsp;&#8250;&nbsp; <?php echo $title; ?>
				</div>
		</div>
		
		<div id="content" class="grid_9">
			<div class="box">
				<h1><?php echo $title; ?></h1>
				<?php echo $description; ?>
			</div>	
		</div>
		<div id="sidebar" class="grid_3">
			<div id="treecontrol">
				<a title="Collapse the entire tree below" href="#"><img src="jquery-treeview/images/minus.gif" /> Collapse All</a>
				<a title="Expand the entire tree below" href="#"><img src="jquery-treeview/images/plus.gif" /> Expand All</a>
				<a title="Toggle the tree below, opening closed branches, closing open branches" href="#">Toggle All</a>
			</div>
			<?php echo $navigation; ?>
		</div>
		
		<div class="clear"></div>
	
	</div>
					
						

	<!--START Footer Section-->
	<div id="footer">
		<p>Powered by <a href="http://68kb.com">68 Knowledge Base</a> &nbsp;&middot;&nbsp; <a href="#top">Top of Page</a></p>
	</div>

	
	<!--END Footer Section-->

	</body>
</html>
