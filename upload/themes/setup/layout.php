<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>68 KB Setup</title>
<script type="text/javascript" src="<?php echo base_url();?>javascript/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<link href="<?php echo base_url();?>themes/admin/default/style/default.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
function disableDefault(){
	event.returnValue = false;
	return false;
}
//-->
</script>
</head>

<body> 
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

	<div id="wrapper">
		<div id="header"></div>
		
		<div id="content">

		
			<!-- // Content // -->
			
			<?php echo $body; ?>	  
			
			<!-- // End Content // -->
		
		</div>
</div>

<div id="footer">
	&copy; 2009 68 KB - <?php echo KB_VERSION; ?> <br />
	Time: <?=$this->benchmark->elapsed_time();?> - Memory: <?=$this->benchmark->memory_usage();?>
</div>

</body>
</html>