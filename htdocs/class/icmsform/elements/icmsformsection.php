<?php
/**
 * Form control creating a section in a form for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_ipf_Object
 * @since		  1.1
 * @author		  marcan <marcan@impresscms.org>
 * @version		$Id$
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * @todo	This is not functionnal yet.. it needs further integration
 */

class IcmsFormSection extends icms_form_Element {

	/**
	 * Text
	 * @var	string
	 * @access	private
	 */
	var $_value;

	/**
	 * Constructor
	 *
	 * @param	string  $sectionname    name of the form section
	 * @param	bool    $value          value of the form section
	 */
	function IcmsFormSection($sectionname, $value=false){
		$this->setName($sectionname);
		$this->_value = $value;
	}

	/**
	 * Get the text
	 *
	 * @return	string
	 */
	function getValue(){
		return $this->_value;
	}

	/**
	 * Prepare HTML for output
	 *
	 * @return	string
	 */
	function render(){
		return $this->getValue();
	}
}

?>