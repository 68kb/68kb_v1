<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title><?php echo $article->article_title; ?></title>
<style type="text/css">
body, td{font-family:verdana,arial,sans-serif;font-size:80%}
a:link, a:active, a:visited{color:#0000CC}
img{border:0}
pre { font-family: Monaco, Verdana, Sans-serif;}
</style>
<script>
	function Print() {
		document.body.offsetHeight;
		window.print();
	}
</script>
</head>
<body onload="Print()">

	<h2><?php echo $article->article_title; ?></h2>
	<hr>
	<?php echo $article->article_description; ?>
	
	<hr>
	You can view this article online at:<br />
	<?php echo site_url('article/'.$article->article_uri); ?>
</body>
</html>