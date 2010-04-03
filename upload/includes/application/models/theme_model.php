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
 * Theme Model
 *
 * This class is used for getting themes.
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/design/themes.html
 * @version 	$Id: theme_model.php 89 2009-08-13 01:54:20Z suzkaw68 $
 */
class Theme_model extends Model {

	/**
	 * Constructor
	 *
	 * @uses 	get_settings
	 * @return 	void
	 */
	function __construct()
	{
		parent::__construct();
		log_message('debug', 'Theme Model Initialized');
	}
	
	// ------------------------------------------------------------------------
	
	/**
 	* Load Active Template
 	*
 	* Load the config file for the active template.
 	*
 	* @access	private
 	* @param	string	the file
 	* @return	bool
 	*/
	function load_active_template($template)
	{
		if (file_exists(KBPATH .'themes/front/'.$template.'/config.php'))
		{
			require_once(KBPATH .'themes/front/'.$template.'/config.php');
			$preview = 'front/'.$template.'/preview.png';
			if ($this->_testexists($preview))
			{
				$data['template']['preview']=base_url().'themes/'.$preview;
			}
			else
			{
				$data['template']['preview']=base_url().'images/nopreview.gif';
			}
			return $data['template'];
		}
		else
		{
			return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Load All Other Templates
	 *
	 * @access	public
	 * @param	string	the default template
	 * @return	arr
	 */
	function load_templates($default)
	{
		$location = KBPATH.'themes/front/';
		$available_theme = '';
		$i=0;
		if ($handle = opendir($location)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if (is_dir($location.$file) && $file != $default && $file != "." && $file != ".." && $file != ".DS_Store" && $file != ".svn" && $file != "modules" && $file != "index.htm") 
				{
					$preview = $location.$file.'/preview.png';
					if (file_exists($preview))
					{
						$preview = base_url().'themes/front/'.$file.'/preview.png';
					}
					else
					{
						$preview = base_url().'images/nopreview.gif';
					}
					if (file_exists($location.$file.'/config.php'))
					{
						require_once($location.$file.'/config.php');
						$available_theme[$i]['title']=$data['template']['name'];
						$available_theme[$i]['description']=$data['template']['description'];
						$available_theme[$i]['version']=$data['template']['version'];
						$available_theme[$i]['file'] = $file;
						$available_theme[$i]['preview_img'] = $preview;
						$available_theme[$i]['name']=$file;
						$available_theme[$i]['preview']=$preview;
					}
					else
					{
						$available_theme[$i]['title']=$file;
						$available_theme[$i]['preview_img'] = $preview;
						$available_theme[$i]['file'] = $file;
					}
				}
				$i++;
			}
			closedir($handle);
		}
		return $available_theme;
	}
	
	// ------------------------------------------------------------------------
		
	/**
	 * Test if a file exists
	 *
	 * @access	private
	 * @param	string	the file
	 * @return	bool
	 */
	function _testexists($file)
	{
		$file = KBPATH.'themes/'.$file;
		if ( ! file_exists($file))
		{
			return false;
		}
		return true;
	}
}

/* End of file template_model.php */
/* Location: ./upload/includes/application/models/template_model.php */ 