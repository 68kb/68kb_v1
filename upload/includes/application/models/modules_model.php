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
 * Modules Model
 *
 * @package		68kb
 * @subpackage	Models
 * @category	Models
 * @author		68kb Dev Team
 * @link		http://68kb.com/user_guide/developer/modules.html
 * @version 	$Id: modules_model.php 91 2009-08-13 02:10:14Z suzkaw68 $
 */
class Modules_model extends Model {

	/**
 	* Instance of database connection class
 	* @access 	private
 	* @var 	object
 	*/
 	var $modules_dir = '';

	/**
	 * Constructor
	 *
	 * @return void
	 **/
	public function __construct()
	{
		parent::Model();
		log_message('debug', 'Modules_model Initialized');
		$this->modules_dir = KBPATH .'my-modules';
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Loads active modules
	* @return array
	*/
	function load_active()
	{
		$modules = array();
		$this->db->from('modules')->order_by('displayname', 'DESC');
		$query = $this->db->get();
		$result = $query->result_array();
		foreach($result as $row)
		{
			if ($this->exists($row['name']))
			{
				$data = $this->get_config($row['name']);
				if( ! empty($data))
				{
					$row['server_version']=$data['module']['version'];
					$help_file = $this->modules_dir.'/'.$data['module']['name'].'/readme.txt';
					if (file_exists($help_file)) {
						$row['help_file'] = base_url() .'my-modules/'.$data['module']['name'].'/readme.txt';
					}
				}
			}
			$modules[] = $row;
		}
		return $modules;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Loads not active modules
	*
	* @return array
	*/
	function load_unactive()
	{
		$opendir = opendir($this->modules_dir);
 		while (false !== ($module = readdir($opendir)))
		{
			// Ignores . and .. that opendir displays
			if ($module != '.' && $module != '..')
			{
				// Checks if they have the correct files
				if ($this->exists($module))
				{
					$this->modules["$module"]['file'] = 'my-modules/'.$module.'/config.php';
				}
			}
		}
		closedir($opendir);
		if (!empty($this->modules))
		{
			//assign $data to null
			$available_module=array();
			$i=0;
			foreach ($this->modules as $name => $module)
			{
				$data = $this->get_config($name);
				if( ! empty($data))
				{
					$this->modules["$name"]['data'] = $data;
					$this->db->from('modules')->where('directory', $name);
					$query = $this->db->get();
					if ($query->num_rows() == 0)
					{
						//it doesn't exist so not active
						$available_module[$i]['name']=$data['module']['name'];
						$available_module[$i]['displayname']=$data['module']['displayname'];
						$available_module[$i]['version']=$data['module']['version'];
						$available_module[$i]['description'] = $data['module']['description'];
						$help_file = $this->modules_dir.'/'.$name.'/readme.txt';
						if (file_exists($help_file)) {
							$available_module[$i]['help']='../my-modules/'.$name.'/readme.txt';
						}
						if (file_exists($this->modules_dir.'/'.$name.'/init.php'))
						{
							$available_module[$i]['uninstall']=TRUE;
						}
					}
					$i++;
				}
			}
			return $available_module;
 		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get a module's config.php and return the $data array.
	 * If the config.php file is not found or the $data array
	 * is not present it returns an empty array.
	 *
	 * @access private
	 * @param string $moduleName
	 * @return array $mData
	 */
	function get_config($module_name)
	{
		$m_data = array();
		if (file_exists($this->modules_dir.'/'.$module_name.'/config.php')) 
		{
			include($this->modules_dir.'/'.$module_name.'/config.php');
			if (isset($data)) 
			{
				$m_data = $data;
				unset($data);
			}
		}
		elseif (file_exists($this->modules_dir.'/'.$module_name.'/events.php')) 
		{
			include($this->modules_dir.'/'.$module_name.'/events.php');
			if (isset($data)) 
			{
				$m_data = $data;
				unset($data);
			}
		}
		return $m_data;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Checks if a module exists
	*
	* @return boolean True if files exist, false otherwise
	*/
	function exists($module_name, $file = 'config.php')
	{
		return (@file_exists($this->modules_dir.'/'.$module_name.'/'.$file)) ? true : false;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Activate a module
	* @param string
	*/
	function activate($name)
	{
		if ($this->exists($name))
		{
			$data = $this->get_config($name);
		}
		$this->db->from('modules')->where('directory', $name);
		$query = $this->db->get();
		if ($query->num_rows() == 0)
		{
			//it doesn't exist
			$module_data = array(
				'name' => $data['module']['name'], 
				'displayname' => $data['module']['displayname'], 
				'description' => $data['module']['description'], 
				'directory' => $data['module']['name'], 
				'version' => $data['module']['version'], 
				'active' => 1
			);
			
			$this->db->insert('modules', $module_data);
			if($this->db->affected_rows() > 0) 
			{
				$id = $this->db->insert_id();
				$this->db->cache_delete_all();
				$this->init_module($id, 'install');
			}
		}
		else
		{
			
			return false;
		}
		
	}
	
	// ------------------------------------------------------------------------
	
	/**
	* Get a single module
	*
	* @param 	int $id Id of the module.
	* @return 	array of results
	* @access 	public
	*/
 	function get_module($id)
 	{
		$this->db->from('modules')->where('id', $id);
		$query = $this->db->get();
 		return $query->row_array(); 
 	}

	// ------------------------------------------------------------------------
	
	/**
	* Install, uninstall, or upgrade a module. This calls the init file in a module and runs one of the following functions:
	* install(), uninstall(), or upgrade().
	*
	* @param 	int $id
	* @param 	string $action
	*
	*/
 	function init_module($id, $action, $msg = '')
 	{
 		$directory=$this->get_module($id);
		
		if( ! $directory)
		{
			return false;
		}
		
        if ($this->exists($directory['directory'], '/init.php'))
		{
			require_once($this->modules_dir.'/'.$directory['directory'].'/init.php');
		}
		
		if($action=="deactivate")
		{
			if(function_exists('uninstall'))
			{
				$msg = uninstall();
			}
			$this->db->delete('modules', array('id' => $id)); 
			return $msg;
		}
		elseif($action=="upgrade")
		{
			if(function_exists('upgrade'))
			{
				upgrade();
			}
			if ($this->exists($directory['directory']))
			{
				$data = $this->get_config($directory['directory']);
				$module_data = array(
					'name' => $data['module']['name'], 
					'displayname' => $data['module']['displayname'], 
					'description' => $data['module']['description'], 
					'directory' => $data['module']['name'], 
					'version' => $data['module']['version'], 
					'active' => 1
				);
				$this->db->where('id', $id);
				$this->db->update('modules', $module_data);
				$this->db->cache_delete_all();
			}
		}
		else
		{
			if(function_exists('install'))
			{
				install();
			}
		}
 	}
}

/* End of file modules_model.php */
/* Location: ./upload/includes/application/models/modules_model.php */