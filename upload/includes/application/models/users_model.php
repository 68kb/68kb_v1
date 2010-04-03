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
 * Users Model
 *
 * Handles users
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/overview/users.html
 * @version 	$Id: users_model.php 89 2009-08-13 01:54:20Z suzkaw68 $
 */
class Users_model extends model
{	
	function __construct()
	{
		parent::__construct();
		$this->obj =& get_instance();
	}
	
	// ------------------------------------------------------------------------
	
	/**
 	* Edit User
 	* 
 	* @param	array $data An array of data.
	* @uses 	format_uri
 	* @return	true on success.
 	*/
	function edit_user($user_id, $data)
	{
		if (isset($data['password']) && $data['password'] != '')
		{
			$data['password'] = md5($data['password']);
		}
		$user_id = (int)$user_id;
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);
		
		if($this->db->affected_rows() > 0) 
		{
			$this->db->cache_delete_all();
			return true;
		} 
		else
		{
			log_message('info', 'Could not edit the user id '. $user_id);
			return false;
		}
	}
	
	/**
	 * Get User By ID.
	 *
	 * Get a single user by their ID
	 *
	 * @access	public
	 * @param	int the id
	 * @return	array
	 */
	function get_user_by_id($id)
	{
		$this->db->from('users')->where('id', $id);
		$query = $this->db->get();
		$data = $query->row();
		//echo $this->db->last_query();
		$query->free_result();
		return	$data;
	}
	
	/**
	 * Add User
	 *
	 * Get all the settings from the db.
	 *
	 * @access	public
	 * 
	 * @return	array
	 */
	function get_users()
	{
		$this->db->from('users');
		$query = $this->db->get();
		return	$query;
	}	
}

/* End of file users_model.php */
/* Location: ./upload/includes/application/models/users_model.php */ 