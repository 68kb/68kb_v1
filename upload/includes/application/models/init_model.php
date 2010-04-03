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
 * Init Model
 *
 * This class is a global model used for the majority of your settings.
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: init_model.php 89 2009-08-13 01:54:20Z suzkaw68 $
 */
class Init_model extends Model {

	/**
	 * User data, stored for embeded methods
	 *
	 * @access 	private
	 * @var 	array
	 */
	var $settings = array();
	
	/**
	 * Constructor
	 *
	 * @uses 	get_settings
	 * @return 	void
	 */
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Init Model Initialized');
		$this->get_settings();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Settings
	 *
	 * Get all the auto loaded settings from the db.
	 *
	 * @return	array
	 * @uses 	this->_cron
	 */
	function get_settings()
	{
		if ( ! $this->db->table_exists('settings'))
		{
			redirect('setup');
		}
		$this->db->select('short_name,value')->from('settings')->where('auto_load', 'yes');
		$query = $this->db->get();
		foreach ($query->result() as $k=>$row)
		{
			$this->settings[$row->short_name] = $row->value;
		}
		//check cron
		$this->_cron();
		return $this->settings;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Setting (Notice Singular)
	 *
	 * Used to pull out one specific setting from the settings table.
	 *
	 * Here is an example: 
	 * <code>
	 * <?php
	 * $this->init_model->get_setting('site_name');
	 * ?>
	 * </code>
	 *
	 * @access	public
	 * @param	string
	 * @return	mixed
	 */
	function get_setting($name)
	{
		if ( ! empty($this->settings[$name]))
		{
			return $this->settings[$name];
		}
		else
		{
			$this->db->select('value')->from('settings')->where('short_name', $name);
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
			   $row = $query->row();
			   return $row->value;
			}
			return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Run Cron
	 *
	 * Runs the internal Cron Job based off the date.
	 *
	 * @return	void
	 */
	function _cron()
	{
		$today=date("Y-m-d");
		$this->load->plugin('version');
		if ($this->settings['last_cron'] < $today)
		{
			$this->load->plugin('version');
			$version = checklatest();
			$data = array('value' => $version);
			$this->db->where('short_name', 'latest');
			$this->db->update('settings', $data);
			
			log_message('info', 'Internal Cron ran');
			$data = array('value' => $today);
			$this->db->where('short_name', 'last_cron');
			$this->db->update('settings', $data);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Test if a template file exists
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function test_exists($file)
	{
		$file = 'themes/'.$file;
		if (!file_exists($file))
		{
			return false;
		}
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Load Body Template
	 *
	 * This method is used to load the body template file. Can be used without layout
	 * for emails, printer, etc...
	 *
	 * @access	public
	 * @param	string	the template body file.
	 * @param	string	the directory - admin or front.
	 * @param	array 	data array
	 * @uses	test_exists
	 * @return	object 	I think
	 */
	function load_body($template, $dir='front', $data)
	{
		$data['settings']=$this->settings;
		
		if ($dir=='admin')
		{
			$body_file = $dir.'/'.$data['settings']['admin_template'].'/'.$template.'.php';
		}
		else
		{
			$body_file = $dir.'/'.$data['settings']['template'].'/'.$template.'.php';
		}
		
		if ($this->test_exists($body_file))
		{
			return $this->load->view($body_file, $data, true);
		}
		else
		{
			return $this->load->view($dir.'/default/'.$template, $data, true);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Load layout Template
	 *
	 * This method is used to load the layout template file. 
	 *
	 * @access	public
	 * @param	string	the directory - admin or front.
	 * @param	array 	data array
	 * @uses	test_exists
	 * @return	object
	 */
	function load_layout($dir='front', $data)
	{
		$data['settings']=$this->settings;
		
		if (defined('IN_ADMIN'))
		{
			$layout_file = $dir.'/'.$data['settings']['admin_template'].'/layout.php';
		}
		else
		{
			$layout_file = $dir.'/'.$data['settings']['template'].'/layout.php';
		}

		if ($this->test_exists($layout_file))
		{
			return $this->load->view($layout_file, $data);
		}
		else
		{
			return $this->load->view($dir.'/default/layout.php',$data);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Template Display
	 *
	 * Displays a template based off the current settings
	 * Basically a wrapper for layout
	 *
	 * <code>
	 * $this->init_model->display_template('category', $data);
	 * </code>
	 *
	 * @access	public
	 * @param	string	the template body file
	 * @param	array	the data to pass
	 * @param	string	The directory. front or admin (Defaults to front)
	 * @uses	get_setting
	 * @uses	load_body
	 * @uses	test_exists
	 */
	function display_template($template, $data='', $dir='front')
	{
		$data['settings']=$this->settings;
		
		// check directory
		if ($dir=='admin')
		{
			define('IN_ADMIN', TRUE);
		}
		else
		{
			$dir='front';
			// are we caching?
			if ($this->get_setting('cache_time') > 0)
			{
				$this->output->cache($this->get_setting('cache_time'));
			}
		}
		
		// meta content
		if ( ! isset($data['title']))
		{
			$data['title'] = $this->get_setting('site_name');
		}
		if ( ! isset($data['meta_keywords']))
		{
			$data['meta_keywords'] = $this->get_setting('site_keywords');
		}
		if ( ! isset($data['meta_description']))
		{
			$data['meta_description'] = $this->get_setting('site_description');
		}
		
		// Check the body exists
		$data['body'] = $this->load_body($template, $dir, $data);
		
		// Now check the layout exists
		$this->load_layout($dir, $data);
		
		// finally show the last hook
		$this->core_events->trigger('display_template');	
	}
	
}

/* End of file init_model.php */
/* Location: ./upload/includes/application/models/init_model.php */