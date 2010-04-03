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
 * Modules Controller
 *
 * Handles any modules added to the script
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/developer/modules.html
 * @version 	$Id: modules.php 85 2009-08-05 05:08:12Z suzkaw68 $
 */
class Modules extends Controller
{
	var $modules;
	
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Modules Controller Initialized');
		$this->load->model('init_model');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * List all modules
	 *
	 * @access	public
	 */
	function index()
	{
		
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Show a modules front end file
	 *
	 * @access	public
	 */
	function show()
	{
		$name = $this->uri->segment(3, 0);
		$this->db->from('modules')->where('name', $name);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$file = KBPATH .'my-modules/'.$row->directory.'/index.php';
			if (@file_exists($file))
			{
				$data['file'] = $file;
				$this->core_events->trigger('modules/show', $file);
				$this->init_model->display_template('modules/show', $data);
			}
			else
			{
				//take me home.
				redirect('kb');
			}
		}
		else
		{
			redirect('kb');
		}
	}
		
	// ------------------------------------------------------------------------
	
	/**
	 * Get Module
	 *
	 * Get a single module
	 *
	 * @access	public
	 * @param	int	the module id
	 * @return	bool
	 */
 	function get_module()
	{
		$name = $this->uri->segment(3, 0);
		$this->db->from('modules')->where('name', $name);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$file = KBPATH .'my-modules/'.$row->directory.'/index.php';
			if (@file_exists($file))
			{
				$data['file'] = $file;
				require_once($file);
			}
			else
			{
				//take me home.
				redirect('kb');
			}
		}
		else
		{
			redirect('kb');
		}
	}
}

/* End of file modules.php */
/* Location: ./upload/includes/application/controllers/modules.php */ 