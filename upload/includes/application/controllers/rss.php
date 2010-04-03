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
 * RSS Controller
 *
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/articles.html#rss
 * @version 	$Id: rss.php 76 2009-07-31 14:47:18Z suzkaw68 $
 */
class Rss extends Controller {

	function __construct()
	{
		parent::__construct();
		log_message('debug', 'RSS Controller Initialized');
		$this->load->model('init_model');	
		$this->load->model('article_model');
		$this->load->helper('xml');	
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Default RSS View
	 *
	 * Show the latest articles
	 *
	 * @uses	show_feed
	 */
	function index()
	{
		$data['feed_name'] = $this->init_model->get_setting('site_name');
		$data['feed_url'] = base_url();
		$data['page_description'] = $this->init_model->get_setting('site_description');
		$data['creator_email'] = $this->init_model->get_setting('site_email');
		$data['articles'] = $this->article_model->get_latest(); 
		$this->show_feed($data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Default RSS View
	 *
	 * Show the latest articles
	 *
	 * @param	string - The category uri
	 * @uses	show_feed
	 */
	function category($uri = '')
	{
		$this->load->model('category_model');
		if($uri <> '')
		{
			$uri = $this->input->xss_clean($uri);
			$data['cat']=$this->category_model->get_cat_by_uri($uri);
			if($data['cat'])
			{
				$id = $data['cat']->cat_id;
				$data['encoding'] = 'utf-8';
				$data['feed_name'] = $data['cat']->cat_name. ' | '. $this->init_model->get_setting('site_name');
				$data['feed_url'] = base_url();
				$data['page_description'] = $this->init_model->get_setting('site_description');
				$data['creator_email'] = $this->init_model->get_setting('site_email');
				$data['articles'] = $this->article_model->get_articles_by_catid($id);
				$this->show_feed($data);
			}
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Dislay the template
	 *
	 * @access	public
	 */
	function show_feed($data)
	{
		echo $this->init_model->load_body('rss', 'front', $data);
	}

}
/* End of file rss.php */
/* Location: ./upload/includes/application/controllers/rss.php */