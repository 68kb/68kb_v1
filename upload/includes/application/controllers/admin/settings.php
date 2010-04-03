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
 * Admin Settings Controller
 *
 * Handles all the settings
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/settings.html
 * @version 	$Id: settings.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Settings extends Controller
{
	/**
	* Constructor
	*
	* Requires needed models and helpers.
	* 
	* @access	public
	*/
	function __construct()
	{
		parent::__construct();
		$this->load->model('init_model');
		$this->load->helper('cookie');
		$this->load->library('auth');
		$this->auth->restrict();
		$this->load->helper('form');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Redirects to this->main
	*
	* @access	public
	*/
	function index()
	{
		redirect('admin/settings/main/'); 
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Save Settings
	* 
	* @access	public
	*/
	function main()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');
		$data['nav']='settings';
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		
		$this->form_validation->set_rules('site_name', 'lang:kb_site_title', 'required');
		$this->form_validation->set_rules('site_email', 'lang:kb_email', 'required|valid_email');
		$this->form_validation->set_rules('max_search', 'lang:kb_max_search', 'required|numeric');
		$this->form_validation->set_rules('cache_time', 'lang:kb_cache_time', 'required|numeric');
		$this->core_events->trigger('settings/validation');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('settings/main', $data, 'admin');
		}
		else
		{
			foreach ($_POST as $key => $value)
			{
				$data = array( 
					'value' => $this->input->xss_clean($value)
				);
				$this->db->where('short_name', $key);
				$this->db->update('settings', $data);
			}
			$this->revert('admin/settings/');
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Template Controller
	*
	* Allow admin to select active template.
	*
	* @access	public
	*/
	function templates()
	{
		$this->load->model('theme_model');
		$settings['template'] = $this->init_model->get_setting('template');
		$data['nav'] = 'settings';
		
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		
		$activate = $this->uri->segment(4, 0);
		
		if ($activate != '')
		{
			if (file_exists(KBPATH .'themes/front/'.$activate.'/config.php'))
			{
				$data = array( 
				'value' => $this->input->xss_clean($activate)
				);
				$this->db->where('short_name', 'template');
				$this->db->update('settings', $data);
				redirect('admin/settings/templates/');
			}
			else
			{
				echo $activate;
			}
		}
		
		$data['active'] = $this->theme_model->load_active_template($settings['template']);
		$data['available_themes'] = $this->theme_model->load_templates($settings['template']);
		$this->init_model->display_template('settings/template', $data, 'admin'); 
	}
	
	// ------------------------------------------------------------------------
		
	/**
	 * Load Active Template
	 *
	 * Load the config file for the active template.
	 *
	 * @access	private
	 * @param	string	the file
	 * @return	bool
	 */
	function _load_active_template($template)
	{
		if (file_exists(KBPATH .'themes/front/'.$template.'/config.php'))
		{
			require_once(KBPATH .'themes/front/'.$template.'/config.php');
			$preview = 'front/'.$template.'/preview.png';
			if ($this->_testexists($preview))
			{
				$data['template']['preview']=base_url().'templates/'.$preview;
			}
			else
			{
				$data['template']['preview']=base_url().'images/nopreview.gif';
			}
			return $data['template'];
		}
		else
		{
			return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------
		
	/**
	 * Test if a file exists
	 *
	 * Test if a file exists.
	 *
	 * @access	private
	 * @param	string	the file
	 * @return	bool
	 */
	function _testexists($file)
	{
		$file = KBPATH.'themes/'.$file;
		//echo $file.'<BR>';
		if ( ! file_exists($file))
		{
			return false;
		}
		return true;
	}
		
	// ------------------------------------------------------------------------
	
	/**
	* Revert
	*
	* Show a message and redirect the user
	* 
	* @access	public
	* @param	string -- The location to goto
	* @return	array
	*/
	function revert($goto)
	{
		$data['nav'] = 'settings';
		$data['goto'] = $goto;
		$this->init_model->display_template('content', $data, 'admin');
	}
	
}

/* End of file settings.php */
/* Location: ./upload/includes/application/controllers/admin/settings.php */ 