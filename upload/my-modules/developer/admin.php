<h1>ADMIN</h1>
<p>Anything in this file will be shown in the admin layout. </p>

<p>You can add forms or whatever you wish.</p>

<h2>Form Example</h2>

<?php 
	if(isset($_POST['test']))
	{
		echo '<p>You submitted: '. $_POST['test'] .'</p>';
	}
?>

<form method="post" action="<?php echo site_url('/admin/modules/show/developer'); ?>">
	<input type="text" name="test" value="Click Submit" />
	<input type="submit" value="submit" />
</form>

<h2>Database Example</h2>
<p>Below will be a list of a few users:</p>
<?php
$CI =& get_instance();
$CI->db->from('users')->limit('4');
$query = $CI->db->get();
if ($query->num_rows() > 0)
{
	echo '<ul>';
	foreach ($query->result() as $row)
	{
		echo '<li>'.$row->username.'</li>';
	}
	echo '</ul>';
}
?>
<p>Here is the code used: </p>
<pre>
$CI =& get_instance();
$CI->db->from('users')->limit('4');
$query = $CI->db->get();
if ($query->num_rows() > 0)
{
	echo '&lt;ul&gt;';
	foreach ($query->result() as $row)
	{
		echo '&lt;li>'.$row->username.'&lt;/li>';
	}
	echo '&lt;/ul>';
}
</pre>