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
 * Category Model
 *
 * This class is used to handle the categories data.
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/categories.html
 * @version 	$Id: category_model.php 89 2009-08-13 01:54:20Z suzkaw68 $
 */
class Category_model extends model
{	
	/**
	 * Constructor
	 *
	 * @uses 	get_settings
	 * @return 	void
	 */
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Category Model Initialized');
	}
	
	// ------------------------------------------------------------------------
		
	/**
	* Delete Category
	* 
	* @param	int $cat_id The id of the category to delete.
	* @return	true on success.
	*/
	function delete_category($cat_id)
	{
		$cat_id=(int)trim($cat_id);
		$this->db->delete('categories', array('cat_id' => $cat_id)); 
		if ($this->db->affected_rows() > 0) 
		{
			$this->db->cache_delete_all();
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
 	* Edit Category
 	* 
 	* @param	array $data An array of data.
	* @uses 	format_uri
 	* @return	true on success.
 	*/
	function edit_category($cat_id, $data)
	{
		$cat_id = (int)$cat_id;
		
		if (isset($data['cat_uri']) && $data['cat_uri'] != '') 
		{
			$data['cat_uri'] = $this->format_uri($data['cat_uri'], 0, $cat_id);
		}
		else
		{
			$data['cat_uri'] = $this->format_uri($data['cat_name'], 0, $cat_id);
		}
		$this->db->where('cat_id', $cat_id);
		$this->db->update('categories', $data);
		
		if ($this->db->affected_rows() > 0) 
		{
			$this->db->cache_delete_all();
			return true;
		} 
		else
		{
			log_message('info', 'Could not edit the category id '. $cat_id);
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
 	* Add Category
 	* 
 	* @param	array 	$data An array of data.
	* @uses 	format_uri
 	* @return	mixed 	Id on success.
 	*/
	function add_category($data)
	{
		if (isset($data['cat_uri']) && $data['cat_uri'] != '') 
		{
			$data['cat_uri'] = $this->format_uri($data['cat_uri']);
		}
		else
		{
			$data['cat_uri'] = $this->format_uri($data['cat_name']);
		}
		$this->db->insert('categories', $data);
		if ($this->db->affected_rows() > 0) 
		{
			$this->db->cache_delete_all();
			return $this->db->insert_id();
		} 
		else 
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Insert Article2Cats
	* 
	* Insert the selected categories
	* into the article2cat table.
	*
	* @access	public
	* @param	int - The article id
	* @param	array - The array of cats.
	* @return 	bool
	*/
	function insert_cats($id, $arr)
	{
		$this->db->delete('article2cat', array('article_id' => $id));
		if (is_array($arr))
		{
			foreach($arr as $catObj)
			{
				$data = array('article_id' => $id, 'category_id' => $catObj);
				$this->db->insert('article2cat', $data);
			}
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Check URI
	* 
	* Checks other categories for the same uri.
	* 
	* @param	string 	$cat_uri The uri name
	* @return	boolean True if checks out ok, false otherwise
	*/
	function check_uri($cat_uri, $cat_id=false)
	{
		if ($cat_id !== false) 
		{
			$cat_id=(int)$cat_id;
			$this->db->select('cat_uri')->from('categories')->where('cat_uri', $cat_uri)->where('cat_id !=', $cat_id);
		} 
		else 
		{
			$this->db->select('cat_uri')->from('categories')->where('cat_uri', $cat_uri);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) 
		{
			return false;
		} 
		else 
		{
			return true;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Format URI
	* 
	* Formats a category uri.
	* 
	* @param	string $cat_uri The uri name
	* @uses 	check_uri
	* @uses		remove_accents
	* @uses		seems_utf8
	* @uses		utf8_uri_encode
	* @uses		format_uri
	* @return	string A cleaned uri
	*/
	function format_uri($cat_uri, $i=0, $cat_id=false)
	{
		$cat_uri = strip_tags($cat_uri);
		// Preserve escaped octets.
		$cat_uri = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $cat_uri);
		// Remove percent signs that are not part of an octet.
		$cat_uri = str_replace('%', '', $cat_uri);
		// Restore octets.
		$cat_uri = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $cat_uri);
		
		$cat_uri = remove_accents($cat_uri);
		if (seems_utf8($cat_uri)) 
		{
			if (function_exists('mb_strtolower')) 
			{
				$cat_uri = mb_strtolower($cat_uri, 'UTF-8');
			}
			$cat_uri = utf8_uri_encode($cat_uri, 200);
		}

		$cat_uri = strtolower($cat_uri);
		$cat_uri = preg_replace('/&.+?;/', '', $cat_uri); // kill entities
		$cat_uri = preg_replace('/[^%a-z0-9 _-]/', '', $cat_uri);
		$cat_uri = preg_replace('/\s+/', '-', $cat_uri);
		$cat_uri = preg_replace('|-+|', '-', $cat_uri);
		$cat_uri = trim($cat_uri, '-');
		
		if ($i>0) 
		{
			$cat_uri=$cat_uri."-".$i;
		}
		
		if (!$this->check_uri($cat_uri, $cat_id)) 
		{
			$i++;
			$cat_uri=$this->format_uri($cat_uri, $i);
		}
		return $cat_uri;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get an array of categories.
	 *
	 * Get an array of categories and for use
	 * in a select list.
	 *
	 * @access	public
	 * @param	string	the prefix to indent nested cats with.
	 * @param	int	the parent id
	 * @param	bool Inside the admin
	 * @return	array
	 */
	function get_cats_for_select($prefix='', $parent=0, $article_id='', $admin=FALSE)
	{
		$arr = array();
		$this->db->select('cat_id,cat_uri,cat_name,cat_description')->from('categories')->orderby('cat_order', 'DESC')->orderby('cat_name', 'asc')->where('cat_parent', $parent); 
		if ($admin==FALSE)
		{
			$this->db->where('cat_display', 'Y');	
		}
		$query = $this->db->get();
		//echo $this->db->last_query();
		foreach ($query->result() as $row)
		{
			$rs['cat_name']=$prefix . $row->cat_name;
			$rs['cat_id']=$row->cat_id;
			$rs['cat_uri']=$row->cat_uri;
			$rs['cat_description']=$row->cat_description;
			$id=$row->cat_id;
			if ($article_id <> '')
			{
				$this->db->from('article2cat')->where('article_id', $article_id)->where('category_id', $row->cat_id);
				$art2cat = $this->db->get();
				if ($art2cat->num_rows() > 0)
				{
					$rs['selected'] = 'Y';
				}
				else
				{
					$rs['selected'] = 'N';
				}
			}
			else
			{
				$rs['selected'] = 'N';
			}
			array_push($arr, $rs);
			$arr = array_merge($arr, $this->get_cats_for_select($prefix .'&nbsp;&nbsp;&raquo;&nbsp;', $id, $article_id,$admin));
		}
		return $arr;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Categories By Parent.
	 *
	 * Get an array of categories that have the
	 * same parent.
	 *
	 * @access	public
	 * @param	int	the parent id
	 * @return	array
	 */
	function get_categories_by_parent($parent)
	{
		$arr = array();
		$this->db->from('categories')->orderby('cat_order', 'DESC')->orderby('cat_name', 'asc')->where('cat_parent', $parent)->where('cat_display', 'Y');
		$query = $this->db->get();
		return $query;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Category By URI.
	 *
	 * Get a single category from its cat_uri
	 *
	 * @access	public
	 * @param	string	the unique uri
	 * @return	array
	 */
	function get_cat_by_uri($uri)
	{
		$this->db->from('categories')->where('cat_uri', $uri)->where('cat_display', 'Y');
		$query = $this->db->get();
		$data = $query->row();
		$query->free_result();
		return  $data;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Category By ID.
	 *
	 * Get a single category from its id
	 *
	 * @access	public
	 * @param	int	the unique id
	 * @return	array
	 */
	function get_cat_by_id($id)
	{
		$id=(int)$id;
		$this->db->from('categories')->where('cat_id', $id);
		$query = $this->db->get();
		$data = $query->row();
		$query->free_result();
		return  $data;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Category Name By ID.
	 *
	 * Get a single category Name
	 *
	 * @access	public
	 * @param	int	the unique id
	 * @return	string
	 */
	function get_cat_name_by_id($id)
	{
		$this->db->select('cat_name')->from('categories')->where('cat_id', $id);
		$query = $this->db->get();
		$data = $query->row();
		$query->free_result();
		return  $data->cat_name;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Category By Article.
	 *
	 * Get a list of categories an article is associated with.
	 *
	 * @access	public
	 * @param	int	the unique id
	 * @return	array
	 */
	function get_cats_by_article($id)
	{
		$this->db->select('*');
		$this->db->from('article2cat');
		$this->db->join('categories', 'article2cat.category_id = categories.cat_id', 'left');
		$this->db->where('article_id', $id);
		$this->db->where('cat_display', 'Y');
		$query = $this->db->get();
		return $query;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Category Tree.
	 *
	 * Get a recursive list of categories.
	 *
	 * @access	public
	 * @param	string Orderby
	 * @param	string Order ASC or DESC
	 * @param	int	The parent to start at
	 * @return	array
	 */
	function get_tree($orderby='cat_name', $order='ASC', $parent=0)
	{
		$cat = array();
		$this->db->from('categories')->orderby('cat_order', 'DESC')->orderby($orderby, $order)->where('cat_parent', $parent)->where('cat_display', 'Y');
		$query = $this->db->get();
		foreach ($query->result() as $row)
		{
			$rs['cat_id']=$row->cat_id;
			$rs['cat_name']=$row->cat_name;
			$rs['cat_parent']=$row->cat_parent;
			$rs['cat_url']=$row->cat_uri;
			$rs['cat_total'] = $this->get_category_count($row->cat_id);
			$rs['cat_link'] = site_url("category/".$row->cat_uri."/");
			$cat[]=$rs;
		}
		return $cat;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get Category Tree.
	 *
	 * Get a recursive list of categories.
	 *
	 * @access	public
	 * @param	string Orderby
	 * @param	string Order ASC or DESC
	 * @param	int	The parent to start at
	 * @return	array
	 */
	function get_category_count($cat=0)
	{
		$this->db->from('articles');
		$this->db->join('article2cat', 'articles.article_id = article2cat.article_id', 'left');
		$this->db->where('category_id', $cat);
		$this->db->where('article_display', 'Y');
		return $this->db->count_all_results();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get Sub Categories
	* 
	* @param	int		parent id
	* @return	mixed
	*/
	function get_sub_categories($parent)
	{
		$parent = (int)$parent;
		$this->db->select('cat_id,cat_uri,cat_name,cat_parent')->from('categories')->where('cat_parent', $parent)->where('cat_display !=', 'N')->order_by('cat_order DESC, cat_name ASC');
		$query = $this->db->get();
		if ($query->num_rows() > 0) 
		{
			$cat = $query->result_array();
			$query->free_result();
			return $cat;
		} 
		else 
		{
			return false;
		}
	}
}
	
/* End of file category_model.php */
/* Location: ./upload/includes/application/models/category_model.php */ 
