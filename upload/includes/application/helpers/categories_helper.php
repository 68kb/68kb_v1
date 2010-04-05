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
 * Categories Plugin
 *
 * This is used to display a list of categories.
 *
 * @subpackage	Plugins
 * @link		http://68kb.com/user_guide/developer/plugin-categories.html
 */
if ( ! function_exists('categories_plugin'))
{
	function categories_plugin($params)
	{
		$CI =& get_instance();
	
		$parent = 0; // parent to start with
		$depth = 1; // category depth
		$type = 'list'; // list or table
	
		$table_attr = 'width="100%"';
		$style = 1;
		$cols=2;
		$show_count = FALSE;
		$show_description = FALSE;
		$row_start = '';
		$cell_start = '';
		$row_alt_start = '';
		$cell_alt_start = '';
		$trail_pad = '&nbsp;';
	
		parse_str($params, $options);
	
		foreach ($options as $_key=>$_value) 
		{
			switch ($_key) 
			{
				case 'parent':
				case 'depth':
					$$_key = (int)$_value;
					break;
				case 'type':
					$$_key = (string)$_value;
					break;
				case 'table_attr':
				case 'tr_attr':
				case 'td_attr':
				case 'show_count':
				case 'show_description':
					$$_key = $_value;
					break;
			}
		}
	
		$CI->load->model('category_model');
	
		if ($type == 'table') 
		{
			// setup table template
			$tmpl = array (
				'table_open'          => '<table '. $table_attr .'>',

				'row_start'           => '<tr '. $row_start .'>',
				'row_end'             => '</tr>',
				'cell_start'          => '<td '. $cell_start .'>',
				'cell_end'            => '</td>',

				'row_alt_start'       => '<tr '. $row_alt_start .'>',
				'row_alt_end'         => '</tr>',
				'cell_alt_start'      => '<td '. $cell_alt_start .'>',
				'cell_alt_end'        => '</td>',

				'table_close'         => '</table>'
			);
		
			$data = array();
			$cats = $CI->category_model->get_categories_by_parent($parent);
		
			if ($cats->num_rows() == 0) // no records so we can't continue
			{
				return FALSE;
			}
		
			foreach ($cats->result_array() as $row)
			{
				$count = '0';
				if ($show_count)
				{
					$count = '<span>('.$CI->category_model->get_category_count($row['cat_id']).')</span>';
				}
			
				$td = '<h4 class="cat_name '.$row['cat_uri'].'"><a href="'.site_url('category/'.$row['cat_uri']).'">'.$row['cat_name'].'</a> '.$count.'</h4>';
			
				if ($show_description)
				{
					$td .= '<p>'.$row['cat_description'].'</p>';
				}
				// any sub cats? Only goes to a depth of 2.
				if ($depth > 1)
				{
					$children = $CI->category_model->get_categories_by_parent($row['cat_id']);
					if ($children->num_rows() > 0)
					{
						$td .= '<ul>';
						foreach ($children->result_array() as $child)
						{
							$td .= '<li><a href="'.site_url('category/'.$child['cat_uri']).'">'.$child['cat_name'].'</a></li>';
						}
						$td .= '</ul>';
					}
				}
				// Make it into an array
				$td_arr = array($td);
			
				// Now merge this with others
				$data = array_merge($data, $td_arr);
			}
			// Load the table library
			$CI->load->library('table');

			// Set the template as defined above.
			$CI->table->set_template($tmpl);
			$CI->table->set_empty($trail_pad); 

			// Make the columns
			$new_list = $CI->table->make_columns($data, $cols);

			// echo it to the browser
			echo $CI->table->generate($new_list);

			// finally clear the template incase it is used twice.
			$CI->table->clear();
		}
		else
		{
			// pass off to categories model to walk through it.
			return $CI->category_model->walk_categories($parent, $depth, $type);
		}
	}
}
/* End of file categories_helper.php */
/* Location: ./upload/includes/application/helpers/categories_helper.php */ 