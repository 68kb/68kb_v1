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
 * Install Model
 *
 * This class is used for installing your db.
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: install_model.php 141 2009-12-04 13:16:36Z suzkaw68 $
 */
class Install_model extends Model
{	
	function __construct()
	{
		parent::Model();
		$this->obj =& get_instance();
		$this->load->dbforge();
	}
	
	// ------------------------------------------------------------------------

	/**
	* Install Tables
	*
	* Loops through this class methods that begines with "table_" and runs them.
	*
	*/
	function install($username='demo', $password='demo', $email='demo@example.com', $drop=FALSE)
	{
		$sample_data = TRUE;
		
		// make email global for use in settings
		define('KB_EMAIL', $email);
		
		$msg = '';

		$class_methods = get_class_methods($this);

		foreach ($class_methods as $method_name)
		{
			if(substr($method_name, 0, 6) == 'table_')
			{
				if($method_name=='table_users')
				{
					$msg[] = $this->$method_name($username, $password, $email, $drop);
				}
				else
				{
					$msg[] = $this->$method_name($drop);
				}
			}
		}
		if($sample_data == TRUE)
		{
			$this->default_data();
		}
		return $msg;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Articles Table
	*/
	function table_articlestocat($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('article2cat');
		}

		$this->dbforge->add_field("article_id int(20) default NULL");
		$this->dbforge->add_field("category_id int(20) default NULL");

		$this->dbforge->add_key('article_id', TRUE);
		$this->dbforge->add_key('category_id', TRUE);
		if($this->dbforge->create_table('article2cat'))
		{
			return 'article2cat table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Articles Table
	*/
	function table_articles($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('articles');
		}
		$fields = array(
				'article_id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("article_uri varchar(55) NOT NULL default '0'");
		$this->dbforge->add_field("article_title varchar(255) NOT NULL default ''");
		$this->dbforge->add_field("article_keywords varchar(255) NOT NULL default ''");
		$this->dbforge->add_field("article_description text NOT NULL");
		$this->dbforge->add_field("article_short_desc text NOT NULL");
		$this->dbforge->add_field("article_date int(11) NOT NULL default '0'");
		$this->dbforge->add_field("article_modified int(11) NOT NULL default '0'");
		$this->dbforge->add_field("article_display char(1) NOT NULL default 'N'");
		$this->dbforge->add_field("article_hits int(11) NOT NULL default '0'");
		$this->dbforge->add_field("article_author int(11) NOT NULL default '0'");
		$this->dbforge->add_field("article_order int(11) NOT NULL default '0'");
		$this->dbforge->add_field("article_rating int(11) NOT NULL default '0'");
		$this->dbforge->add_key('article_id', TRUE);
		$this->dbforge->add_key('article_uri', TRUE);
		$this->dbforge->add_key('article_title', TRUE);
		if($this->dbforge->create_table('articles'))
		{
			return 'articles table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Articles Tags Table
	*/
	function table_article_tags($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('article_tags');
		}
		$this->dbforge->add_field("tags_tag_id int(11) NOT NULL default '0'");
		$this->dbforge->add_field("tags_article_id int(11) NOT NULL default '0'");
		$this->dbforge->add_key('tags_tag_id', TRUE);
		if($this->dbforge->create_table('article_tags'))
		{
			return 'article_tags table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Attachments Table
	*/
	function table_attachments($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('attachments');
		}
		$fields = array(
				'attach_id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("article_id int(11) NOT NULL default '0'");
		$this->dbforge->add_field("attach_name varchar(55) NOT NULL default ''");
		$this->dbforge->add_field("attach_type varchar(55) NOT NULL default ''");
		$this->dbforge->add_field("attach_size varchar(55) NOT NULL default ''");
		$this->dbforge->add_key('attach_id', TRUE);
		if($this->dbforge->create_table('attachments'))
		{
			return 'attachments table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Captcha Table
	*/
	function table_captcha($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('captcha');
		}
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
			return 'captcha table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Categories
	*/
	function table_categories($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('categories');
		}
		$fields = array(
				'cat_id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("cat_parent int(11) NOT NULL default '0'");
		$this->dbforge->add_field("cat_uri varchar(55) NOT NULL default '0'");
		$this->dbforge->add_field("cat_name varchar(255) NOT NULL default ''");
		$this->dbforge->add_field("cat_description text NOT NULL");
		$this->dbforge->add_field("cat_display char(1) NOT NULL DEFAULT 'N'");
		$this->dbforge->add_field("cat_order int(11) NOT NULL default '0'");
		$this->dbforge->add_key('cat_id', TRUE);
		$this->dbforge->add_key('cat_uri', TRUE);
		$this->dbforge->add_key('cat_name');
		$this->dbforge->add_key('cat_parent');
		$this->dbforge->add_key('cat_order');
		if($this->dbforge->create_table('categories'))
		{
			return 'categories table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Comments Table
	*/
	function table_comments($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('comments');
		}
		$fields = array(
				'comment_ID' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("comment_article_ID int(11) NOT NULL default '0'");
		$this->dbforge->add_field("comment_author varchar(55) NOT NULL default ''");
		$this->dbforge->add_field("comment_author_email varchar(55) NOT NULL default ''");
		$this->dbforge->add_field("comment_author_IP varchar(16) NOT NULL default ''");
		$this->dbforge->add_field("comment_date int(16) NOT NULL default '0'");
		$this->dbforge->add_field("comment_content text NOT NULL");
		$this->dbforge->add_field("comment_approved enum('0','1','spam') NOT NULL default '1'");
		$this->dbforge->add_key('comment_ID', TRUE);
		$this->dbforge->add_key('comment_article_ID');
		if($this->dbforge->create_table('comments'))
		{
			return 'comments table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Glossary Table
	*/
	function table_glossary($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('glossary');
		}
		$fields = array(
				'g_id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("g_term varchar(55) NOT NULL default ''");
        $this->dbforge->add_field("g_definition text NOT NULL");
		$this->dbforge->add_key('g_id', TRUE);
		$this->dbforge->add_key('g_term');
		if($this->dbforge->create_table('glossary'))
		{
			return 'glossary table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Modules Table
	*/
	function table_modules($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('modules');
		}
		$fields = array(
				'id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("name varchar(255) NOT NULL default ''");
        $this->dbforge->add_field("displayname varchar(255) NOT NULL default ''");
        $this->dbforge->add_field("description varchar(255) NOT NULL default ''");
        $this->dbforge->add_field("directory varchar(255) NOT NULL default ''");
        $this->dbforge->add_field("version varchar(10) NOT NULL default ''");
        $this->dbforge->add_field("active tinyint(1) NOT NULL default '0'");
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('name', TRUE);
		$this->dbforge->add_key('displayname');
		$this->dbforge->add_key('active');
		
		if($this->dbforge->create_table('modules'))
		{
			return 'modules table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Search Log Table
	*/
	function table_searchlog($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('searchlog');
		}
		$fields = array(
				'searchlog_id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("searchlog_term varchar(55) NOT NULL default ''");
        $this->dbforge->add_key('searchlog_id', TRUE);
        $this->dbforge->add_key('searchlog_term');
		if($this->dbforge->create_table('searchlog'))
		{
			return 'searchlog table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Sessions Table
	*/
	function table_sessions($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('sessions');
		}
		$fields = array(
				'session_id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("ip_address varchar(16) NOT NULL default '0'");
		$this->dbforge->add_field("user_agent varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("last_activity int(10) NOT NULL default '0'");
        $this->dbforge->add_key('session_id', TRUE);
		if($this->dbforge->create_table('sessions'))
		{
			return 'sessions table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Settings table
	*/
	function table_settings($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('settings');
		}
		$fields = array(
				'option_id' => array( 'type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE ),
                'short_name' => array( 'type' => 'VARCHAR', 'constraint' => '55' ),
				'name' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
				'value' => array( 'type' => 'VARCHAR', 'constraint' => '255' ),
				'auto_load' => array( 'type' => 'VARCHAR', 'constraint' => '16' )
		);

		$this->dbforge->add_field($fields);
		
		$this->dbforge->add_key('option_id', TRUE);
		$this->dbforge->add_key('short_name', TRUE);
		$this->dbforge->add_key('value');
		$this->dbforge->add_key('auto_load');
		
		$this->dbforge->create_table('settings');

		$data = array('short_name' => 'site_name','name' => "Site Title",'value' => 'Your Site','auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'site_email','name' => "Site Email",'value' => KB_EMAIL,'auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'version','name' => "Script Version",'value' => KB_VERSION,'auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'last_cron','name' => "Last Cron",'value' => "",'auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'template','name' => "Template",'value' => 'emporium','auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'admin_template','name' => "Admin Template",'value' => 'default','auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'max_search','name' => "Per Page",'value' => '5','auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'cache_time','name' => "Cache Time",'value' => '0','auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'comments','name' => "Allow Comments",'value' => 'Y','auto_load' => 'yes');
		$this->db->insert('settings', $data);		
		$data = array('short_name' => 'latest','name' => "Latest 68KB Release",'value' => '0','auto_load' => 'no');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'site_keywords','name' => "Site Keywords",'value' => 'keywords, go, here','auto_load' => 'yes');
		$this->db->insert('settings', $data);
		$data = array('short_name' => 'site_description','name' => "Site Description",'value' => 'Site Description','auto_load' => 'yes');
		$this->db->insert('settings', $data);

		return 'settings table installed...<br />';
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Tags Table
	*/
	function table_tags($drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('tags');
		}
		$fields = array(
				'id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("tag varchar(30) NOT NULL default '0'");
        $this->dbforge->add_key('id', TRUE);
		if($this->dbforge->create_table('tags'))
		{
			return 'tags table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Install Users Table
	*/
	function table_users($username, $password, $email, $drop)
	{
		if($drop)
		{
			$this->dbforge->drop_table('users');
		}
		$fields = array(
				'id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE),
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("custip varchar(16) NOT NULL default '0'");
		$this->dbforge->add_field("firstname varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("lastname varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("email varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("username varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("password varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("joindate int(11) NOT NULL default '0'");
		$this->dbforge->add_field("lastlogin int(11) NOT NULL default '0'");
		$this->dbforge->add_field("cookie varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("session varchar(50) NOT NULL default ''");
		$this->dbforge->add_field("level int(5) NOT NULL default '5'");
        $this->dbforge->add_key('id', TRUE);
		if($this->dbforge->create_table('users'))
		{
			$data = array(
				    'firstname' => 'admin',
				    'lastname' => 'acount',
				    'email' => $email,
				    'username' => $username,
				    'password' => md5($password),
				    'joindate' => time(),
					'level'	=> 1
				);
			$this->db->insert('users', $data);
			return 'users table installed...<br />';
		}
	}
	
	// ------------------------------------------------------------------------
	
	function default_data()
	{
		$data = array(
				'name' => 'jwysiwyg',
				'displayname' => 'WYSIWYG jQuery Plugin',
				'description' => 'This module is an inline content editor to allow editing rich HTML content on the fly.',
				'directory' => 'jwysiwyg',
				'version' => 'v1.0',
				'active' => '1'
			);
		$this->db->insert('modules', $data);
		$data = array(
					'article_author' => 1,
					'article_title' => "Welcome to 68kb",
					'article_uri' => "welcome",
					'article_keywords' => 'cat',
					'article_short_desc' => 'Short Description',
					'article_description' => 'Welcome to 68kb!<div><br></div><div>Thank you for downloading and installing 68kb. I know as with any script it probably does things differently than others and at this time we would like to highlight some of the available resources available to you.&nbsp;</div><div><br></div><div>1.&nbsp;<a href="http://68kb.com/knowledge-base/">Knowledge Base</a>&nbsp;- Our knowledge base includes information about using and working with the script.&nbsp;</div><div>2. <a href="http://68kb.com/support/">Support Forums</a> - We have community support forums where you can get help, advice, or talk with other 68kb users.&nbsp;</div><div>3. <a href="http://68kb.com/blog/">68kb Blog</a> - Our blog covers new releases and other tips and tricks to get you comfortable with 68kb.&nbsp;</div><div><br></div><div>Thanks again!</div>',
					'article_display' => 'Y',
					'article_date' => time(),
					'article_modified ' => time()
				);
		$this->db->insert('articles', $data);
		$data = array(
					'article_id' => 1,
					'category_id' => 1,
				);
		$this->db->insert('article2cat', $data);
		$data = array(
					'cat_parent' => 0,
					'cat_name' => "Example Category",
					'cat_uri' => "example",
					'cat_description' => 'This is an example category.',
					'cat_display' => 'Y'
				);
		$this->db->insert('categories', $data);
		$data = array(
					'g_term' => "68kb",
					'g_definition' => '68kb is a php knowledge base script. This is an example of the glossary function.'
				);
		$this->db->insert('glossary', $data);
	}
	
}


/* End of file db_model.php */
/* Location: ./upload/includes/application/models/db_model.php */