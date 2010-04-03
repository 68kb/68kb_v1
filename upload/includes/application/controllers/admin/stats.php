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
 * Admin Stats Controller
 *
 * Handles displaying stats
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/settings.html
 * @version 	$Id: stats.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Stats extends Controller
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
	* @access	public
	*/
	function index()
	{
		$data['nav'] = 'settings';
		if ( ! $this->auth->check_level(1))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$views = 0;
		$data['cats'] = $this->db->count_all('categories');
		$data['articles'] = $this->db->count_all('articles');
		$this->db->select('article_hits')->from('articles');
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$views += $row->article_hits;
		}
		$data['views'] = $views;
		$this->init_model->display_template('stats/main', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Show most viewed
	*
	* @access	public
	*/
	function viewed()
	{
		$data['nav'] = 'settings';
		$this->db->from('articles')->orderby('article_hits', 'DESC');
		$data['query'] = $this->db->get();
		$this->init_model->display_template('stats/viewed', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Search Log
	*
	* @access	public
	*/
	function searchlog()
	{
		$data['nav'] = 'settings';
		$prefix = $this->db->dbprefix;
		
		$this->db->select('searchlog_term, COUNT(*) AS "total"', FALSE); 
		$this->db->group_by('searchlog_term')->order_by('total', 'DESC');
		$data['query'] = $this->db->get('searchlog');
		$this->init_model->display_template('stats/searchlog', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Search Log
	*
	* @access	public
	*/
	function rating()
	{
		$data['nav'] = 'settings';
		$this->db->from('articles')->orderby('article_rating', 'DESC')->limit(50);
		$data['query'] = $this->db->get();
		$this->init_model->display_template('stats/rating', $data, 'admin');
	}
}

/* End of file stats.php */
/* Location: ./upload/includes/application/controllers/admin/stats.php */ 