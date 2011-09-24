<?php
/**
* Installer initial site configuration page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        2.3.0
* @author		Haruki Setoyama  <haruki@planewave.org>
* @author 		Kazumi Ono <webmaster@myweb.ne.jp>
* @author		Skalpa Keo <skalpa@xoops.org>
* @version		$Id$
*/

require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )	exit();

	$wizard->setPage( 'siteinit' );
	$pageHasForm = true;
	$pageHasHelp = false;

	$vars =& $_SESSION['siteconfig'];

	$error =& $_SESSION['error'];

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$vars['adminname'] = $_POST['adminname'];
	$vars['adminmail'] = $_POST['adminmail'];
	$vars['adminpass'] = $_POST['adminpass'];
	$vars['adminpass2'] = $_POST['adminpass2'];
	$error = '';

    if (!preg_match( "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $vars['adminmail'] ) ) {
    	$error = ERR_INVALID_EMAIL;
    } elseif ( @empty( $vars['adminname'] ) || @empty( $vars['adminpass'] ) || @empty( $vars['adminmail'] ) ) {
    	$error = ERR_REQUIRED;
	} elseif ( $vars['adminpass'] != $vars['adminpass2'] ) {
    	$error = ERR_PASSWORD_MATCH;
	}
	if ( $error ) {
    	$wizard->redirectToPage( '+0' );
    	return 200;
	} else {
    	$wizard->redirectToPage( '+1' );
    	return 302;
	}
}

    ob_start();
?>
<?php if ( !empty( $error ) ) echo '<div class="x2-note error">' . $error . "</div>\n"; ?>
<fieldset>
	<legend><?php echo LEGEND_ADMIN_ACCOUNT; ?></legend>
	<label for="adminname"><?php echo ADMIN_LOGIN_LABEL; ?></label>
	<input type="text" name="adminname" id="adminname" value="<?php echo htmlspecialchars( $vars['adminname'], ENT_QUOTES ); ?>" />
	<label for="adminmail"><?php echo ADMIN_EMAIL_LABEL; ?></label>
	<input type="text" name="adminmail" id="adminmail" value="<?php echo htmlspecialchars( $vars['adminmail'], ENT_QUOTES ); ?>" />
	<label for="adminpass"><?php echo ADMIN_PASS_LABEL; ?></label>
	<input type="password" name="adminpass" id="adminpass" value="" />
	<label for="adminpass2"><?php echo ADMIN_CONFIRMPASS_LABEL; ?></label>
	<input type="password" name="adminpass2" id="adminpass2" value="" />
</fieldset>
<?php
	$content = ob_get_contents();
	ob_end_clean();
	$error = '';
    include 'install_tpl.php';
?>