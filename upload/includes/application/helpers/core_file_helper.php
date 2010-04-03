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
 * Category Model
 *
 * This class is used to handle the categories data.
 *
 * @package		68kb
 * @subpackage	Helpers
 * @category	Helpers
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/helpers/uri.html
 * @version 	$Id: core_file_helper.php 96 2009-08-15 02:03:33Z suzkaw68 $
 */

// ------------------------------------------------------------------------

/**
 * Delete Files
 *
 * Deletes all files contained in the supplied directory path.
 * Files must be writable or owned by the system in order to be deleted.
 * If the second parameter is set to TRUE, any directories contained
 * within the supplied base directory will be nuked as well.
 *
 * This function overrides the system file_helper because we needed to
 * suppress errors with the unlink function.
 *
 * @access	public
 * @param	string	path to file
 * @param	bool	whether to delete any directories found in the path
 * @return	bool
 */
function delete_files($path, $del_dir = FALSE, $level = 0)
{	
	// Trim the trailing slash
	$path = preg_replace("|^(.+?)/*$|", "\\1", $path);
	
	if ( ! $current_dir = @opendir($path))
		return;

	while(FALSE !== ($filename = @readdir($current_dir)))
	{
		if ($filename != "." and $filename != "..")
		{
			if (is_dir($path.'/'.$filename))
			{
				// Ignore empty folders
				if (substr($filename, 0, 1) != '.')
				{
					delete_files($path.'/'.$filename, $del_dir, $level + 1);
				}
			}
			else
			{
				@unlink($path.'/'.$filename);
			}
		}
	}
	@closedir($current_dir);

	if ($del_dir == TRUE AND $level > 0)
	{
		@rmdir($path);
	}
}

/* End of file core_file_helper.php */
/* Location: ./upload/includes/application/helpers/core_file_helper.php */ 