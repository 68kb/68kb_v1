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
 * Admin Comments Controller
 *
 * Handles all the comments
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: comments.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Comments extends Controller
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
		$this->load->model('comments_model');
		$this->load->helper('cookie');
		$this->load->helper('gravatar');
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
		$data='';
		$cookie = get_cookie('commentsgrid_orderby', TRUE);
		$cookie2 = get_cookie('commentsgrid_orderby_2', TRUE);
		if ($cookie<>'' && $cookie2 <> '')
		{
			redirect('admin/comments/grid/orderby/'.$cookie.'/'.$cookie2);
		}
		else
		{
			redirect('admin/comments/grid/');
		}
		$this->init_model->display_template('comments/grid', $data, 'admin');
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
		$data['nav'] = 'comments';
		$data['goto'] = $goto;
		$this->init_model->display_template('content', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Grid
	*
	* Show a table of categories
	*
	* @access	public
	* @return	array
	*/
	function grid()
	{
		$data['nav'] = 'comments';
		if ( ! $this->auth->check_level(2))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		#### settings ###
		$this->load->library("pagination");
		$this->load->helper('text');
		$this->load->helper(array('form', 'url'));
		
		$config['per_page'] = $this->init_model->settings['max_search']; 
	
		#### db init ###
		
		//total number of rows
		$config['total_rows'] = $this->db->count_all('comments');
	
		//prepare active record for new query (with limit/offeset/orderby)
		$this->db->select('comment_ID, comment_author, comment_author_email, comment_author_IP, comment_date, comment_content, comment_approved, article_title, article_uri');
		$this->db->from("comments");
		$this->db->join('articles', 'comments.comment_article_ID = articles.article_id', 'left');
		#### SEARCHING #### 
		if($this->input->post('searchtext') != '')
		{
			$q = $this->input->post('searchtext', TRUE);
			$data['q'] = $q;
			$this->db->like('comment_author', $q);
			$this->db->orlike('comment_author_email', $q);
			$this->db->orlike('comment_author_IP', $q);
		}
		if($this->input->post('comment_approved') != '')
		{
			$this->db->where('comment_approved', $this->input->post('comment_approved'));
			$data['s_display'] = $this->input->post('comment_approved');
		}
		#### sniff uri/orderby ###
			
		$segment_array = $this->uri->segment_array();
		$segment_count = $this->uri->total_segments();
		
		$allowed = array('comment_ID','comment_author', 'comment_author_email', 'comment_author_IP', 'comment_date', 'article_title');
		 
		//segments
		$do_orderby = array_search("orderby",$segment_array);
		$asc = array_search("asc",$segment_array);
		$desc = array_search("desc",$segment_array);
		
		//do orderby
		if ($do_orderby!==FALSE)
		{
			$orderby = $this->uri->segment($do_orderby+1);
			if( ! in_array( trim ( $orderby ), $allowed )) 
			{
				$orderby = 'comment_ID';	
			}
			$this->db->order_by($orderby, $this->uri->segment($do_orderby+2));
		} 
		else 
		{
			$orderby = 'comment_ID';
		}
		
		$data['orderby'] = $orderby;
		$data['sort'] = $this->uri->segment($do_orderby+2);
		
		if ($data['sort'] == 'asc') 
		{
			$data['opp'] = 'desc';
		} 
		else 
		{
			$data['opp'] = 'asc';
		}
			
		//set cookie
		$cookie = array(
				'name'   => 'commentsgrid_orderby',
				'value'  => $this->uri->segment($do_orderby+1),
				'expire' => '86500'
			);
		$cookie2 = array(
				'name'   => 'commentsgrid_orderby_2',
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
		$data["items"] = $query->result_array();
		

		$config['base_url'] = site_url(join("/",$segment_array));
		$config['uri_segment'] = count($segment_array)+1;
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();	   
		
		$this->init_model->display_template('comments/grid', $data, 'admin'); 
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Update Status
	* 
	* @access	public
	*/
	function update()
	{
		$newstatus = $this->input->post('newstatus', TRUE);
		foreach ($this->input->post('commentid') AS $key)
		{
			$this->comments_model->change_display($newstatus, $key);
		}
		redirect('admin/comments/');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Edit Comment
	* 
	* @access	public
	*/
	function edit()
	{
		$this->load->library('form_validation');
		$data['nav'] = 'comments';
		if ( ! $this->auth->check_level(2))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper('form');
		$id = (int) $this->uri->segment(4, 0);
		if ($id=='')
		{
			$id = $this->input->post('comment_ID', TRUE);
		}
		$this->db->from('comments')->where('comment_ID', $id);
		$query = $this->db->get();
		$data['art'] = $query->row();
		$data['action'] = 'modify';
		
		$this->form_validation->set_rules('comment_author', 'lang:kb_name', 'required');
		$this->form_validation->set_rules('comment_author_email', 'lang:kb_email', 'required');
		$this->form_validation->set_rules('comment_content', 'lang:kb_content', 'required');
		$this->form_validation->set_rules('comment_approved', 'lang:kb_display', 'required');
		$this->core_events->trigger('comments/validation');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('comments/edit', $data, 'admin');
		}
		else
		{
			//success
			$comment_ID = $this->input->post('comment_ID', TRUE);
			$data = array( 
				'comment_author' => $this->input->post('comment_author', TRUE),
				'comment_author_email' => $this->input->post('comment_author_email', TRUE),
				'comment_content' => $this->input->post('comment_content', TRUE),
				'comment_approved' => $this->input->post('comment_approved', TRUE),
			);
			$this->comments_model->edit_comment($comment_ID, $data);
			$this->revert('admin/comments/');
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Delete Article
	* 
	* @access	public
	*/
	function delete()
	{
		$data['nav'] = 'comments';
		if ( ! $this->auth->check_level(2))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$id = (int) $this->uri->segment(4, 0);
		$this->comments_model->delete_comment($id);
		$this->core_events->trigger('comments/delete', $id);
		$this->revert('admin/comments/');
	}
	
}

/* End of file comments.php */
/* Location: ./upload/includes/application/controllers/admin/comments.php */ 