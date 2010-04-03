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
 * Admin Controller
 *
 * Handles the admin home page as well as login and logout.
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: kb.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Kb extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('init_model');
		$this->load->library('session');
		$this->load->helper('cookie');
		$this->load->helper('form');
		$this->load->library('auth');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Show the admin home page or revert to login
	*
	* @access	public
	*/
	function index()
	{
		$this->auth->restrict();
		$this->load->helper('gravatar');
		$this->load->helper('text');
	    $this->load->model('init_model', 'init');
		$data['nav'] = 'dashboard';
		$this->core_events->trigger('admin/home');
		// Get article count for virgin notice
		$data['cats'] = $this->db->count_all('categories');
		$data['comment_count'] = $this->db->count_all('comments');
		$data['articles'] = $this->db->count_all('articles');
		if ($data['articles'] == 0)
		{
			$data['first_time'] = TRUE;
		}
		$data['install'] = $this->checkinstall();
		$data['latest'] = $this->init_model->get_setting('latest');
		// Get pending comments
		$this->db->select('comment_ID, comment_author, comment_author_email, comment_author_IP, comment_date, comment_content, comment_approved, article_title, article_uri');
		$this->db->from("comments");
		$this->db->join('articles', 'comments.comment_article_ID = articles.article_id', 'left');
		$this->db->limit(5);
		$query = $this->db->get();
		$data['comments'] = $query;
		$this->init->display_template('home', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get News
	*
	* Get the latest 68kb news
	*
	* @access	public
	*/
	function get_news()
	{
		$this->load->library('simplepie');
		$link = 'http://feeds.feedburner.com/68KB';
		$feed = new SimplePie();
		$feed->set_feed_url($link);
		$feed->enable_cache(false);
		$feed->init();
		$feed->handle_content_type();
		$output = '';
		$i = 0;
		if ($feed->data)
		{
			$items = $feed->get_items();
			foreach ($items as $item)
			{
				if ($i < 3) 
				{
					$output.="<strong><a href='".$item->get_permalink()."' target='_blank'>".$item->get_title()."</a></strong> - ".$item->get_date('j M Y');
					$output.="<p>".$item->get_description() ."</p>";
				}
				$i++;
			}
		}
		echo $output;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Login Controller
	*
	* Allow the admin to login
	*
	* @access	public
	*/
	function login()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'lang:kb_username', 'required');
	    $this->form_validation->set_rules('password', 'lang:kb_password', 'required');
	    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	    $data='';
	    if ($this->form_validation->run() == false)
	    {
			$this->load->view('admin/default/login', $data);
	    }
	    else
	    {
			$login = array($this->input->post('username'), $this->input->post('password'));
			if($this->auth->process_login($login))
			{
				redirect('admin');
			}
			else
			{
				$data['error']=$this->lang->line('kb_login_invalid');
				$this->load->view('admin/default/login', $data);
			}
	    }	
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Logout Controller
	*
	* Log the user out.
	*
	* @access	public
	*/
	function logout()
	{
		if ($this->auth->logout()) 
		redirect('/admin/kb/login/');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Check if the install file exists
	 *
	 * @access	private
	 * @param	string	the body
	 * @return	bool
	 */
	private function checkinstall()
	{
		$file = APPPATH.'controllers/setup/install.php';
		if ( ! file_exists($file))
		{
			return false;
		}
		return true;
	}
}

/* End of file kb.php */
/* Location: ./upload/includes/application/controllers/admin/kb.php */ 