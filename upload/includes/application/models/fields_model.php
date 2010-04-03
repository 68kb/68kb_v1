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
 * Fields Model
 *
 * This class is used to allow the admin to add new fields to tables.
 * Currently not in use.
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: fields_model.php 57 2009-07-30 02:52:19Z suzkaw68 $
 */
class Fields_model extends Model {

	/**
	* Constructor
	*
	* @return 	void
	**/
	public function __construct()
	{
		parent::__construct();
		log_message('debug', 'fields_model Initialized');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get the listing field names
	* 
	* @return 	array
	*/
	function get_field_names()
	{
		return $this->db->list_fields('articles');
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get a single listing extra fields
	* 
	* @param 	int		The listing id
	* @return 	mixed	Array on success
	*/
	function get_fields()
	{
		$this->db->from('fields');
		$query = $this->db->get();
		if ($query->num_rows() > 0) 
		{
			return  $query;
		}
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Add a listing field
	* 
	* @uses 	add_column
	* @param 	array 	Array of field data
	* @return 	bool
	*/
	function add_field($data)
	{
		if ( ! $this->db->field_exists($data['field_name'], 'articles'))
		{
			$this->db->insert('fields', $data);
			if($this->db->affected_rows() > 0) 
			{
				$id = $this->db->insert_id();
				if($this->add_column($data['field_name'], $data['field_type'], 'articles', $data['field_size']))
				{
					return true;
				}
				return false;
			}
			return false;
		}
		return false;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Add a column
	* 
	* @param 	sting	The name of the column
	* @param	string 	The type of column. See http://dev.mysql.com/doc/refman/5.0/en/data-types.html
	* @param	string 	The table to add it to.
	* @param	int 	The max limit on the field
	* @param	string 	The default column value
	* @return 	bool
	*/
	function add_column($name, $type, $table='articles', $constrain=100, $default='')
	{
		$this->load->dbforge();
		$field = array(
						$name => array(
						'type' => $type,
						'constraint' => $constrain,
						'default' => $default,
						),
					);
		if($this->dbforge->add_column($table, $field))
		{
			return true;
		}
		return false;
	}
}

/* End of file fields_model.php */
/* Location: ./upload/includes/application/models/fields_model.php */ 