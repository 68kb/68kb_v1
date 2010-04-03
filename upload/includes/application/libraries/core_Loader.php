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
 * 68KB Core Loader
 *
 * This extends the CI_Loader library so we can set the views to another directory.
 *
 * @package		68kb
 * @subpackage	Libraries
 * @category	Libraries
 * @author		68kb Dev Team
 * @link		http://68kb.com/
 * @version 	$Id: core_Loader.php 49 2009-07-28 19:21:51Z suzkaw68 $
 */
class core_Loader extends CI_Loader{
	
	var $_ci_view_path		= '';
	
	function __construct()
	{
		parent::__construct();
		$this->_ci_view_path = KBPATH .'themes/';
	}
}

/* End of file core_loader.php */
/* Location: ./upload/includes/application/libraries/core_loader.php */ 