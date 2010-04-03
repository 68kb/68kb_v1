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
 * Events Model
 *
 * This file works with the module system
 *
 * @package		68kb
 * @subpackage	Libraries
 * @category	Libraries
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/developer/modules.html
 * @version 	$Id: core_events.php 139 2009-12-02 14:45:42Z suzkaw68 $
 */
class core_events
{
	/**
	 * @var array Array of registered hooks and their listeners
	 */
	var $listeners = array();
	
	/**
	 * Kb events
	 * 
	 * Allow users to extend the system.
	 * Idea from Iono
	 */
	function __construct()
	{
		$data='';
		$CI =& get_instance();
		if($CI->db->table_exists('modules') && $CI->config->item('modules_on'))
		{
			$CI->db->from('modules');
			$CI->db->where('active', '1'); 
			$query = $CI->db->get();
			foreach ($query->result() as $row)
			{
				if (@file_exists(KBPATH .'my-modules/'.$row->directory.'/events.php'))
				{
					include_once(KBPATH .'my-modules/'.$row->directory.'/events.php');
					$class = $row->name.'_events';
					if (class_exists($class)) 
					{
						new $class($this);
					}
				}
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Register a listner for a given hook
	 *
	 * @param string $hook
	 * @param object $class_reference
	 * @param string $method
	 */
	function register($hook, &$class_reference, $method)
	{
		// Specifies a key so we can't define the same handler more than once
		$key = get_class($class_reference).'->'.$method;
		$this->listeners[$hook][$key] = array(&$class_reference, $method);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Trigger an event
	 *
	 * @param string $hook
	 * @param mixed $data
	 */
	function trigger($hook, $data='')
	{
		$call_it = '';
		// Are there any hooks?
		if (isset($this->listeners[$hook]) && is_array($this->listeners[$hook]) && count($this->listeners[$hook]) > 0)
		{
			// Loop
			foreach ($this->listeners[$hook] as $listener)
			{
				// Set up variables
				$class =& $listener[0];
				$method = $listener[1];
				if (method_exists($class,$method))
				{
					// Call method dynamically
					$call_it.=$class->$method($data);
				}
			}
		}
		return $call_it;
	}
}

/* End of file core_Events.php */
/* Location: ./upload/includes/application/libraries/core_Events.php */