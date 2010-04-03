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
 * Upgrade Model
 *
 * This class is used for upgrading your db.
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: upgrade_model.php 130 2009-12-01 18:04:30Z suzkaw68 $
 */
class Upgrade_model extends model
{	
	function __construct()
	{
		parent::Model();
		$this->obj =& get_instance();
		$this->load->dbforge();
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Upgrade the db.
	 *
	 * @access	private
	 */
	function upgrade()
	{
		$prefix = $this->db->dbprefix;
		$log = '';
		
		//update version
		$this->db->select('short_name,value')->from('settings')->where('short_name', 'version');
		$query = $this->db->get();
		foreach ($query->result() as $k=>$row)
		{
			$version = $row->value;
		}
		
		$log = $this->do_upgrade();
		
		$data['value']=KB_VERSION;
		$this->db->where('short_name', 'version');
		$this->db->update('settings', $data);
		
		//optimize db
		$this->load->dbutil();
		$this->dbutil->optimize_database();
		
		//delete cache
		$this->load->helper('file');
		delete_files($this->config->item('cache_path'));
		
		return $log;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Upgrade
	 *
	 * @access	public
	 */
	function do_upgrade()
	{
		$log[] = $this->upgrade_to_one();
		return $log;
	}
	
	// ------------------------------------------------------------------------

	/**
	* Upgrade to v1.0
	*/
	function upgrade_to_one()
	{
		$prefix = $this->db->dbprefix;
		
		$log[] = 'Checking for levels...<br>';
		if ( ! $this->db->field_exists('level', 'users'))
		{
			$sql = 'ALTER TABLE `'.$prefix.'users` ADD `level` INT( 5 ) NULL DEFAULT \'5\';';
			$this->db->query($sql);
			$log[] = 'Level added...<br />';
			$sql = 'UPDATE '.$prefix.'users SET level = 1';
			$this->db->query($sql);
		}
		
		$log[] = 'Checking for tags...<br>';
		if ( ! $this->db->table_exists('tags'))
		{
			$fields = array(
					'id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_field("tag varchar(30) NOT NULL default '0'");
	        $this->dbforge->add_key('id', TRUE);
			if($this->dbforge->create_table('tags'))
			{
				$log[] = 'tags table installed...';
			}
			$this->dbforge->add_field("tags_tag_id int(11) NOT NULL default '0'");
			$this->dbforge->add_field("tags_article_id int(11) NOT NULL default '0'");
			$this->dbforge->add_key('tags_tag_id', TRUE);
			if($this->dbforge->create_table('article_tags'))
			{
				$log[] = 'article_tags table installed...<br />';
			}
			$this->db->where('active', 0);
			$this->db->delete('modules');
		}
		
		$log[] = 'Checking for rating...<br>';
		if ( ! $this->db->field_exists('article_rating', 'articles'))
		{
			$sql = 'ALTER TABLE `'.$prefix.'articles` ADD `article_rating` INT( 11 ) NULL DEFAULT \'0\';';
			$this->db->query($sql);
			$log[] = 'Rating added...<br />';
		}
		
		$log[] = 'Checking for new settings...<br>';
		$this->db->select('short_name')->from('settings')->where('short_name', 'site_keywords');
		$count = $this->db->count_all_results();
		if($count == 0)
		{
			$data = array('short_name' => 'site_keywords','name' => "Site Keywords",'value' => '','auto_load' => 'yes');
			$this->db->insert('settings', $data);
			$data = array('short_name' => 'site_description','name' => "Site Description",'value' => '','auto_load' => 'yes');
			$this->db->insert('settings', $data);
			$log[] = 'Updating settings...<br>';
		}
		
		$log[] = 'Checking for article changes...<br>';
		if($this->db->field_exists('a_id', 'articles'))
		{
			// now alter table
			$sql = "
				ALTER TABLE `".$prefix."articles` 
				CHANGE `a_id` `article_id` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT, 
				CHANGE `a_uri` `article_uri` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				CHANGE `a_title` `article_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				CHANGE `a_keywords` `article_keywords` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
				CHANGE `a_short_desc` `article_short_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, 
				CHANGE `a_description` `article_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				CHANGE `a_date` `article_date` INT( 11 ) NOT NULL DEFAULT '0', 
				CHANGE `a_modified` `article_modified` INT( 11 ) NOT NULL DEFAULT '0', 
				CHANGE `a_display` `article_display` CHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N', 
				CHANGE `a_hits` `article_hits` INT(11) NOT NULL DEFAULT '0', 
				CHANGE `a_author` `article_author` INT( 11 ) NOT NULL DEFAULT '1',
				CHANGE `a_order` `article_order` INT( 11 ) NOT NULL DEFAULT '0';
			";
			$this->db->query($sql);
			// change datetime
			$this->db->select('article_id, article_date, article_modified')->from('articles');
			$query = $this->db->get();
			foreach ($query->result() as $row)
			{
				$data = array( 
					'article_date' => time(),
					'article_modified' => time()
				);
				$this->db->where('article_id', $row->article_id);
				$this->db->update('articles', $data);
			}
			$log[] = 'Updating articles...<br>';
			
			// COMMENTS
			$sql = "
				 ALTER TABLE `".$prefix."comments` CHANGE `comment_date` `comment_date` INT( 16 ) NOT NULL DEFAULT '0';
			";
			// change datetime
			$this->db->select('comment_ID, comment_date')->from('comments');
			$query = $this->db->get();
			foreach ($query->result() as $row)
			{
				$data = array( 
					'comment_date' => time()
				);
				$this->db->where('comment_ID', $row->comment_ID);
				$this->db->update('articles', $data);
			}
			$this->db->query($sql);
			$log[] = 'Updating comments...<br>';
		}
		
		$log[] = 'Checking for category changes...<br>';
		if ($this->db->field_exists('c_id', 'categories'))
		{
			$sql = "
				ALTER TABLE `".$prefix."categories` 
					CHANGE `c_id` `cat_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
					CHANGE `c_parent` `cat_parent` INT( 11 ) NOT NULL DEFAULT '0',
					CHANGE `c_uri` `cat_uri` VARCHAR( 55 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
					CHANGE `c_name` `cat_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					CHANGE `c_description` `cat_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					CHANGE `c_display` `cat_display` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'N',
					CHANGE `c_order` `cat_order` INT( 11 ) NOT NULL DEFAULT '0';
			";
			$this->db->query($sql);
			
			$log[] = 'Updating categories...<br>';
		}
		
		$log[] = 'Checking for attachment changes...<br>';
		if ($this->db->field_exists('a_id', 'attachments'))
		{
			$sql = "
				ALTER TABLE `".$prefix."attachments` CHANGE `a_id` `attach_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
					CHANGE `a_name` `attach_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					CHANGE `a_type` `attach_type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					CHANGE `a_size` `attach_size` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
			";
			$this->db->query($sql);
			
			$log[] = 'Updating attachments...<br>';
		}
		
		//this is from way long time ago. Left for backwards compatability.
		$log[] = 'Checking for Captcha...<br>';
		if ( ! $this->db->table_exists('captcha'))
		{
			$fields = array(
					'captcha_id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
			);
			$this->dbforge->add_field($fields);
			$this->dbforge->add_field("captcha_time int(10) NOT NULL default '0'");
			$this->dbforge->add_field("ip_address varchar(16) NOT NULL default '0'");
			$this->dbforge->add_field("word varchar(20) NOT NULL default ''");
			$this->dbforge->add_field("a_size varchar(255) NOT NULL default ''");
			$this->dbforge->add_key('captcha_id', TRUE);
			$this->dbforge->add_key('word');
			if($this->dbforge->create_table('captcha'))
			{
				$log[] = 'Captcha Table Created...';
			}
		}
		
		return $log;
	}
	
}


/* End of file db_model.php */
/* Location: ./upload/includes/application/models/db_model.php */