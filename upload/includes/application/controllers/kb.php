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
 * Main Home Page Controller
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: kb.php 89 2009-08-13 01:54:20Z suzkaw68 $
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
		log_message('debug', 'KB Controller Initialized');
		$this->load->model('init_model');
		$this->load->model('category_model');
		if(!$this->db->table_exists('articles'))
		{
			redirect('setup');
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Show the home page
	*
	* @uses category_model::get_categories_by_parent
	* @uses article_model::get_most_popular
	* @uses article_model::get_latest
	* @access	public
	*/
	function index()
	{
		$this->load->helper('form');
		$this->load->model('article_model');
		$this->load->model('tags_model');
		
		$this->benchmark->mark('cats_start');
		$data['parents'] = $this->category_model->get_categories_by_parent(0);
		$data['cat_tree'] = $this->category_model->get_cats_for_select();
		$this->benchmark->mark('cats_end');
		
		$this->benchmark->mark('articles_start');
		$data['pop'] = $this->article_model->get_most_popular(10);
		$data['latest'] = $this->article_model->get_latest(10);
		$this->benchmark->mark('articles_end');
		
		$data['title'] = $this->init_model->get_setting('site_name');
		
		$this->init_model->display_template('home', $data);
	}
}

/* End of file kb.php */
/* Location: ./upload/includes/application/controllers/kb.php */ 