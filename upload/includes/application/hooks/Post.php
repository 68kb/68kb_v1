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
 * is_post
 *
 * This file checks that post data originates from your website. Idea
 * from vBulletin.
 *
 * @package		68kb
 * @subpackage	Hooks
 * @category	Hooks
 * @author		68kb Dev Team
 * @version 	$Id: Post.php 45 2009-07-28 17:20:56Z suzkaw68 $
 */
function is_post()
{
	// Here you can enter allowed websites.
	/*
	$allowed[] = '.test.com';
	$allowed[] = '.paypal.com';
	*/
	
	if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' AND !defined('SKIP_REFERRER_CHECK'))
	{
		if ($_SERVER['HTTP_HOST'] OR $_ENV['HTTP_HOST'])
		{
			$http_host = ($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']);
		}
		else if ($_SERVER['SERVER_NAME'] OR $_ENV['SERVER_NAME'])
		{
			$http_host = ($_SERVER['SERVER_NAME'] ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']);
		}
	
		if ($http_host AND isset($_SERVER['HTTP_REFERER']))
		{
			$http_host = preg_replace('#:80$#', '', trim($http_host));
			$referrer_parts = @parse_url($_SERVER['HTTP_REFERER']);
			$ref_port = intval(@$referrer_parts['port']);
			$ref_host = $referrer_parts['host'] . ((!empty($ref_port) AND $ref_port != '80') ? ":$ref_port" : '');
	
			$allowed[] = preg_replace('#^www\.#i', '', $http_host);
	
			$pass_ref_check = false;
			foreach ($allowed AS $host)
			{
				if (preg_match('#' . preg_quote($host, '#') . '$#siU', $ref_host))
				{
					$pass_ref_check = true;
					break;
				}
			}
			unset($allowed);
	
			if ($pass_ref_check == false)
			{
				die('I am sorry this action is not permitted.');
			}
		}
	}
}

/* End of file Post.php */
/* Location: ./upload/includes/application/hooks/Post.php */ 