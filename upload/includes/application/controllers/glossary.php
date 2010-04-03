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
 * Glossary Controller
 *
 * Handles the glossary page
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/glossary.html
 * @version 	$Id: glossary.php 46 2009-07-28 17:32:07Z suzkaw68 $
 */
class Glossary extends Controller
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Glossary Controller Initialized');
		$this->load->model('init_model');
		$this->load->model('category_model');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Show the home page
	*
	* @access	public
	*/
	function index()
	{
		$this->db->from('glossary')->orderby('g_term', 'asc');
		$query = $this->db->get();
		$data['title'] = $this->lang->line('kb_glossary') . ' | '. $this->init_model->get_setting('site_name');
		$data['glossary'] = $query;
		$data['letter'] = range('a', 'z');
		
		$this->init_model->display_template('glossary', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Term
	*
	* Find the key term
	*
	* @access	public
	*/
	function term($term='')
	{
		$term = $this->input->xss_clean($term);
		$this->db->from('glossary');
		if($term == 'sym') {
			$this->db->where('g_term LIKE', '.%');
			$this->db->orwhere('g_term LIKE', '0%');
			$this->db->orwhere('g_term LIKE', '1%');
			$this->db->orwhere('g_term LIKE', '2%');
			$this->db->orwhere('g_term LIKE', '3%');
			$this->db->orwhere('g_term LIKE', '4%');
			$this->db->orwhere('g_term LIKE', '5%');
			$this->db->orwhere('g_term LIKE', '6%');
			$this->db->orwhere('g_term LIKE', '7%');
			$this->db->orwhere('g_term LIKE', '8%');
			$this->db->orwhere('g_term LIKE', '9%');
		} else {
			$this->db->where('g_term LIKE', $term.'%');
		}
		$query = $this->db->get();
		$data['glossary'] = $query;
		$data['letter'] = range('a', 'z');
		$data['title'] = $this->lang->line('kb_glossary') . ' | '. $this->init_model->get_setting('site_name');
		
		$this->init_model->display_template('glossary', $data);
	}
}

/* End of file glossary.php */
/* Location: ./upload/includes/application/controllers/glossary.php */ 