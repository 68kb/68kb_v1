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
 * Admin Users Controller
 *
 * Handles all the users
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/users.html
 * @version 	$Id: users.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Users extends Controller
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
		$this->load->model('users_model');
		$this->load->helper('cookie');
		$this->load->library('auth');
		$this->auth->restrict();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Redirects to this->grid
	*
	* @access	public
	*/
	function index()
	{
		$data = '';
		$cookie = get_cookie('usersgrid_orderby', TRUE);
		$cookie2 = get_cookie('usersgrid_orderby_2', TRUE);
		if($cookie<>'' && $cookie2 <> '')
		{
			redirect('admin/users/grid/orderby/'.$cookie.'/'.$cookie2);
		}
		else
		{
			redirect('admin/users/grid/');
		}
		$this->init_model->display_template('users/grid', $data, 'admin');
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
		$data['goto'] = $goto;
		$data['nav'] = 'users';
		$this->init_model->display_template('content', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Grid
	*
	* Show a table of users
	* 
	* It assume this uri sequence:
	* /controller/simplepagination/[offset] 
	* or 
	* /controller/simplepagination/orderby/fieldname/orientation/[offset]
	*
	* @link http://codeigniter.com/forums/viewthread/45709/#217816
	* @access	public
	* @return	array
	*/
	function grid()
	{
		$data['nav'] = 'users';
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		#### settings ###
		$this->load->library("pagination");
		$this->load->helper("url");
			
		$config['per_page'] = $this->init_model->settings['max_search']; 
	
		#### db init ###
		
		//total number of rows
		$config['total_rows'] = $this->db->count_all('users');
	
		//prepare active record for new query (with limit/offeset/orderby)
		$this->db->select('id, username, firstname, lastname, email, level');
		$this->db->from("users");
	
			
		#### sniff uri/orderby ###
			
		$segment_array = $this->uri->segment_array();
		$segment_count = $this->uri->total_segments();
		
		$allowed = array('id','username', 'firstname', 'lastname', 'email', 'level');
		 
		//segments
		$do_orderby = array_search("orderby",$segment_array);
		$asc = array_search("asc",$segment_array);
		$desc = array_search("desc",$segment_array);
		
		//do orderby
		if ($do_orderby !== FALSE)
		{
			$orderby = $this->uri->segment($do_orderby+1);
			if( !in_array( trim ( $orderby ), $allowed )) 
			{
				$orderby = 'id';	
			}
			$this->db->order_by($orderby, $this->uri->segment($do_orderby+2));
		} 
		else 
		{
			$orderby = 'id';
		}
		
		$data['orderby'] = $orderby;
		$data['sort'] = $this->uri->segment($do_orderby+2);
		if($data['sort'] == 'asc') 
		{
			$data['opp'] = 'desc';
		} 
		else 
		{
			$data['opp'] = 'asc';
		}
			
		//set cookie
		$cookie = array(
				'name'   => 'usersgrid_orderby',
				'value'  => $this->uri->segment($do_orderby+1),
				'expire' => '86500'
			);
		$cookie2 = array(
				'name'   => 'usersgrid_orderby_2',
				'value'  => $this->uri->segment($do_orderby+2),
				'expire' => '86500'
			);
               
		set_cookie($cookie);
		set_cookie($cookie2);
		
		#### pagination & data subset ###
			
		//remove last segment (assume it's the current offset)
		if (ctype_digit($segment_array[$segment_count]))
		{
			$this->db->limit($config['per_page'], $segment_array[$segment_count]);
			array_pop($segment_array);
		}	 
		else 
		{
			$this->db->limit($config['per_page']);
		}
		$query = $this->db->get();
		$data['items'] =  $query->result_array();
	
	
		$config['base_url'] = site_url(join("/",$segment_array));
		$config['uri_segment'] = count($segment_array)+1;
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();	   
		
		$this->init_model->display_template('users/grid', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Edit User
	* 
	* @access	public
	*/
	function edit()
	{
		$data['nav'] = 'users';
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

		$id = (int) $this->uri->segment(4, 0);

		$data['art'] = $this->users_model->get_user_by_id($id);
		$data['action'] = site_url('admin/users/edit/'.$id);
		
		//$this->form_validation->set_rules('username', 'lang:kb_username', 'required');
		$this->form_validation->set_rules('firstname', 'lang:kb_firstname', 'trim');
		$this->form_validation->set_rules('lastname', 'lang:kb_lastname', 'trim');
		$this->form_validation->set_rules('email', 'lang:kb_email', 'required');
		if($this->input->post('password') <> '')
		{
			$this->form_validation->set_rules('password', 'lang:kb_password', 'required|matches[passconf]');
			$this->form_validation->set_rules('passconf', 'lang:kb_confirmpassword', 'required');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('users/form', $data, 'admin');
		}
		else
		{
			//success
			$id = $this->input->post('id', TRUE);
			$data = array(
				'username' => $this->input->post('username', TRUE),
				'firstname' => $this->input->post('firstname', TRUE),
				'lastname' => $this->input->post('lastname', TRUE),
				'email' => $this->input->post('email', TRUE),
				'level' => $this->input->post('level', TRUE)
			);
			if ($this->input->post('password'))
			{
				$pass = array('password' => $this->input->post('password', TRUE));
				$data = array_merge((array)$data, (array)$pass);
			}
			$var = $this->users_model->edit_user($id, $data);
			$this->revert('admin/users/');
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Add User
	* 
	* @access	public
	*/
	function add()
	{
		$data['nav']='users';
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper(array('form', 'url', 'security'));
		$this->load->library('form_validation');
		
		$data['action'] = site_url('admin/users/add/');
		
		/** Rules **/
		$this->form_validation->set_rules('username', 'lang:kb_username', 'required|callback_username_check');
		$this->form_validation->set_rules('firstname', 'lang:kb_firstname', 'trim');
		$this->form_validation->set_rules('lastname', 'lang:kb_lastname', 'trim');
		$this->form_validation->set_rules('email', 'lang:kb_email', 'required');
		$this->form_validation->set_rules('password', 'lang:kb_password', 'required|matches[passconf]');
		$this->form_validation->set_rules('passconf', 'lang:kb_confirmpassword', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('users/form', $data, 'admin');
		}
		else
		{
			$password = dohash($this->input->post('password', TRUE), 'md5'); // MD5
			$data = array(
				    'firstname' => $this->input->post('firstname', TRUE),
				    'lastname' => $this->input->post('lastname', TRUE),
				    'email' => $this->input->post('email', TRUE),
				    'username' => $this->input->post('username', TRUE),
				    'password' => $password,
				    'joindate' => time(),
					'level' => $this->input->post('level', TRUE)
				);
			$this->db->cache_delete_all();
			if ($this->db->insert('users', $data)) 
			{
				$this->revert('admin/users/');
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Username check
	*
	* Checks to see if a username is in use.
	* 
	* @access	public
	*/
	function username_check($str)
	{
		$this->db->select('id')->from('users')->where('username', $str);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$this->form_validation->set_message('username_check', $this->lang->line('kb_username_inuse'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Delete User
	* 
	* @access	public
	*/
	function delete()
	{
		$data['nav'] = 'users';
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$id = (int) $this->uri->segment(4, 0);
		$this->db->cache_delete_all();
		$this->db->delete('users', array('id' => $id));
		$this->core_events->trigger('users/delete', $id);
		$this->revert('admin/users/');
	}
	
}

/* End of file users.php */
/* Location: ./upload/includes/application/controllers/admin/users.php */ 