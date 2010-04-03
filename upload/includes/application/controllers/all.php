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
 * All Controller. Shows all articles
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/articles.html
 * @version 	$Id: all.php 46 2009-07-28 17:32:07Z suzkaw68 $
 */
class All extends Controller
{
	/**
	* Constructor
	*
	* @return 	void
	*/
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'All Controller Initialized');
		$this->load->model('init_model');
		$this->load->model('category_model');
		$this->load->model('article_model');
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
		$data['parents'] = $this->category_model->get_categories_by_parent(0);
		foreach($data['parents']->result() as $row)
		{
			$data['articles'][$row->cat_id] = $this->article_model->get_articles_by_catid($row->cat_id);
		}
		$data['title'] = $this->init_model->get_setting('site_name');
		
		$this->init_model->display_template('all', $data);
	}
}

/* End of file kb.php */
/* Location: ./upload/includes/application/controllers/kb.php */