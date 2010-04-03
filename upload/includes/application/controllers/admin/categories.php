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
 * Admin Categories Controller
 *
 * Handles the categories pages
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/categories.html
 * @version 	$Id: categories.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Categories extends Controller
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
		$data='';
		redirect('admin/categories/grid/');
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
		$data['nav'] = 'categories';
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
		$data['nav'] = 'categories';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$data['options'] = $this->category_model->get_cats_for_select('',0,'',TRUE);
		$this->init_model->display_template('categories/grid', $data, 'admin'); 
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
		$data['nav'] = 'categories';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper('form');
		$id = (int) $this->uri->segment(4, 0);
		$data['art'] = $this->category_model->get_cat_by_id($id);
		$data['options'] = $this->category_model->get_cats_for_select('',0,'',TRUE);
		$data['action'] = site_url('admin/categories/edit/'.$id);
		
		$this->form_validation->set_rules('cat_name', 'lang:kb_title', 'required');
		$this->core_events->trigger('categories/validation');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('categories/form', $data, 'admin');
		}
		else
		{
			//success
			$id = $this->input->post('cat_id', TRUE);
			$parent = $this->input->post('cat_parent', TRUE);
			if ($parent=='') 
			{
				$parent=0;
			}
			$cat_uri = $this->input->post('cat_uri', TRUE);
			$data = array(
				'cat_uri' => $cat_uri, 
				'cat_name' => $this->input->post('cat_name', TRUE),
				'cat_description' => $this->input->post('cat_description', TRUE),
				'cat_parent' => $parent,
				'cat_display' => $this->input->post('cat_display', TRUE),
				'cat_order' => $this->input->post('cat_order', TRUE)
			);
			$var = $this->category_model->edit_category($id, $data);
			$this->revert('admin/categories/');
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
		$data['nav'] = 'categories';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper('form');
		$id = (int) $this->uri->segment(4, 0);
		$data['options'] = $this->category_model->get_cats_for_select('',0,'',TRUE);
		$data['action'] = site_url('admin/categories/add/');
		
		$this->form_validation->set_rules('cat_name', 'lang:kb_title', 'required');
		$this->form_validation->set_rules('cat_uri', 'lang:kb_uri', 'alpha_dash');
		$this->form_validation->set_rules('cat_description', 'lang:kb_description', '');
		$this->form_validation->set_rules('cat_parent', 'lang:kb_parent_cat', 'numeric');
		$this->form_validation->set_rules('cat_order', 'lang:kb_weight', 'numeric');
		$this->core_events->trigger('categories/validation');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('categories/form', $data, 'admin');
		}
		else
		{
			//success
			$parent = $this->input->post('cat_parent', TRUE);
			if ($parent=='') 
			{
				$parent=0;
			}
			$cat_uri = $this->input->post('cat_uri', TRUE);
			$data = array(
				'cat_uri' => $cat_uri, 
				'cat_name' => $this->input->post('cat_name', TRUE),
				'cat_description' => $this->input->post('cat_description', TRUE),
				'cat_parent' => $parent,
				'cat_display' => $this->input->post('cat_display', TRUE),
				'cat_order' => $this->input->post('cat_order', TRUE)
			);
			$var = $this->category_model->add_category($data);
			$this->revert('admin/categories/');
		}
		
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Duplicate Article
	* 
	* @access	public
	*/
	function duplicate()
	{
		$data['nav'] = 'categories';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper('form');
		$id = (int) $this->uri->segment(4, 0);
		$data['art'] = $this->category_model->get_cat_by_id($id);
		$data['options'] = $this->category_model->get_cats_for_select('',0);
		$data['action'] = 'add';
		$this->init_model->display_template('categories/form', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Delete Category
	* 
	* @access	public
	*/
	function delete()
	{
		$data['nav'] = 'categories';
		if ( ! $this->auth->check_level(3))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$id = (int) $this->uri->segment(4, 0);
		$this->db->delete('categories', array('cat_id' => $id));
		$this->core_events->trigger('categories/delete', $id);
		$this->revert('admin/categories/');
	}
	
}

/* End of file categories.php */
/* Location: ./upload/includes/application/controllers/admin/categories.php */ 