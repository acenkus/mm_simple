<?php

/**
 * * @package    MightMedia TVS
 * * @author     Coders <www.coders.lt>
 * * @author     dewdrop <www.dewdrop.lt>
 * * @copyright  2008-2014 Mightmedia Team
 * * @license    GNU General Public License v2
 * * @version    v1.7
 * * @link       http://mightmedia.org
 * */

/**
 * Return languege
 *
 * @global array $conf
 * @return string
 */
function lang() {

	if (empty($_SESSION[SECRET]['lang'])) {
		global $conf;
		$_SESSION[SECRET]['lang'] = basename($conf['kalba'], '.php');
	}

	return $_SESSION[SECRET]['lang'];
}
?>