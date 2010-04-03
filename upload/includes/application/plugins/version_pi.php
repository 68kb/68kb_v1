<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 68KB
 *
 * An open source knowledge base script
 *
 * @package		68kb
 * @author		68kb Dev Team
 * @copyright	Copyright (c) 2009, 68 Designs, LLC
 * @license		http://68kb.com/user_guide/license.html
 * @link		http://68kb.com
 * @since		Version 1.0
 */


// ------------------------------------------------------------------------

/**
 * Check for latest release
 * 
*/
function checklatest()
{
	// Home call details
	$product_id = 1;
	$home_url_site = '68kb.com';
	$home_url_port = 80;
	$home_url_kb = '/68downloads/version.php';
	$fsock_terminate = false;

	// Build request
	$request = 'remote=version&product_id='.urlencode($product_id);
		
	$request = $home_url_kb.'?'.$request;
		
	// Build HTTP header
	$header  = "GET $request HTTP/1.0\r\nHost: $home_url_site\r\nConnection: Close\r\nUser-Agent: 68kb (www.68kb.com)\r\n";
	$header .= "\r\n\r\n";
		
	// Contact license server
	$fpointer = fsockopen($home_url_site, $home_url_port, $errno, $errstr, 5);
	$return = '';
	if ($fpointer) 
	{
		fwrite($fpointer, $header);
		while(!feof($fpointer)) 
		{
			$return .= fread($fpointer, 1024);
		}
		fclose($fpointer);
	}
	else
	{
		($fsock_terminate) ? exit : NULL;
	}
	
	// Get rid of HTTP headers
	$content = explode("\r\n\r\n", $return);
	$content = explode($content[0], $return);
	
	// Assign version to var
	$version = trim($content[1]);
	
	// Clean up variables for security
	unset($home_url_site, $home_url_kb, $request, $header, $return, $fpointer, $content);
	
	return $version;
}

/* End of file version_pi.php */
/* Location: ./upload/includes/application/plugins/version_pi.php */ 