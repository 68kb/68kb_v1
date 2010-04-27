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
 * Admin Utility Controller
 *
 * Handles utilities
 *
 * @package		68kb
 * @subpackage	Admin_Controllers
 * @category	Admin_Controllers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/articles.html
 * @version 	$Id: articles.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class Articles extends Controller
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
		$this->load->model('category_model');
		$this->load->model('article_model');
		$this->load->model('tags_model');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Index Controller
	*
	* Redirects to this->grid
	*
	* @access	public
	*/
	function index()
	{
		$data='';
		$cookie = get_cookie('articlesgrid_orderby', TRUE);
		$cookie2 = get_cookie('articlesgrid_orderby_2', TRUE);
		if($cookie<>'' && $cookie2 <> '')
		{
			redirect('admin/articles/grid/orderby/'.$cookie.'/'.$cookie2);
		}
		else
		{
			redirect('admin/articles/grid/');
		}
		$this->init_model->display_template('articles/grid', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Revert
	*
	* Show a message and redirect the user
	* 
	* @access	public
	* @param	string -- The location to goto
	* @return	array
	*/
	function revert($goto)
	{
		$data['nav'] = 'articles';
		$data['goto'] = $goto;
		$this->init_model->display_template('content', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Grid
	*
	* Show a table of articles
	* 
	* It assume this uri sequence:
	* /controller/simplepagination/[offset] 
	* or 
	* /controller/simplepagination/orderby/fieldname/orientation/[offset]
	*
	* @link http://codeigniter.com/forums/viewthread/45709/#217816
	* @access	public
	* @return	array
	*/
	function grid()
	{
		#### settings ###
		$data['nav'] = 'articles';
		$this->load->library("pagination");
		$this->load->helper(array('form', 'url'));
		$this->load->model('users_model');
		$data['authors'] = $this->users_model->get_users();
		$data['categories'] = $this->category_model->get_cats_for_select('',0);
		
		$config['per_page'] = 25; 
	
		#### db init ###
		$data['paginate'] = TRUE;
		//total number of rows
		$config['total_rows'] = $this->db->count_all('articles');
	
		//prepare active record for new query (with limit/offeset/orderby)
		$this->db->distinct();
		$this->db->select('articles.article_id, article_uri, article_title, article_display, article_date, article_hits');
		$this->db->from("articles");
		$this->db->join('article2cat', 'articles.article_id = article2cat.article_id', 'left');
		
		// User Level
		if ($this->session->userdata('level') == 4)
		{
			$this->db->where('article_author', $this->session->userdata['userid']);
		}
		
		#### SEARCHING #### 
		if($this->input->post('searchtext') != '')
		{
			$q = $this->input->post('searchtext', TRUE);
			$data['q'] = $q;
			$this->db->like('article_title', $q);
			$this->db->orlike('article_short_desc', $q);
			$this->db->orlike('article_description', $q);
			$this->db->orlike('article_uri', $q);
		}
		if($this->input->post('article_display') != '')
		{
			$this->db->where('article_display', $this->input->post('article_display'));
			$data['s_display'] = $this->input->post('article_display', TRUE);
		}
		if($this->input->post('a_author') != 0)
		{
			$this->db->where('article_author', $this->input->post('a_author'));
			$data['s_author'] = $this->input->post('a_author', TRUE);
		}
		if($this->input->post('cat') != 0)
		{
			$this->db->where($this->db->dbprefix('article2cat').'.category_id', $this->input->post('cat'));
			$data['s_cat'] = $this->input->post('cat', TRUE);
		}
		#### sniff uri/orderby ###
			
		$segment_array = $this->uri->segment_array();
		$segment_count = $this->uri->total_segments();
		
		$allowed = array('article_id','article_uri', 'article_title', 'article_display', 'a_hits', 'article_date');
		 
		//segments
		$do_orderby = array_search("orderby",$segment_array);
		$asc = array_search("asc",$segment_array);
		$desc = array_search("desc",$segment_array);
		
		//do orderby
		if ($do_orderby !== FALSE)
		{
			$orderby = $this->uri->segment($do_orderby+1);
			if( ! in_array( trim ( $orderby ), $allowed )) 
			{
				$orderby = 'article_id';	
			}
			$this->db->order_by($orderby, $this->uri->segment($do_orderby+2));
		} 
		else 
		{
			$orderby = 'article_id';
		}
		
		$data['orderby'] = $orderby;
		$data['sort'] = $this->uri->segment($do_orderby+2);
		if ($data['sort'] == 'asc') 
		{
			$data['opp'] = 'desc';
		} 
		else 
		{
			$data['opp'] = 'asc';
		}
			
		//set cookie
		$cookie = array(
				'name'   => 'articlesgrid_orderby',
				'value'  => $this->uri->segment($do_orderby+1),
				'expire' => '86500'
			);
		$cookie2 = array(
				'name'   => 'articlesgrid_orderby_2',
				'value'  => $this->uri->segment($do_orderby+2),
				'expire' => '86500'
			);
               
		set_cookie($cookie);
		set_cookie($cookie2);
		
		#### pagination & data subset ###
			
		//remove last segment (assume it's the current offset)
		if($this->input->post('search') == 'go') 
		{
			$data['paginate']=false;
		}
		elseif (ctype_digit($segment_array[$segment_count]))
		{
			$this->db->limit($config['per_page'], $segment_array[$segment_count]);
			array_pop($segment_array);
		} 
		else 
		{
			$this->db->limit($config['per_page']);
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		$results = array();
		foreach ($query->result_array() as $art_row)
		{
			$art_row['cats'] = $this->category_model->get_cats_by_article($art_row['article_id']);
			$results[] = $art_row;
		}
		
		$data['items'] = $results; //$query->result_array();

		$config['base_url'] = site_url(join("/",$segment_array));
		$config['uri_segment'] = count($segment_array)+1;
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();	   
		
		$this->init_model->display_template('articles/grid', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Edit Article
	* 
	* @access public
	*/
	function edit()
	{
		$data['nav'] = 'articles';
		
		$this->load->library('form_validation');
		$id = (int)$this->uri->segment(4, 0);
		if($id == '')
		{
			$id=$this->input->post('article_id');
		}
		if($id == '')
		{
			redirect('admin/articles/');
		}
		if($this->session->flashdata('error'))
		{
			$data['error'] = $this->session->flashdata('error');
		}

		$this->load->helper(array('form', 'url'));
		$data['art'] = $this->article_model->get_article_by_id($id);
		$data['options'] = $this->category_model->get_cats_for_select('',0, $id, TRUE);
		$data['attach'] = $this->article_model->get_attachments($id);
		$data['action'] = 'modify';
		
		$level = $this->auth->check_level(4);
		if ( ! $level)
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		elseif ($this->session->userdata('level') == 4 && $this->session->userdata('userid') != $data['art']->article_author)
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}

		$continue = true;
		
		$this->form_validation->set_rules('article_title', 'lang:kb_title', 'required');
		$this->form_validation->set_rules('article_uri', 'lang:kb_uri', 'alpha_dash');
		$this->form_validation->set_rules('article_keywords', 'lang:kb_keywords', 'trim|xss_clean');
		$this->form_validation->set_rules('article_short_desc', 'lang:kb_short_description', 'trim|xss_clean');
		$this->form_validation->set_rules('article_description', 'lang:kb_description', 'required|trim|xss_clean');
		$this->form_validation->set_rules('article_display', 'lang:kb_display', 'trim');
		$this->form_validation->set_rules('article_order', 'lang:kb_weight', 'numeric');
		$this->core_events->trigger('articles/validation');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('articles/edit', $data, 'admin');
		}
		else
		{
			//success
			$edit_data = array(
				'article_uri' => $this->input->post('article_uri', TRUE), 
				'article_title' => $this->input->post('article_title', TRUE),
				'article_keywords' => $this->input->post('article_keywords', TRUE),
				'article_short_desc' => $this->input->post('article_short_desc', TRUE),
				'article_description' => $this->input->post('article_description', TRUE),
				'article_display' => $this->input->post('article_display', TRUE),
				'article_order' => $this->input->post('article_order', TRUE)
			);
			if ($this->article_model->edit_article($id, $edit_data))
			{
				//$this->tags_model->insert_tags($id, $this->input->post('tags'));
				$this->category_model->insert_cats($id, $this->input->post('cat'));
				
				//now file uploads
				if ($_FILES['userfile']['name'] != "") 
				{
					$target = KBPATH .'uploads/'.$id;
					$this->_mkdir($target);
					$config['upload_path'] = $target;
					$config['allowed_types'] = $this->config->item('attachment_types');
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload())
					{
						$this->session->set_flashdata('error', $this->upload->display_errors());
						redirect('admin/articles/edit/'.$id);
					}	
					else
					{
						$upload = array('upload_data' => $this->upload->data());
						$insert = array(
							'article_id' => $id, 
							'attach_name' => $upload['upload_data']['file_name'],
							'attach_type' => $upload['upload_data']['file_type'],
							'attach_size' => $upload['upload_data']['file_size']
						);
						$this->db->insert('attachments', $insert);
						$data['attach'] = $this->article_model->get_attachments($id);
					}
				}
				
				//final continue
				if($continue)
				{
					$this->core_events->trigger('articles/edit', $id);
				    if (isset($_POST['save']) && $_POST['save']<>"")
				    {
				    	redirect('admin/articles/edit/'.$id);
				    }
				    else
				    {
				    	$this->revert('admin/articles/');
				    }
				}
			}
			else
			{
				$data['error'] = 'Could not edit article';
				$this->init_model->display_template('articles/edit', $data, 'admin');
			}
		}
	}

	// ------------------------------------------------------------------------
	
	/**
	* Add Article
	* 
	* @access	public
	*/
	function add()
	{
		$this->load->library('form_validation');
		$data['nav'] = 'articles';
		if ( ! $this->auth->check_level(4))
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		$this->load->helper(array('form', 'url'));
		$data['options'] = $this->category_model->get_cats_for_select('',0, '', TRUE);
		$data['action'] = 'add';
		
		$this->form_validation->set_rules('article_title', 'lang:kb_title', 'required');
		$this->form_validation->set_rules('article_uri', 'lang:kb_uri', 'alpha_dash');
		$this->form_validation->set_rules('article_keywords', 'lang:kb_keywords', 'trim|xss_clean');
		$this->form_validation->set_rules('article_short_desc', 'lang:kb_short_description', 'trim|xss_clean');
		$this->form_validation->set_rules('article_description', 'lang:kb_description', 'trim|xss_clean');
		$this->form_validation->set_rules('article_display', 'lang:kb_display', 'trim');
		$this->form_validation->set_rules('article_order', 'lang:kb_weight', 'numeric');
		$this->core_events->trigger('articles/validation');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->init_model->display_template('articles/add', $data, 'admin');
		}
		else
		{
			//success
			$cats = $this->input->post('cat');
			$article_uri = $this->input->post('article_uri', TRUE);
			$data = array(
				'article_uri' => $article_uri, 
				'article_author' => $this->session->userdata('userid'), 
				'article_title' => $this->input->post('article_title', TRUE),
				'article_keywords' => $this->input->post('article_keywords', TRUE),
				'article_short_desc' => $this->input->post('article_short_desc', TRUE),
				'article_description' => $this->input->post('article_description', TRUE),
				'article_display' => $this->input->post('article_display', TRUE),
				'article_order' => $this->input->post('article_order', TRUE)
			);
			$id = $this->article_model->add_article($data);
			if (is_int($id))
			{
				//$tags = $this->input->post('tags');
				//$this->tags_model->insert_tags($id, $tags);
				$this->category_model->insert_cats($id, $cats);
				$this->core_events->trigger('articles/add', $id);
				
				if ($_FILES['userfile']['name'] != "") 
				{
					$target = KBPATH .'uploads/'.$id;
					$this->_mkdir($target);
					$config['upload_path'] = $target;
					$config['allowed_types'] = $this->config->item('attachment_types');
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload())
					{
						$this->session->set_flashdata('error', $this->upload->display_errors());
						redirect('admin/articles/edit/'.$id);
					}
					else
					{
						$upload = array('upload_data' => $this->upload->data());
						$insert = array(
							'article_id' => $id, 
							'attach_name' => $upload['upload_data']['file_name'],
							'attach_type' => $upload['upload_data']['file_type'],
							'attach_size' => $upload['upload_data']['file_size']
						);
						$this->db->insert('attachments', $insert);
						$data['attach'] = $this->article_model->get_attachments($id);
					}
				}
			    if (isset($_POST['save']) && $_POST['save']<>"")
			    {
			    	redirect('admin/articles/edit/'.$id);
			    }
			    else
			    {
			    	$this->revert('admin/articles/');
			    }
			}
			else
			{
				$this->revert('admin/articles/');
			}
		}
		
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Delete Article
	* 
	* @access	public
	*/
	function delete()
	{
		$data['nav'] = 'articles';
		$id = (int) $this->uri->segment(4, 0);
		$level = $this->auth->check_level(4);
		if ( ! $level)
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		elseif ($this->session->userdata('level') == 4 && $this->session->userdata('userid') != $id)
		{
			$data['not_allowed'] = TRUE;
			$this->init_model->display_template('content', $data, 'admin'); 
			return FALSE;
		}
		
		$this->article_model->delete_article($id);
		$this->revert('admin/articles/');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Update status
	* 
	* @access	public
	*/
	function update()
	{
		$ordID = $this->input->post('articleid', TRUE);
		$newstatus = $_POST["newstatus"];
		$data['nav'] = 'articles';
		foreach ($_POST['articleid'] AS $key)
		{
			if ($newstatus == 'Y')
			{
				//active
				$data = array('article_display' => 'Y');
				$this->db->where('article_id', $key);
				$this->db->update('articles', $data);
			}
			elseif ($newstatus == 'N')
			{
				//not active
				$data = array('article_display' => 'N');
				$this->db->where('article_id', $key);
				$this->db->update('articles', $data);
			}
			elseif ($newstatus == 'D')
			{
				//delete
				$level = $this->auth->check_level(4);
				if ( ! $level)
				{
					$data['not_allowed'] = TRUE;
					$this->init_model->display_template('content', $data, 'admin'); 
					return FALSE;
				}
				elseif ($this->session->userdata('level') == 4 && $this->session->userdata('userid') != $key)
				{
					$data['not_allowed'] = TRUE;
					$this->init_model->display_template('content', $data, 'admin'); 
					return FALSE;
				}
				$this->article_model->delete_article($key);
			}
		}
		$this->revert('admin/articles/');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Delete an Uploaded file.
	* 
	* @access	private
	*/
	function upload_delete()
	{
		$this->load->helper('file');
		$id = (int) $this->uri->segment(4, 0);
		
		$this->db->select('attach_id, article_id, attach_name')->from('attachments')->where('attach_id', $id);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$article_id = $row->article_id;
			unlink(KBPATH .'uploads/'.$row->article_id.'/'.$row->attach_name);
			$this->db->delete('attachments', array('attach_id' => $id));
			redirect('admin/articles/edit/'.$article_id.'/#attachments');
		}
		else
		{
			redirect('admin/articles/');
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Attempt to make a directory to house uploaded files.
	* 
	* @access	private
	*/
	function _mkdir($target) 
	{
		// from php.net/mkdir user contributed notes
		if(file_exists($target)) 
		{
			if( ! @is_dir($target))
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		// Attempting to create the directory may clutter up our display.
		if(@mkdir($target)) 
		{
			$stat = @stat(dirname($target));
			$dir_perms = $stat['mode'] & 0007777;  // Get the permission bits.
			@chmod($target, $dir_perms);
			return true;
		} 
		else 
		{
			if(is_dir(dirname($target)))
			{
				return false;
			}
		}

		// If the above failed, attempt to create the parent node, then try again.
		if ($this->_mkdir(dirname($target)))
		{
			return $this->_mkdir($target);
		}

		return false;
	}
	
}

/* End of file articles.php */
/* Location: ./upload/includes/application/controllers/admin/articles.php */ 