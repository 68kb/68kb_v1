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
 * Fields Controller
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/settings.html
 * @version 	$Id: fields.php 84 2009-08-05 03:26:00Z suzkaw68 $
 */
class fields extends controller
{
	
	/**
	* Constructor
	*
	* @access	public
	*/
	function __construct()
	{
		parent::__construct();
		$this->load->model('init_model');
		$this->load->model('fields_model');
		$this->load->helper('cookie');
		$this->load->library('auth');
		$this->auth->restrict();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Show utility list
	*
	* @access	public
	*/
	function index()
	{
		$data['nav']='articles';
		$data['options'] = $this->fields_model->get_fields();
		$this->init_model->display_template('fields/grid', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Add Field
	* 
	* @access	public
	*/
	function add()
	{
		$this->load->library('form_validation');
		$data['nav'] = 'articles';
		$this->load->helper('form');
		$id = (int)$this->uri->segment(4, 0);
		$data['action']=site_url('admin/fields/add/');
		
		$this->form_validation->set_rules('field_name', 'lang:kb_field_name', 'required');
		$this->form_validation->set_rules('field_type', 'lang:kb_field_type', 'rquired');
		$this->form_validation->set_rules('field_size', 'Size', 'numeric');
		$this->form_validation->set_rules('field_validation', 'lang:kb_parent_cat', '');
		$this->form_validation->set_rules('field_label', 'label', '');
		$this->form_validation->set_rules('field_options', 'options', '');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('fields/form', $data, 'admin');
		}
		else
		{
			//success
			$data = array(
				'field_name' => $this->input->post('field_name', TRUE),
				'field_type' => $this->input->post('field_type', TRUE),
				'field_size' => $this->input->post('field_size', TRUE),
				'field_validation' => $this->input->post('field_validation', TRUE),
				'field_label' => $this->input->post('field_label', TRUE),
				'field_options' => $this->input->post('field_options', TRUE)
			);
			$var = $this->fields_model->add_field($data);
			$this->revert('admin/fields/');
		}
		
	}
}

/* End of file utility.php */
/* Location: ./upload/includes/application/controllers/admin/utility.php */