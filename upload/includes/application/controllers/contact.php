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
 * @version 	$Id: contact.php 143 2009-12-04 13:20:38Z suzkaw68 $
 */
class Contact extends Controller
{
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Contact Controller Initialized');
		$this->load->model('init_model');
		$this->load->model('category_model');
		$this->load->model('comments_model');
		$this->load->model('article_model');
		$this->load->helper('smiley');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Show a contact form
	*
	* @access	public
	*/
	
	function index()
	{
		$data['title'] = $this->lang->line('kb_contact'). ' | '. $this->init_model->get_setting('site_name');
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('subject', 'lang:kb_subject', 'required');
		$this->form_validation->set_rules('name', 'lang:kb_name', 'required');
		$this->form_validation->set_rules('content', 'lang:kb_content', 'required');
		$this->form_validation->set_rules('email', 'lang:kb_email', 'required|valid_email');
		$this->form_validation->set_rules('captcha', 'lang:kb_captcha', 'required|callback_captcha_check');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->plugin('captcha');
			
			/*
				TODO Get the font_path working.
			*/
			$vals = array(
					'word'		 => '',
					'img_path'	 => KBPATH .'uploads/',
					'img_url'	 => base_url() .'uploads/',
					'font_path'	 => '', //KBPATH .'includes/fonts/texb.ttf',
					'img_width'	 => '150',
					'img_height' => 30,
					'expiration' => 7200
				);
			$cap = create_captcha($vals);
			$c_data = array(
					'captcha_id'	=> '',
					'captcha_time'	=> $cap['time'],
					'ip_address'	=> $this->input->ip_address(),
					'word'			=> $cap['word']
				);

			$query = $this->db->insert_string('captcha', $c_data);
			$this->db->query($query);
			$data['cap']= $cap;
			$this->init_model->display_template('contact', $data);
		}
		else
		{
			//success
			//lets get any related article
			
			$this->db->select('*');
			$this->db->from('articles');
		
			$subject = $this->input->post('subject', TRUE);
			$terms = explode(' ', $subject);
			foreach($terms as $row)
			{
				if(strlen($row) > 3)
				{
					$this->db->orlike('article_keywords', $row);
				}
			}
			$this->db->orderby('article_title', 'asc');
			$query = $this->db->get();
			
			$data['articles'] = $query;
			$data['subject'] = $subject;
			$data['name'] = $this->input->post('name', TRUE);
			$data['email'] = $this->input->post('email', TRUE);
			$data['content'] = $this->input->post('content', TRUE);
			
			$this->init_model->display_template('contact_confirm', $data);
		}
	}
	
	function captcha_check($str)
	{
		if ($str == '')
		{
			$this->validation->set_message('captcha_check', 'The %s field can not be blank');
			return FALSE;
		}
		else
		{
			$expiration = time()-7200; // Two hour limit
			$this->db->where('captcha_time < ', $expiration);
			$this->db->delete('captcha'); 
			
			// Then see if a captcha exists:
			$prefix = $this->db->dbprefix;
			$sql = "SELECT COUNT(*) AS count FROM ".$prefix."captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
			$binds = array($str, $this->input->ip_address(), $expiration);
			$query = $this->db->query($sql, $binds);
			$row = $query->row();
	
			if ($row->count == 0)
			{
				$this->form_validation->set_message('captcha_check', 'You must submit the word that appears in the image');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
	
	function submit()
	{
		$this->load->library('email');
		
		$subject = $this->input->post('subject', TRUE);
		$name = $this->input->post('name', TRUE);
		$email = $this->input->post('email', TRUE);
		$content = $this->input->post('content', TRUE);
		
		$to = $this->init_model->get_setting('site_email');
		
		$this->email->from($email, $name);
		$this->email->to($to);
		
		$this->email->subject($subject);
		$this->email->message($content);
		
		if ( ! $this->email->send())
		{
			$data['error'] = $this->email->print_debugger();
		}
		$data['title'] = $this->init_model->get_setting('site_name');
		$this->init_model->display_template('thanks', $data);
	}
	
}

/* End of file contact.php */
/* Location: ./upload/includes/application/controllers/contact.php */ 