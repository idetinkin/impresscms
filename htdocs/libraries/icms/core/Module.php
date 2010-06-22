<?php
/**
 * Manage of modules
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: module.php 19450 2010-06-18 14:15:29Z malanciault $
 */

if(!defined('ICMS_ROOT_PATH')){exit();}

/**
 * @package 	kernel
 * @copyright 	copyright &copy; 2000 XOOPS.org
 **/

/**
 * A Module
 *
 * @package	kernel
 * @author	Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 **/
class icms_core_Module extends core_Object
{
	/**
	 * @var string
	 */
	var $modinfo;
	/**
	 * AdminMenu of the module
	 *
	 * @var array
	 */
	var $adminmenu;
	/**
	 * Header menu on admin of the module
	 *
	 * @var array
	 */
	var $adminheadermenu;
	/**
	 * array for messages
	 *
	 * @var array
	 */
	var $messages;

	/**
	 * Constructor
	 */
	function icms_core_Module()
	{
		$this->core_Object();
		$this->initVar('mid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 150);
		$this->initVar('version', XOBJ_DTYPE_INT, 100, false);
		$this->initVar('last_update', XOBJ_DTYPE_INT, null, false);
		$this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('isactive', XOBJ_DTYPE_INT, 1, false);
		$this->initVar('dirname', XOBJ_DTYPE_OTHER, null, true);
		$this->initVar('hasmain', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('hasadmin', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('hassearch', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('hasconfig', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('hascomments', XOBJ_DTYPE_INT, 0, false);
		// RMV-NOTIFY
		$this->initVar('hasnotification', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('dbversion', XOBJ_DTYPE_INT, 0, false);
	}

	/**
	 * Load module info
	 *
	 * @param   string  $dirname    Directory Name
	 * @param   boolean $verbose
	 **/
	function loadInfoAsVar($dirname, $verbose = true)
	{
		if(!isset($this->modinfo)) {$this->loadInfo($dirname, $verbose);}
		$this->setVar('name', $this->modinfo['name'], true);
		$this->setVar('version', (int) (100 * ($this->modinfo['version'] + 0.001)), true);
		$this->setVar('dirname', $this->modinfo['dirname'], true);
		$hasmain = (isset($this->modinfo['hasMain']) && $this->modinfo['hasMain'] == 1) ? 1 : 0;
		$hasadmin = (isset($this->modinfo['hasAdmin']) && $this->modinfo['hasAdmin'] == 1) ? 1 : 0;
		$hassearch = (isset($this->modinfo['hasSearch']) && $this->modinfo['hasSearch'] == 1) ? 1 : 0;
		$hasconfig = ((isset($this->modinfo['config']) && is_array($this->modinfo['config'])) || !empty($this->modinfo['hasComments'])) ? 1 : 0;
		$hascomments = (isset($this->modinfo['hasComments']) && $this->modinfo['hasComments'] == 1) ? 1 : 0;
		// RMV-NOTIFY
		$hasnotification = (isset($this->modinfo['hasNotification']) && $this->modinfo['hasNotification'] == 1) ? 1 : 0;
		$this->setVar('hasmain', $hasmain);
		$this->setVar('hasadmin', $hasadmin);
		$this->setVar('hassearch', $hassearch);
		$this->setVar('hasconfig', $hasconfig);
		$this->setVar('hascomments', $hascomments);
		// RMV-NOTIFY
		$this->setVar('hasnotification', $hasnotification);
	}

	/**
	 * Get module info
	 *
	 * @param   string  	$name
	 * @return  array|string	Array of module information.
	 * If {@link $name} is set, returns a single module information item as string.
	 **/
	function &getInfo($name = null)
	{
		if(!isset($this->modinfo)) {$this->loadInfo($this->getVar('dirname'));}
		if(isset($name))
		{
			if(isset($this->modinfo[$name])) {return $this->modinfo[$name];}
			$return = false;
			return $return;
		}
		return $this->modinfo;
	}

	/**
	 * Retreive the database version of this module
	 *
	 * @return int dbversion
	 **/
	function getDBVersion()
	{
		$ret = $this->getVar('dbversion');
		return $ret;
	}

	/**
	 * Get a link to the modules main page
	 *
	 * @return	string $ret or FALSE on fail
	 */
	function mainLink()
	{
		if($this->getVar('hasmain') == 1)
		{
			$ret = '<a href="'.ICMS_URL.'/modules/'.$this->getVar('dirname').'/">'.$this->getVar('name').'</a>';
			return $ret;
		}
		return false;
	}

	/**
	 * Get links to the subpages
	 *
	 * @return	string $ret
	 */
	function subLink()
	{
		$ret = array();
		if($this->getInfo('sub') && is_array($this->getInfo('sub')))
		{
			foreach($this->getInfo('sub') as $submenu)
			{
				$ret[] = array('name' => $submenu['name'], 'url' => $submenu['url']);
			}
		}
		return $ret;
	}

	/**
	 * Load the admin menu for the module
	 */
	function loadAdminMenu()
	{
		if($this->getInfo('adminmenu') && $this->getInfo('adminmenu') != '' && file_exists(ICMS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/'.$this->getInfo('adminmenu')))
		{
			include_once ICMS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/'.$this->getInfo('adminmenu');
			$this->adminmenu = & $adminmenu;
			if(isset($headermenu)) {$this->adminheadermenu = & $headermenu;}
		}
	}

	/**
	 * Get the admin menu for the module
	 *
	 * @return	string $this->adminmenu
	 */
	function &getAdminMenu()
	{
		if(!isset($this->adminmenu)) {$this->loadAdminMenu();}
		return $this->adminmenu;
	}

	/**
	 * Get the admin header menu for the module
	 *
	 * @return	string $this->adminmenu
	 */
	function &getAdminHeaderMenu()
	{
		if(!isset($this->adminheadermenu)) {$this->loadAdminMenu();}
		return $this->adminheadermenu;
	}

	/**
	 * Load the module info for this module
	 *
	 * @param   string  $dirname    Module directory
	 * @param   bool    $verbose    Give an error on fail?
	 * @return  bool   TRUE if success, FALSE if fail.
	 */
	function loadInfo($dirname, $verbose = true)
	{
		global $icmsConfig;
		icms_loadLanguageFile($dirname, 'modinfo');
		if(file_exists(ICMS_ROOT_PATH.'/modules/'.$dirname.'/icms_version.php'))
		{
			include ICMS_ROOT_PATH.'/modules/'.$dirname.'/icms_version.php';
		}elseif(file_exists(ICMS_ROOT_PATH.'/modules/'.$dirname.'/xoops_version.php'))
		{
			include ICMS_ROOT_PATH.'/modules/'.$dirname.'/xoops_version.php';
		}
		else
		{
			if(false != $verbose) {echo "Module File for $dirname Not Found!";}
			return false;
		}
		$this->modinfo = & $modversion;
		return true;
	}

	/**
	 * Search contents within a module
	 *
	 * @param   string  $term
	 * @param   string  $andor  'AND' or 'OR'
	 * @param   integer $limit
	 * @param   integer $offset
	 * @param   integer $userid
	 * @return  mixed   Search result or False if fail.
	 **/
	function search($term = '', $andor = 'AND', $limit = 0, $offset = 0, $userid = 0)
	{
		if($this->getVar('hassearch') != 1) {return false;}
		$search = & $this->getInfo('search');
		if($this->getVar('hassearch') != 1 || !isset($search['file']) || !isset($search['func']) || $search['func'] == '' || $search['file'] == '')
		{
			return false;
		}
		if(file_exists(ICMS_ROOT_PATH."/modules/".$this->getVar('dirname').'/'.$search['file']))
		{
			include_once ICMS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/'.$search['file'];
		}
		else
		{
			return false;
		}
		if(function_exists($search['func']))
		{
			$func = $search['func'];
			return $func($term, $andor, $limit, $offset, $userid);
		}
		return false;
	}

	/**
	 * Displays the (good old) adminmenu
	 *
	 * @param int  $currentoption  The current option of the admin menu
	 * @param string  $breadcrumb  The breadcrumb trail
	 * @param bool  $submenus  Show the submenus!
	 * @param int  $currentsub  The current submenu
	 *
	 * @return datatype  description
	 */
	function displayAdminMenu($currentoption = 0, $breadcrumb = '', $submenus = false, $currentsub = -1)
	{
		global $icmsModule, $icmsConfig;
		icms_loadLanguageFile($icmsModule->getVar('dirname'), 'modinfo');
		icms_loadLanguageFile($icmsModule->getVar('dirname'), 'admin');
		$tpl = new icms_core_Tpl();
		$tpl->assign(array('headermenu' => $this->getAdminHeaderMenu(), 'adminmenu' => $this->getAdminMenu(), 'current' => $currentoption, 'breadcrumb' => $breadcrumb, 'headermenucount' => count($this->getAdminHeaderMenu()), 'submenus' => $submenus, 'currentsub' => $currentsub, 'submenuscount' => count($submenus)));
		$tpl->display(ICMS_ROOT_PATH.'/modules/system/templates/admin/system_adm_modulemenu.html');
	}

	/**#@+
	 * For backward compatibility only!
	 * @deprecated
	 */
	function mid() {return $this->getVar('mid');}
	function dirname() {return $this->getVar('dirname');}
	function name() {return $this->getVar('name');}
	function &getByDirName($dirname)
	{
		$modhandler = & xoops_gethandler('module');
		$inst = & $modhandler->getByDirname($dirname);
		return $inst;
	}

	/**
	 * Modules Message Function
	 *
	 * @since ImpressCMS 1.2
	 * @author Sina Asghari (aka stranger) <stranger@impresscms.org>
	 *
	 * @param string $msg	The Error Message
	 * @param string $title	The Error Message title
	 * @param	bool	$render	Whether to echo (render) or return the HTML string
	 *
	 * @todo Make this work with templates ;)
	 */
	function setMessage($msg, $title='', $render = false){
		$ret = '<div class="moduleMsg">';
		if($title != '') {$ret .= '<h4>'.$title.'</h4>';}
		if(is_array($msg))
		{
			foreach($msg as $m) {$ret .= $m.'<br />';}
		}
		else {$ret .= $msg;}
		$ret .= '</div>';
		if($render){
			echo $ret;
		}else{
			return $ret;
		}
	}
}
?>