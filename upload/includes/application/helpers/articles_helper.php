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
 * Articles Helper
 *
 * This is used to display a list of articles.
 *
 * @subpackage	Plugins
 * @link		http://68kb.com/user_guide/developer/plugin-articles.html
 */
if ( ! function_exists('articles_plugin'))
{
	function articles_plugin($params = '')
	{	
		$CI =& get_instance();

		// Default Options
		$limit = 10;
		$author = '';
		$category = '';
		$keywords = '';
		$sort = 'article_rating'; 
		$order_by = 'desc'; // random, asc, desc
		$return = FALSE;

		// Parse what they are requesting
		parse_str($params, $options);

		// Validate the formatting
		foreach ($options as $_key => $_value) 
		{
			switch ($_key) 
			{
				case 'limit':
				case 'author':
					$$_key = (int) $_value;
					break;
				case 'featured':
				case 'order_by':
				case 'sort':
				case 'keywords':
					$$_key = (string) $_value;
					break;
				case 'category':
				case 'return':
					$$_key = $_value;
					break;
			}
		}

		// Do the articles query
		$CI->db->from('articles')->where('article_display', 'Y');

		if ($category !== '')
		{
			$CI->db->join('article2cat', 'articles.article_id = article2cat.article_id', 'left');
		}

		if ($author !== '')
		{
			$CI->db->where('article_author', $author);
		}
		if ($keywords !== '')
		{
			$CI->db->like('article_keywords', $keywords); 
		}
		if ($category !== '' && ctype_digit($category)) // Single category
		{
			$CI->db->where('category_id', $category);
		}
		elseif ($category !== '') // Passing multiple categories
		{
			$CI->db->where_in('category_id', $category);
		}

		$CI->db->order_by($sort, $order_by); 

		$CI->db->limit($limit);

		$query = $CI->db->get();

		if ($query->num_rows() == 0) // no records so we can't continue
		{
			return FALSE;
		}

		// parse the list
		$output = '<ul class="articles">';
		foreach ($query->result() as $row)
		{
			$output .= '<li><a href="'. site_url("article/".$row->article_uri."/").'">'.$row->article_title.'</a></li>';
		}
		$output .= '</ul>';

		// send it off
		if ($return)
		{
			return $output;
		}
		else
		{
			echo $output;
		}
	}
}
/* End of file articles_helper.php */
/* Location: ./upload/includes/application/helpers/articles_helper.php */ 