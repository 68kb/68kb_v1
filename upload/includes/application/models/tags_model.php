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
 * Tags Model
 *
 * This class is used to allow the admin to add new tags to articles.
 * http://codeigniter.com/forums/viewthread/73046/
 * http://codeigniter.com/forums/viewthread/49356/#239045
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: tags_model.php 89 2009-08-13 01:54:20Z suzkaw68 $
 */
class Tags_model extends Model {

	/**
	* Constructor
	*
	* @return 	void
	**/
	public function __construct()
	{
		parent::__construct();
		log_message('debug', 'Tags Model Initialized');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get tags for article
	* 
	* @return 	array
	*/
	function get_article_tags($id)
	{
		$id = (int)$id;
		$this->db->select('tag');
		$this->db->join('tags', 'id = tags_tag_id', 'inner');
		$this->db->where('tags_article_id', $id);
		$query = $this->db->get('article_tags');
		$tags = '';
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$tags .= $row->tag.', ';
			}
			$tags = rtrim($tags, ', ');
		}
		return $tags;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get tag cloud
	* 
	* @return 	array
	*/
	function tag_cloud($num='')
	{
		$this->db->select('tag, COUNT(tags_tag_id) as qty');
		$this->db->join('article_tags', 'tags_tag_id = id', 'inner');
		$this->db->groupby('id');

		$query = $this->db->get('tags');

		$built = array();

		if ($query->num_rows > 0)
		{
			$result = $query->result_array();
			foreach ($result as $row)
			{
				$built[$row['tag']] = $row['qty'];
			}
			return $built;
		}
		else
		{
			return array();
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get all tags
	* 
	* @return 	array
	*/
	function get_all_tags()
	{
		$this->db->select('tag')->from('tags')->order_by('tag', 'DESC');
		$query = $this->db->get();
		$tags = '';
		if ($query->num_rows > 0)
		{
			$result = $query->result_array();
			foreach ($result as $row)
			{
				$tags .= $row['tag'].' ';
			}
			$tags = rtrim($tags, ' ');
			return $tags;
		}
		else
		{
			return array();
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Insert Tags
	* 
	* @param 	int
	* @param	string
	*/
	function insert_tags($article_id, $tags)
	{
		$article_id = (int)$article_id;
		//first delete any article tags.
		$this->db->delete('article_tags', array('tags_article_id' => $article_id)); 
		$tags = str_replace(', ', ',', $tags);
		$tags = str_replace('_', ' ', $tags);
		$tags = explode(',', $tags);
		
		foreach ($tags as $tag) 
		{
			$tag = trim($tag);
			if ( ! empty($tag))
			{
				$this->db->where('tag', $tag);
				$query = $this->db->get('tags',1);

				if ( $query->num_rows() == 1 )
				{
					$row = $query->row_array();
					$tag_id = $row['id'];
				}
				else
				{
					$tag_data = array('tag' => $tag);
					$this->db->insert('tags', $tag_data);
					$tag_id = $this->db->insert_id();
				}
				$tag_assoc_data =    array(
					'tags_tag_id' => $tag_id,
					'tags_article_id' => $article_id
					);

				$this->db->insert('article_tags', $tag_assoc_data);
			}
		}
	}
}

/* End of file tags_model.php */
/* Location: ./upload/includes/application/models/tags_model.php */ 