<?php
/**
 * Initiating Wideimage library
 *
 * This file is responsible for initiating the Wideimage library
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		libraries
 * @since		1.2
 * @author		TheRplima <therplima@impresscms.org>
 * @version		$Id: wideimage.php 10326 2010-07-11 18:54:25Z malanciault $
 */
/**
 *
 * Event triggers for WideImage library
 * @since	1.2
 *
 */
class IcmsPreloadWideimage extends icms_preload_Item {
	/**
	 * Function to be triggered at the end of the output init process
	 *
	 * @return	void
	 */
	function eventStartOutputInit() {
        if (extension_loaded('gd')) {
            // just including the file... more to come
            include_once  ICMS_LIBRARIES_PATH . '/wideimage/lib/WideImage.php' ;
        }		
	}

	/**
	 *
	 * Events to be triggered at the start of the admin pages loading
	 */
	function eventAdminHeader() {
        if (extension_loaded('gd')) { 
            include_once  ICMS_LIBRARIES_PATH . '/wideimage/lib/WideImage.php' ;
        }		
	}
}
