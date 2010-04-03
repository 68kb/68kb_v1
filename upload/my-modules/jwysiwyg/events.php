<?php
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
 * jwysiwyg
 *
 * @package		68kb
 * @subpackage	Module
 * @category	Module
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/controller/article.html
 * @version 	$Id: events.php 45 2009-07-28 17:20:56Z suzkaw68 $
 */

class jwysiwyg_events
{
	function __construct(&$core_events)
	{
		$core_events->register('glossary/form', $this, 'show_editor');
		$core_events->register('category/form', $this, 'show_editor');
		$core_events->register('articles/form/description', $this, 'show_editor');
		$core_events->register('comment/form', $this, 'show_editor');
	}
	
	function show_editor()
	{
		$output='<script type="text/javascript" src="'.base_url().'/my-modules/jwysiwyg/jquery.wysiwyg.js"></script>';
		$output .= '<link rel="stylesheet" type="text/css" href="'.base_url().'/my-modules/jwysiwyg/jquery.wysiwyg.css" />';
		$output .= '
			<script type="text/javascript" >
				$(document).ready(function() {
					$(function()
					{
					    $(\'#editcontent\').wysiwyg();
					    $(\'#article_short_desc\').wysiwyg();
					    $(\'#comment_content\').wysiwyg();
					});
				});
			</script>
		';

		echo $output;
	}
}

/* End of file events.php */
/* Location: ./upload/my-modules/jwysiwyg/events.php */ 
