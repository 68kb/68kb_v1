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
 * KB Controller
 *
 * Main Setup Controller
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: kb.php 125 2009-11-15 05:15:57Z suzkaw68 $
 */
class Kb extends Controller
{
	/**
	* Constructor
	*
	* @return 	void
	*/
	function __construct()
	{
		parent::__construct();
		define('KB_VERSION', 'v1.0.0rc4');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Show the setup page
	*
	* @access	public
	*/
	function index()
	{
		global $error;
		$data['error']=$error;
		$data['nextStep'] = 2;
		$data['cache'] = $this->_writeable(KBPATH.'cache');
		$data['uploads'] = $this->_writeable(KBPATH.'uploads');
		$data['body'] = $this->load->view('setup/index', $data, true);
		$this->load->view('setup/layout.php', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install step 1
	*
	* @access	public
	*/
	function install()
	{
		global $error;
		
		$data['error']=$error;
		$data['nextStep'] = 2;
		//$data['cache'] = $this->_writeable(KBPATH.'cache');
		$data['uploads'] = $this->_writeable(KBPATH.'uploads');
		$data['body'] = $this->load->view('setup/install-step1', $data, true);
		$this->load->view('setup/layout.php', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Run the install
	*
	* @access	public
	*/
	function run()
	{
		$this->load->model('setup/install_model');
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);
		$email = $this->input->post('adminemail', TRUE);
		$drop = $this->input->post('drop', TRUE);
		$data['log'] = $this->install_model->install($username,$password, $email, $drop);
		$data['body'] = $this->load->view('setup/install-step2', $data, true);
		$this->load->view('setup/layout.php', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Do the upgrade
	*
	* @access	public
	*/
	function upgrade()
	{
		$this->load->model('setup/upgrade_model');
		$data['log'] = $this->upgrade_model->upgrade();
		$data['body'] = $this->load->view('setup/upgrade', $data, true);
		$this->load->view('setup/layout.php', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Check if a file is writeable.
	*
	* @param	string The file name.
	* @access	private
	*/
	private function _writeable($filename) 
	{
		global $error;
		if ( ! is_writable($filename)) 
		{
			$error=TRUE;
			return "<span style='color: red;'>ERROR!</span> Please CHMOD to 777";
		}
		else
		{
			return 'Ok';
		}
	}
}

/* End of file kb.php */
/* Location: ./upload/includes/application/controllers/setup/kb.php */ 