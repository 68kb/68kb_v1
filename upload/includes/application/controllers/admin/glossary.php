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
 * Admin Glossary Controller
 *
 * Handles the glossary items
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/glossary.html
 * @version 	$Id: glossary.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Glossary extends Controller
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
		$cookie = get_cookie('glossarygrid_orderby', TRUE);
		$cookie2 = get_cookie('glossarygrid_orderby_2', TRUE);
		if($cookie<>'' && $cookie2 <> '')
		{
			redirect('admin/glossary/grid/orderby/'.$cookie.'/'.$cookie2);
		}
		else
		{
			redirect('admin/glossary/grid/');
		}
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
		$data['nav'] = 'glossary';
		$this->init_model->display_template('content', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Grid
	*
	* Show a table of articles
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
		#### settings ###
		$data['nav'] = 'glossary';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->library("pagination");
		$this->load->helper("url");
			
		$config['per_page'] = 25; 
	
		#### db init ###
		
		//total number of rows
		$config['total_rows'] = $this->db->count_all('glossary');
	
		//prepare active record for new query (with limit/offeset/orderby)
		//$this->db->select('aID, aURI, aTitle, aCat, aDisplay');
		$this->db->from("glossary");
	
			
		#### sniff uri/orderby ###
			
		$segment_array = $this->uri->segment_array();
		$segment_count = $this->uri->total_segments();
		
		$allowed = array('g_id','g_term');
		
		//segments
		$do_orderby = array_search("orderby",$segment_array);
		$asc = array_search("asc",$segment_array);
		$desc = array_search("desc",$segment_array);
		
		//do orderby
		if ($do_orderby!==false)
		{
			$orderby = $this->uri->segment($do_orderby+1);
			if( ! in_array( trim ( $orderby ), $allowed )) 
			{
				$orderby = 'g_id';	
			}
			$this->db->order_by($orderby, $this->uri->segment($do_orderby+2));
		} 
		else 
		{
			$orderby = 'g_id';
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
				'name'   => 'glossarygrid_orderby',
				'value'  => $this->uri->segment($do_orderby+1),
				'expire' => '86500'
			);
		$cookie2 = array(
				'name'   => 'glossarygrid_orderby_2',
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
		
		$this->init_model->display_template('glossary/grid', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Edit Category
	* 
	* @access	public
	*/
	function edit()
	{
		$this->load->library('form_validation');
		$data['nav'] = 'glossary';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper('form');
		$id = (int) $this->uri->segment(4, 0);
		
		$this->db->from('glossary')->where('g_id', $id);
		$query = $this->db->get();
		$row = $query->row();
		$query->free_result();
			
		$data['art'] = $row;
		
		$data['action'] = site_url('admin/glossary/edit/'.$id);
		
		$this->form_validation->set_rules('g_term', 'lang:kb_title', 'required');
		$this->form_validation->set_rules('g_definition', 'lang:kb_definition', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('glossary/form', $data, 'admin');
		}
		else
		{
			//success
			$data = array(
				'g_term' => $this->input->post('g_term', TRUE),
				'g_definition' => $this->input->post('g_definition', TRUE)
			);
			$this->db->where('g_id', $this->input->post('g_id', TRUE));
			if ($this->db->update('glossary', $data)) 
			{
				$this->revert('admin/glossary/');
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Add Category
	* 
	* @access	public
	*/
	function add()
	{
		$this->load->library('form_validation');
		$data['nav'] = 'glossary';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper('form');
		
		$data['action'] = site_url('admin/glossary/add');
		
		$this->form_validation->set_rules('g_term', 'lang:kb_title', 'required');
		$this->form_validation->set_rules('g_definition', 'lang:kb_definition', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('glossary/form', $data, 'admin');
		}
		else
		{
			$data = array(
				'g_term' => $this->input->post('g_term', TRUE),
				'g_definition' => $this->input->post('g_definition', TRUE)
			);
			if ($this->db->insert('glossary', $data)) 
			{
				$this->revert('admin/glossary/');
			}
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
		$data['nav']='glossary';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$id = (int) $this->uri->segment(4, 0);
		$this->db->delete('glossary', array('g_id' => $id));
		$this->revert('admin/glossary/');
	}
	
}

/* End of file glossary.php */
/* Location: ./upload/includes/application/controllers/admin/glossary.php */ 