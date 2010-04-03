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
 * Admin Modules Controller
 *
 * Handles the modules. Please see the developer module for a brief
 * overview or read the documentation. 
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/developer/modules.html
 * @version 	$Id: modules.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Modules extends controller
{
	var $modules;
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('init_model');
		$this->load->model('modules_model');
		$this->load->helper('cookie');
		$this->load->library('auth');
		$this->auth->restrict();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Index controller
	 *
	 * Redirects to the Modules->manage()
	 *
	 * @access	public
	 */
	function index()
	{
		//$this->modules_model->load_modules();
		//$this->_regenerate();
		redirect('admin/modules/manage/');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * List all modules
	 *
	 * @access	public
	 */
	function manage()
	{
		$data['nav'] = 'settings';
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$id = (int) $this->uri->segment(4, 0);
		$action = $this->uri->segment(5,0);
		if ($id > 0 && ($action == 'activate' || $action == 'deactivate' || $action = 'upgrade' || $action == 'delete'))
		{
			$data['msg'] = $this->modules_model->init_module($id, $action);
		}
		$data['modules'] = $this->modules_model->load_active();
		$data['unactive'] = $this->modules_model->load_unactive();
		$this->init_model->display_template('modules/managemodules', $data, 'admin');
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Activate a module
	 *
	 * @access	public
	 */
	function activate($module)
	{
		$this->modules_model->activate($module);
		$this->session->set_flashdata('msg', lang('kb_activated'));
		redirect('admin/modules/manage/');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Upgrade a module
	 *
	 * @access	public
	 */
	function upgrade($id)
	{
		$this->modules_model->init_module($id, 'upgrade');
		$this->session->set_flashdata('msg', lang('kb_upgraded'));
		redirect('admin/modules/manage/');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Show a modules admin file.
	 *
	 * This is used so you can have module files that are included in the
	 * theme that is in use. This same method is used for both the front 
	 * end and the administration. 
	 *
	 * @access	public
	 */
	function show()
	{
		$nav = $this->core_events->trigger('admin/template/nav/parent');
		$data['nav'] = ( empty($nav) ) ? 'settings': $nav;
		$name = $this->uri->segment(4, 0);
		
		$this->db->from('modules')->where('name', $name);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$file = KBPATH .'my-modules/'.$row->directory.'/admin.php';
			if (@file_exists($file))
			{
				$data['file'] = $file;
				$this->init_model->display_template('modules/show', $data, 'admin');
			}
			else
			{
				//take me home.
				redirect('admin');
			}
		}
		else
		{
			redirect('admin');
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
 	function get_module($id)
	{
		$this->db->select('id,name,description,directory,version,active')->from('modules')->where('id', $id);
		$query = $this->db->get();
		$data = $query->row();
		//echo $this->db->last_query();
		$query->free_result();
		return $data;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Try to remove the module directory
	 *
	 * @access	private
	 * @param	int	the module id
	 * @return	bool
	 */
	function _remove_dir($id)
	{
		$this->db->select('name')->from('modules')->where('id', $id);
		$query = $this->db->get();
		$data = $query->row();
		$name = $data->name;
		$query->free_result();
		if (file_exists(KBPATH .'my-modules/'.$name.'/config.php'))
		{
			$opendir = opendir(KBPATH .'my-modules/'.$name);
			while (false !== ($module = readdir($opendir)))
			{
				// Ignores . and .. that opendir displays
				if ($module != '.' && $module != '..')
				{
					@unlink(KBPATH .'my-modules/'.$name.'/'.$module);
				}
			}
			closedir($opendir);
			@rmdir(KBPATH .'my-modules/'.$name);
		}
	}
}

/* End of file modules.php */
/* Location: ./upload/includes/application/controllers/admin/modules.php */ 