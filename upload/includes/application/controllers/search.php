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
 * Search Controller
 *
 * Allows users to search articles
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: search.php 123 2009-11-13 15:27:14Z suzkaw68 $
 */
class Search extends Controller
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Search Controller Initialized');
		$this->load->model('init_model');
		$this->load->model('category_model');
		$this->load->helper('form');
	}
		
	function index()
	{
		$data['title'] = $this->init_model->get_setting('site_name');
		$data['cat_tree'] = $this->category_model->get_cats_for_select();
		
		$input = $this->input->post('searchtext', TRUE);
		$category = (int)$this->input->post('category', TRUE);
		
		if($input <> '' || $category <> '')
		{
			if ($input)
			{
				$insert = array('searchlog_term' => $input);
				$this->db->insert('searchlog', $insert);
			}
			
			
			$this->db->from('articles');
			$this->db->join('article2cat', 'articles.article_id = article2cat.article_id', 'left');
			if($category)
			{
				$this->db->where('category_id', $category);
			}
			$this->db->where('article_display', 'Y');
			
			// This is a hack found here:
			// http://codeigniter.com/forums/viewthread/122223/
			// And here:
			// http://68kb.com/support/topic/better-keyword-handling-in-search-a-solution
			if($input)
		    {
		    	$keywords = array();
		    	$keywords = explode(" ", $input);
		    	$numkeywords = count($keywords);
		    	$wherestring = "";
		    	for ($i = 0; $i < $numkeywords; $i++)
		    	{
					if ($i > 0)
					{
						$wherestring .= " AND ";
					}
					$wherestring = $wherestring .
		    			" (article_title LIKE '%". mysql_real_escape_string($keywords[$i]) .
		    			"%' OR article_short_desc LIKE '%" . mysql_real_escape_string($keywords[$i]) .
		    			"%' OR article_description LIKE '%". mysql_real_escape_string($keywords[$i]) ."%') ";
		    	}
		    	$this->db->where($wherestring,NULL,FALSE);
		    }
			
			$this->db->orderby('article_order', 'DESC');
			$this->db->orderby('article_hits', 'DESC');
			
			$data['articles'] = $this->db->get();
			
			$data['searchtext'] = $input;
			$data['category'] = $category;
		}
		$this->init_model->display_template('search', $data);
	}
}

/* End of file search.php */
/* Location: ./upload/includes/application/controllers/search.php */