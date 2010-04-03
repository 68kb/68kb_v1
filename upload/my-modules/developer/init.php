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
 * Developer Init file
 *
 * This is an example file used to add a "test" table, alter it, and finally
 * delete it. Please note the use of the $CI =& get_instance(); which is 
 * used to access the CodeIgniter object.
 *
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/developer/modules.html
 * @version 	$Id: init.php 98 2009-08-15 02:09:51Z suzkaw68 $
 */


// ------------------------------------------------------------------------

/**
 * Install or alter any database fields. This is an example and you can 
 * see the install function just adds a test table.
 */
function install()
{
	$CI =& get_instance();
	$CI->load->dbforge();
	$fields = array(
			'id' => array('type' => 'INT','constraint' => 20,'unsigned' => TRUE,'auto_increment' => TRUE),
	);
	$CI->dbforge->add_field($fields);
	$CI->dbforge->add_field("test varchar(20) default NULL");
	$CI->dbforge->add_key('id', TRUE);
	if($CI->dbforge->create_table('test'))
	{
		return 'test table installed...<br />';
	}
}

// ------------------------------------------------------------------------

/**
 * Upgrade is ran to make any adjustments to any tables.
 */
function upgrade()
{
	$CI =& get_instance();
	$CI->load->dbforge();
	
	if ( ! $CI->db->field_exists('preferences', 'test'))
	{
		$fields = array('preferences' => array('type' => 'TEXT'));
		$CI->dbforge->add_column('test', $fields);
		return 'test table altered';
	}
}

// ------------------------------------------------------------------------

/**
 * Uninstall is used for removing any changes your module makes to the db.
 */
function uninstall()
{
	$CI =& get_instance();
	$CI->load->dbforge();
	$CI->dbforge->drop_table('test');
	return 'test table dropped';
}