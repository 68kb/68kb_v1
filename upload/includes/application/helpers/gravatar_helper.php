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

/**
* Gravatar Helper
*
* @package      68kb
* @subpackage   Helpers
* @category     Helpers
* @author       David Cassidy
* @link			http://codeigniter.com/wiki/Gravatars/
*/

/**
* Gravatar
*
* Fetches a gravatar from the Gravatar website using the specified params
*
* @access  public
* @param   string
* @param   string
* @param   integer
* @param   string
* @return  string
*/
function gravatar( $email, $rating = 'X', $size = '80', $default = 'http://gravatar.com/avatar.php' ) {
    # Hash the email address
    $email = md5( $email );

    # Return the generated URL
    return "http://gravatar.com/avatar.php?gravatar_id="
        .$email."&amp;rating="
        .$rating."&amp;size="
        .$size."&amp;default="
        .$default;
}

/* End of file gravatar_helper.php */
/* Location: ./upload/includes/application/helpers/gravatar_helper.php */ 