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
 * @link		http://68kb.com/user_guide/overview/settings.html
 * @version 	$Id: utility.php 134 2009-12-02 01:29:40Z suzkaw68 $
 */
class utility extends controller
{
	
	/**
	* Constructor
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
		$this->load->dbutil();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Show utility list
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
		$this->init_model->display_template('settings/utilities', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Repair DB
	*
	* @access	public
	*/
	function repair()
	{
		$data['nav'] = 'settings';
		$tables = $this->db->list_tables();
		foreach ($tables as $table)
		{
			if ($this->dbutil->repair_table($table))
			{
				$tb[]=$table;
			}
		} 
		$data['table']=$tb;
		$this->init_model->display_template('settings/utilities', $data, 'admin');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Optimize the db
	*
	* @access	public
	*/
	function optimize()
	{
		$data['nav'] = 'settings';
		$tables = $this->db->list_tables();
		foreach ($tables as $table)
		{
			if ($this->dbutil->optimize_table($table))
			{
				$tb[]=$table;
			}
		} 
		$data['optimized']=$tb;
		$this->init_model->display_template('settings/utilities', $data, 'admin');
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	* Remove cache files
	*
	* @access	public
	*/
	function delete_cache()
	{
		$this->load->helper('file');
		delete_files($this->config->item('cache_path'));
		$this->session->set_flashdata('message', lang('kb_cache_deleted'));
		redirect('admin/utility/'); 
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	* Backup the databse
	*
	* @access	public
	*/
	function backup()
	{
		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup();
		
		$name = '68kb-'.time().'.gz';
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		write_file(KBPATH .'uploads/'.$name, $backup);

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download($name, $backup);
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	* Export to html
	*
	* @access	public
	*/
	function export()
	{
		$this->load->helper('file');
		$this->load->model('category_model');
		$this->load->model('article_model');
		$dir = KBPATH.'uploads/user_guide/';
		$log[] = 'Deleting Files...';
		if (is_dir($dir)) 
		{
			delete_files($dir, TRUE);
			rmdir($dir);
		}
		mkdir($dir, 0755);

		//copy jquery stuff
		$src = KBPATH.'themes/admin/export/jquery-treeview';
		$dst = KBPATH.'uploads/user_guide/jquery-treeview';
		$this->recurse_copy($src, $dst);

		$src = KBPATH.'themes/admin/export/css';
		$dst = KBPATH.'uploads/user_guide/css';
		$this->recurse_copy($src, $dst);
		$log[] = 'Copying Files...';

		//create cat tree
		$this->db->select('cat_id,cat_uri,cat_name,cat_parent')->from('categories')->where('cat_display !=', 'N')->order_by('cat_order DESC, cat_name ASC');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			//$items = $query->result_array();
			foreach ($query->result_array() as $row)
			{
				$categories[$row['cat_id']] = array(
				    'cat_id' => $row['cat_id'], 
				    'cat_name' => $row['cat_name'], 
				    'cat_parent' => $row['cat_parent'],
				    'cat_uri' => $row['cat_uri']
				  );
			}
		}
		
		$log[] = 'Generating Tree...';
		ob_start();
		$this->generate_tree_list($categories, 0, 0);
		$r = ob_get_contents();
		ob_end_clean();
		$data['navigation'] = $r;
		
		$log[] = 'Writing Files...';
		
		$data['settings'] = $this->init_model->get_settings();
		
			$article_query = $this->article_model->get_articles();
			if ($article_query->num_rows() > 0)
			{
				foreach($article_query->result() as $rs)
				{
					$data['title'] = $rs->article_title;
					$data['description'] = $rs->article_description;
					$contents = $this->load->view('admin/export/layout', $data, true);

					$filename = $dir.$rs->article_uri.'.html';
					//echo $filename.'<BR>';
					write_file($filename, $contents, 'x+');
				}
			}
		$log[] = 'Finishing...';
		$log[] = '<a href="'.base_url().'uploads/user_guide/">Preview</a>';
		$data['nav']='settings';
		$data['export']=$log;
		$this->init_model->display_template('settings/utilities', $data, 'admin');
	}
	
	
	function generate_tree_list($array, $parent = 0, $level = 0)
	{
		// Reset the flag each time the function is called
		$has_children = false;

		// Loop through each item of the list array
		foreach($array as $key => $value)
		{
			// For the first run, get the first item with a parent_id of 0 (= root category)
			// (or whatever id is passed to the function)
			//
			// For every subsequent run, look for items with a parent_id matching the current item's key (id)
			// (eg. get all items with a parent_id of 2)
			//
			// This will return false (stop) when it find no more matching items/children
			//
			// If this array item's parent_id value is the same as that passed to the function
			// eg. [parent_id] => 0   == $parent = 0 (true)
			// eg. [parent_id] => 20  == $parent = 0 (false)
			//
			if ($value['cat_parent'] == $parent) 
			{
				// Only print the wrapper ('<ul>') if this is the first child (otherwise just print the item)      
				// Will be false each time the function is called again
				if ($has_children === false)
				{
					// Switch the flag, start the list wrapper, increase the level count
					$has_children = true;  
					if($level==0)
					{
						echo '<ul id="browser" class="filetree">
						';
					}
					else
					{
						echo "\n".'<ul>';
					}
					$level++;
				}
	      		// Print the list item
				echo "\n".'<li><span class="folder">' . $value['cat_name'] . '</span>';
				echo $this->get_articles($value['cat_id'], $value['cat_uri']);
				// Repeat function, using the current item's key (id) as the parent_id argument
				// Gives us a nested list of subcategories
				$this->generate_tree_list($array, $key, $level); 
				// Close the item
				echo "</li>\n";
	    	}
		}
		// If we opened the wrapper above, close it.
	  	if ($has_children === true) echo '</ul>'."\n";
	}

	function get_articles($id, $cat_uri = '')
	{
		$this->db->from('articles');
		$this->db->join('article2cat', 'articles.article_id = article2cat.article_id', 'left');
		$this->db->where('category_id', $id);
		$this->db->where('article_display', 'Y');
		$query = $this->db->get();
		$output = '<ul>';
		if ($query->num_rows() > 0)
		{
			foreach($query->result() as $rs)
			{
				$title = $rs->article_title;
				$uri = $rs->article_uri;
				$output .= '<li><span class="file"><a href="./'.$uri.'.html">'.$title.'</a></span></li>';
			}
		}
		else
		{
			//$output.='<li></li>';
		}
		$output.='</ul>';
		return $output;
	}


	function recurse_copy($src,$dst) 
	{
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) 
		{
			if (( $file != '.' ) && ( $file != '..' )) 
			{
				if ( is_dir($src . '/' . $file) ) 
				{
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else 
				{
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}
}

/* End of file utility.php */
/* Location: ./upload/includes/application/controllers/admin/utility.php */ 