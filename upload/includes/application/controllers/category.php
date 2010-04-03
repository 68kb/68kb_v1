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
 * Category Controller
 *
 * Handles the category pages
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/categories.html
 * @version 	$Id: category.php 76 2009-07-31 14:47:18Z suzkaw68 $
 */
class Category extends Controller
{
	/**
	* Constructor
	*
	* @return 	void
	*/
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Category Controller Initialized');
		$this->load->model('init_model');
		$this->load->model('category_model');
		$this->load->model('article_model');
		$this->load->model('comments_model');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Show a single category with all its articles.
	*
	* @access	public
	* @param	string	the unique uri
	* @return	array
	*/
	function index($uri='')
	{
		if($uri<>'' && $uri<>'index') 
		{
			$uri = $this->input->xss_clean($uri);
			$data['cat']=$this->category_model->get_cat_by_uri($uri);
			if($data['cat'])
			{
				$id = $data['cat']->cat_id;
				$data['title'] = $data['cat']->cat_name. ' | '. $this->init_model->get_setting('site_name');
				$data['parents'] = $this->category_model->get_categories_by_parent($id);
				//pagination
				$this->load->library('pagination');

				$config['total_rows'] = $this->article_model->get_articles_by_catid($id, 0, 0, TRUE);
				$config['per_page'] = $this->init_model->get_setting('max_search');

				$config['uri_segment'] = '3';
				$config['base_url'] = site_url("category/". $uri);

				$this->pagination->initialize($config); 
				$data["pagination"] = $this->pagination->create_links();
				
				$data['articles'] = $this->article_model->get_articles_by_catid($id, $config['per_page'], $this->uri->segment(3), FALSE);
			}
			else
			{
				redirect('all');
			}
		}
		else 
		{
			$data['title'] = $this->init_model->get_setting('site_name');
			$data['parents'] = $this->category_model->get_categories_by_parent(0);
		}
		$this->init_model->display_template('category', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Remap
	*
	* Need to document this.
	*
	* @link http://codeigniter.com/user_guide/general/controllers.html#remapping
	* @access	private
	* @param	string	the unique uri
	* @return	array
	*/
	function _remap($method)
	{
		$this->index($method);	
	}
}

/* End of file category.php */
/* Location: ./upload/includes/application/controllers/category.php */ 