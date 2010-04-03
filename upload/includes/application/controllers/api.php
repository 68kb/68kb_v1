<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 68KB
 *
 * An open source knowledge base script
 *
 * @package		API
 * @author		68kb Dev Team
 * @copyright	Copyright (c) 2009, 68 Designs, LLC
 * @license		http://68kb.com/user_guide/license.html
 * @link		http://68kb.com
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * API System
 *
 * The API System uses xml-rpc to communicate.
 *
 * @package		API
 * @subpackage	API
 * @category	API
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/developer/api.html
 * @version 	$Id: api.php 112 2009-11-10 01:08:48Z suzkaw68 $
 */
class Api extends controller
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
		$this->load->model('category_model');
		$this->load->model('article_model');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Route the function
	 *
	 * @access	public
	 */
	function index()
	{
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		
		// Users
		$config['functions']['add_user'] = array('function' => 'Api.add_user');
		$config['functions']['edit_user'] = array('function' => 'Api.edit_user');
		$config['functions']['get_user'] = array('function' => 'Api.get_user');
		
		// Articles
		$config['functions']['add_article'] = array('function' => 'Api.add_article');
		$config['functions']['edit_article'] = array('function' => 'Api.edit_article');
		$config['functions']['add_article_to_cats'] = array('function' => 'Api.add_article_to_cats');
		
		// Comments
		$config['functions']['add_comment'] = array('function' => 'Api.add_comment');
		$config['functions']['edit_comment'] = array('function' => 'Api.edit_comment');
		
		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Check for a valid user account
	 *
	 * @access	public
	 */
	private function check_access($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$query = $this->db->get('users');
		
		if ($query->num_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
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
	private function _username_check($str)
	{
		$this->db->select('id')->from('users')->where('username', $str);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Add a new users
	 *
	 * Parameters: 
	 * <code>
	 * 	$request = array(
	 *		array(array('username'=>'demo', 'password' => 'demo'),'struct'),
	 *		array(array('firstname'=>'John','lastname'=>'Smith','email'=>'john@smith.com','username'=>'john','password'=>'john'),'struct')
	 *	);
	 * </code>
	 *
	 * @access	public 
	 */
	function add_user($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}

		$error = '';
		//check errors
		$user_firstname = $parameters['1']['firstname'];
		$user_lastname = $parameters['1']['lastname'];
		$user_email = $parameters['1']['email'];
		$user_username = $parameters['1']['username'];
		$user_password = $parameters['1']['password'];
		if($user_username == '')
		{
			$error .= 'Username required'."\n";
		}
		if($user_password == '')
		{
			$error .= 'Password required'."\n";
		}
		if( ! $this->_username_check($user_username))
		{
			$error .= 'Username already exists'."\n";
		}
		if($error <> '')
		{
			return $this->xmlrpc->send_error_message('101', $error);
		}
		
		$user_password = md5($user_password);
		
		$data = array(
			    'firstname' => $user_firstname,
			    'lastname' => $user_lastname,
			    'email' => $user_email,
			    'username' => $user_username,
			    'password' => $user_password,
			    'joindate' => time()
			);
		if ($this->db->insert('users', $data)) 
		{
			$id = $this->db->insert_id();
			$response = array(array('id'  => $id), 'struct');
	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
			return $this->xmlrpc->send_response($response);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Edit a users
	 *
	 * @access	public
	 */
	function edit_user($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}

		$error = '';
		//check errors
		$user_id = $parameters['1']['id'];
		$user_firstname = $parameters['1']['firstname'];
		$user_lastname = $parameters['1']['lastname'];
		$user_email = $parameters['1']['email'];
		$user_username = $parameters['1']['username'];
		$user_password = $parameters['1']['password'];
		if($user_username == '')
		{
			$error .= 'Username required'."\n";
		}
		if($user_password == '')
		{
			$error .= 'Password required'."\n";
		}
		if($error <> '')
		{
			return $this->xmlrpc->send_error_message('101', $error);
		}
		
		$user_password = md5($user_password);
		$data = array(
			    'firstname' => $user_firstname,
			    'lastname' => $user_lastname,
			    'email' => $user_email,
			    'username' => $user_username,
			    'password' => $user_password
			);
		$this->db->where('id', $user_id);
		if ($this->db->update('users', $data))
		{
			$response = array(true, 'boolean');
	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
	        return $this->xmlrpc->send_response($response);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get User
	 *
	 * @access	public
	 */
	function get_user($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
		$user_username = $parameters['1']['username'];
		
		$this->db->from('users')->where('username', $user_username);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row(); 
			
			$response = array(array('id'    => array($row->id,'string'),
									'username'  => array($row->username,'string'),
	                                'firstname' => array($row->firstname,'string'),
	                                'lastname'  => array($row->lastname,'string'),
	                                'email'  => array($row->email,'string'),
	                                'joindate' => array($row->joindate,'string')
	                                ),
	                         'struct');

	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
			return $this->xmlrpc->send_response($response);
		}	
	}

	// ------------------------------------------------------------------------
	
	/**
	 * Add a new article
	 *
	 * @access	public
	 */
	function add_article($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
		$error = '';
		//check errors
		$article_author = $parameters['1']['article_author'];
		$article_title = $parameters['1']['article_title'];
		$article_keywords = $parameters['1']['article_keywords'];
		$article_short_desc = $parameters['1']['article_short_desc'];
		$article_description = $parameters['1']['article_description'];
		$article_display = $parameters['1']['article_display'];
		if($article_author == '')
		{
			$error .= 'Author required'."\n";
		}
		if($article_title == '')
		{
			$error .= 'Title required'."\n";
		}
		if($article_description == '')
		{
			$error .= 'Description required'."\n";
		}
		if($error <> '')
		{
			return $this->xmlrpc->send_error_message('101', $error);
		}
		$data = array(
			'article_author' => $article_author, 
			'article_title' => $article_title,
			'article_keywords' => $article_keywords,
			'article_short_desc' => $article_short_desc,
			'article_description' => $article_description,
			'article_display' => $article_display
		);
		$id = $this->article_model->add_article($data);
		if(is_int($id))
		{
			$response = array(array('id'  => $id), 'struct');
	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
			return $this->xmlrpc->send_response($response);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Edit an article
	 *
	 * @access	public
	 */
	function edit_article($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
		$error = '';
		//check errors
		$article_id = $parameters['1']['article_id'];
		$article_author = $parameters['1']['article_author'];
		$article_title = $parameters['1']['article_title'];
		$article_keywords = $parameters['1']['article_keywords'];
		$article_short_desc = $parameters['1']['article_short_desc'];
		$article_description = $parameters['1']['article_description'];
		$article_display = $parameters['1']['article_display'];
		if($article_author == '')
		{
			$error .= 'Author required'."\n";
		}
		if($article_title == '')
		{
			$error .= 'Title required'."\n";
		}
		if($article_description == '')
		{
			$error .= 'Description required'."\n";
		}
		if($error <> '')
		{
			return $this->xmlrpc->send_error_message('101', $error);
		}
		$data = array(
			'article_author' => $article_author, 
			'article_title' => $article_title,
			'article_keywords' => $article_keywords,
			'article_short_desc' => $article_short_desc,
			'article_description' => $article_description,
			'article_display' => $article_display
		);
		$this->db->where('article_id', $article_id);
		if ($this->db->update('articles', $data))
		{
			$response = array(true, 'boolean');
	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
	        return $this->xmlrpc->send_response($response);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Add article to category
	 *
	 * @todo Get this working. :) 
	 * @access	public
	 */
	function add_article_to_cats($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
		$error = '';
		//check errors
		$article_id = $parameters['1']['article_id'];
		$cats = $parameters['1']['cats'];
		if($article_id == '')
		{
			$error .= 'Article ID required'."\n";
		}
		if($cats == '')
		{
			$error .= 'Category array is required'."\n";
		}
		if($error <> '')
		{
			return $this->xmlrpc->send_error_message('101', $error);
		}
		if($this->category_model->insert_cats($article_id, $cats))
		{
			//$response = array(array('response'  => TRUE), 'boolean');
			$response = array(true, 'boolean');
	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
	        return $this->xmlrpc->send_response($response);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Add a comment
	 *
	 * @access	public
	 */
	function add_comment($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
		
		//check errors
		$error = '';
		$comment_author = $parameters['1']['comment_author'];
		$comment_article_ID = $parameters['1']['comment_article_ID'];
		$comment_author_email = $parameters['1']['comment_author_email'];
		$comment_content = $parameters['1']['comment_content'];
		
		if($comment_author == '')
		{
			$error .= 'Author required'."\n";
		}
		if($comment_article_ID == '')
		{
			$error .= 'Article ID is required'."\n";
		}
		if($comment_author_email == '')
		{
			$error .= 'Author Email required'."\n";
		}
		if($comment_content == '')
		{
			$error .= 'Description required'."\n";
		}
		if($error <> '')
		{
			return $this->xmlrpc->send_error_message('101', $error);
		}
		
		$data = array(
			'comment_article_ID' => $comment_article_ID,
			'comment_author' => $comment_author,
			'comment_author_email' => $comment_author_email,
			'comment_content' => $comment_content,
			'comment_approved' => 1
		);
		$this->db->insert('comments', $data);
		$id = $this->db->insert_id();
		if(is_int($id))
		{
			$response = array(array('id'  => $id), 'struct');
	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
			return $this->xmlrpc->send_response($response);
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Edit a comment
	 *
	 * @access	public
	 */
	function edit_comment($request)
	{
		$parameters = $request->output_parameters();
		
		$username = $parameters['0']['username'];
		$password = $parameters['0']['password'];
		
		if( ! $this->check_access($username, $password))
		{
			return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		}
		
		//check errors
		$error = '';
		$comment_ID = $parameters['1']['comment_ID'];
		$comment_author = $parameters['1']['comment_author'];
		$comment_article_ID = $parameters['1']['comment_article_ID'];
		$comment_author_email = $parameters['1']['comment_author_email'];
		$comment_content = $parameters['1']['comment_content'];
		$comment_approved = $parameters['1']['comment_approved'];
		
		if($comment_ID == '')
		{
			$error .= 'Comment id required'."\n";
		}
		if($comment_author == '')
		{
			$error .= 'Author required'."\n";
		}
		if($comment_article_ID == '')
		{
			$error .= 'Article ID is required'."\n";
		}
		if($comment_author_email == '')
		{
			$error .= 'Author Email required'."\n";
		}
		if($comment_content == '')
		{
			$error .= 'Description required'."\n";
		}
		if($error <> '')
		{
			return $this->xmlrpc->send_error_message('101', $error);
		}
		
		$data = array(
			'comment_article_ID' => $comment_article_ID,
			'comment_author' => $comment_author,
			'comment_author_email' => $comment_author_email,
			'comment_content' => $comment_content,
			'comment_approved' => $comment_approved
		);
		$this->db->where('comment_ID', $comment_ID);
		$this->db->update('comments', $data);
		if($this->db->affected_rows() > 0) 
		{
			$response = array(true, 'boolean');
	        return $this->xmlrpc->send_response($response);
		}
		else
		{
			$response = array(false, 'boolean');
			return $this->xmlrpc->send_response($response);
		}
	}
}