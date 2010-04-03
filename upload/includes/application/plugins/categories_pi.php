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
 * List Categories Function
 * 
 * Instructions:
 *
 * Load the plugin using:
 * $this->load->plugin('categories');
 * Once loaded you can call the list_categories function:
 * <code><?php echo list_categories();  ?></code>
 *
 * @param int 		The parent category. Defaults to 0.
 * @param int 		Do you want to show the count? 1 = Yes 0 = No. Defaults to 1.
 * @param int 		Do you want to hide empty categories? Defaults to 0.
 * @param string 	Order by which column. Defaults to cat_name
 * @param string	Order the results ASC or DESC. Defaults to ASC
*/
function list_categories($parent='0', $show_count='1', $hide_empty='0', $orderby='cat_name', $order='ASC')
{	
	$CI =& get_instance();
	$CI->load->model('category_model');
	$cats = $CI->category_model->get_tree($orderby, $order, $parent);
	$output = '<ul>';
	foreach($cats AS $k=>$row)
	{
		$continue = true;
		if($hide_empty == 1 && $row['cat_total'] > 0)
		{
			$output .= '<li><a href="'.$row['cat_link'].'" class="cat '.$row['cat_url'].'">'.$row['cat_name'].'</a>';
		}
		elseif($hide_empty == 0)
		{
			$output .= '<li><a href="'.$row['cat_link'].'" class="cat '.$row['cat_url'].'">'.$row['cat_name'].'</a>';
		}
		else
		{
			$continue = false;
		}
		if($continue)
		{
			if($show_count == 1)
			{
				$output .= ' <span class="cat_count">('.$row['cat_total'].')</span>';
			}
			$output .= '</li>';
		}
	}
	$output .= '</ul>';
	return $output;
}