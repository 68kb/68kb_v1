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
 * Developer Events File
 *
 * @package		68kb
 * @subpackage	Module
 * @category	Module
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/developer/modules.html
 * @version 	$Id: events.php 84 2009-08-05 03:26:00Z suzkaw68 $
 *
 * The class name must be named "yourmodule_events" where your module is the name
 * of the module. For this module it is named "developer". 
 */
class developer_events
{
	/**
	* Class constructor
	*
	* The constructor takes the $core_events as the param.
	* Inside this you will register your events to interact
	* with the core system. 
	*/
	function __construct(&$core_events)
	{
		$core_events->register('display_template', $this, 'profiler');
		$core_events->register('article/description', $this, 'article_description');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Profiler
	* 
	* This is used to dynamically enable the profiler. 
	*
	* In order to use CodeIgniters libraries you must use something like: 
	* $CI =& get_instance();
	*
	* @access	public
	*/
	function profiler()
	{
		$CI =& get_instance();
		$CI->output->enable_profiler(TRUE);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Test article formating
	* 
	* This method shows you how to alter the article content before it is
	* shown. 
	*
	* @access	public
	*/
	function article_description($article)
	{
		if($article['article_id'] == 1)
		{
			$article = 'This is text that came from the developer module.';
			//return $article;
		}
	}
	
}

/* End of file events.php */
/* Location: ./upload/my-modules/jwysiwyg/events.php */ 
