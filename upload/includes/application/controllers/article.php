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
 * Article Controller
 *
 * Handles the article pages
 *
 * @package		68kb
 * @subpackage	Controllers
 * @category	Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/articles.html
 * @version 	$Id: article.php 143 2009-12-04 13:20:38Z suzkaw68 $
 */
class Article extends Controller
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Article Controller Initialized');
		$this->load->model('init_model');
		$this->load->model('users_model');
		$this->load->model('category_model');
		$this->load->model('comments_model');
		$this->load->model('article_model');
		$this->load->helper('smiley');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Show a single article
	*
	* @access	public
	* @param	string	the unique uri
	* @return	array
	*/
	function index($uri='')
	{
		$this->load->helper('typography');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->helper('gravatar');
		$data['title'] = $this->init_model->get_setting('site_name');
		if($uri<>'' && $uri<>'index') 
		{
			$uri = $this->input->xss_clean($uri);
			$article = $this->article_model->get_article_by_uri($uri);
			if($article)
			{
				$data['article'] = $article;
				$this->article_model->add_hit($data['article']->article_id);
				
				//format description
				$data['article']->article_description = $this->article_model->glossary($data['article']->article_description);
				
				// call hooks
				$arr = array('article_id' => $data['article']->article_id, 'article_title' => $data['article']->article_title);
				if($this->core_events->trigger('article/title', $arr) != '')
				{
					$data['article']->article_description = $this->core_events->trigger('article/title', $arr);
				}
				$arr = array('article_id' => $data['article']->article_id, 'article_description' => $data['article']->article_description);
				if($this->core_events->trigger('article/description', $arr) != '')
				{
					$data['article']->article_description = $this->core_events->trigger('article/description', $arr);
				}
				
				$data['article_cats'] = $this->category_model->get_cats_by_article($data['article']->article_id);
				$data['attach'] = $this->article_model->get_attachments($data['article']->article_id);
				$data['author'] = $this->users_model->get_user_by_id($data['article']->article_author);
				
				$data['title'] = $data['article']->article_title. ' | '. $this->init_model->get_setting('site_name');
				$data['meta_keywords'] = $data['article']->article_keywords;
				$data['meta_description'] = $data['article']->article_short_desc;
				$data['comments'] = $this->comments_model->get_article_comments($data['article']->article_id);
				$data['comments_total'] = $this->comments_model->get_article_comments_count($data['article']->article_id);
				
				$data['comment_author'] = get_cookie('kb_author', TRUE);
				$data['comment_author_email'] = get_cookie('kb_email', TRUE);
				
				$data['comment_template'] = $this->init_model->load_body('comments', 'front', $data);
			}
			else
			{
				$data = '';
			}
		}
		else
		{
			$data='';
		}
		$this->init_model->display_template('article', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Show the printer page
	*
	* @access	public
	*/
	function printer()
	{
		$this->load->helper('typography');
		$uri = $this->uri->segment(3, 0);
		if($uri<>'' && $uri<>'index') 
		{
			$uri = $this->input->xss_clean($uri);
			$data['article']=$this->article_model->get_article_by_uri($uri);
			$data['article']->article_description = parse_smileys($data['article']->article_description, $this->config->item('base_url')."/images/");
			$data['title'] = $data['article']->article_title. ' | '. $this->init_model->get_setting('site_name');
		}
		else
		{
			$data='';
		}
		echo $this->init_model->load_body('printer', 'front', $data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Add Comment
	* 
	* @access	public
	*/
	function comment()
	{
		if($this->init_model->get_setting('comments') != 'Y')
		{
			redirect('/kb');
		}
		
		$this->load->model('comments_model');
		$this->load->library('form_validation');
		
		$this->load->helper('cookie', 'session');
		$this->load->helper('form');
		
		$this->form_validation->set_rules('comment_author', 'lang:kb_name', 'required');
		$this->form_validation->set_rules('comment_author_email', 'lang:kb_email', 'required|valid_email');
		$this->form_validation->set_rules('comment_content', 'lang:kb_description', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->index($this->input->post('uri'));
		}
		else
		{
			//success
			$parent = $this->input->post('cat_parent', TRUE);
			$author = $this->input->post('comment_author', TRUE);
			$email = $this->input->post('comment_author_email', TRUE);
			$comment = $this->input->post('comment_content', TRUE);
			
			set_cookie('kb_author', $author, '86500');
			set_cookie('kb_email', $email, '86500');
			
			$data = array(
				'comment_article_ID' => $this->input->post('comment_article_ID', TRUE), 
				'comment_author' => $author,
				'comment_author_email' => $email,
				'comment_content' => $comment,
				'comment_author_IP' => $this->input->ip_address()
			);
			$id = $this->comments_model->add_comment($data);
			$this->comments_model->email_admin($id, $data);
			$this->index($this->input->post('uri'));
		}
		
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Rate Article
	* 
	* @access	public
	*/
	function rate()
	{
		$article_uri = $this->input->post('article_uri', TRUE);
		$article_id = (int)$this->input->post('article_id', TRUE);
		$rating = $this->input->post('rating', TRUE);
		if($rating == 1)
		{
			$rating = '+1';
		}
		else
		{
			$rating = '-1';
		}
		$this->db->set('article_rating', 'article_rating'.$rating, FALSE);
		$this->db->where('article_id', $article_id);
		$this->db->update('articles');
		$this->session->set_flashdata('rating', TRUE);
		redirect('article/'.$article_uri.'/#rating'); 
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Remap
	*
	* Need to document this.
	*
	* @link http://codeigniter.com/user_guide/general/controllers.html#remapping
	* @access	public
	* @param	string	the unique uri
	* @return	array
	*/
	function _remap($method)
	{
		if($method == 'printer')
		{
			$this->printer();
		}
		elseif($method == 'comment')
		{
			$this->comment();
		}
		elseif($method == 'rate')
		{
			$this->rate();
		}
		else
		{
			$this->index($method);	
		}
	}
}

/* End of file article.php */
/* Location: ./upload/includes/application/controllers/article.php */ 