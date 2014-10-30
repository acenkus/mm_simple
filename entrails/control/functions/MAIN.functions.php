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
 
// Patikrinimui ar nesikreipiama tiesiogiai
if ( basename( $_SERVER['PHP_SELF'] ) == 'funkcijos.php' ) {
	ban( $lang['system']['forhacking'] );
}
//lang functions
require_once (ROOT.'entrails/control/functions/LANG.functions.php');
//name functions
require_once (ROOT.'entrails/control/functions/NAME.functions.php');
//querry functions
require_once (ROOT.'entrails/control/functions/QUERY.functions.php');
//URL
//for site adress
require_once (ROOT.'entrails/control/functions/URL.functions.php');
//variables
//page functions
require_once (ROOT.'entrails/control/functions/VARIABLES.functions.php');
require_once (ROOT.'entrails/control/VARIABLES.php');
//USERS
//users display
require_once (ROOT.'entrails/control/USER.php');
//users functions
require_once (ROOT.'entrails/control/functions/USER.functions.php');
//page functions
require_once (ROOT.'entrails/control/functions/PAGE.functions.php');



function build_menu( $data, $id = 0, $active_class = 'active' ) {

	if ( !empty( $data ) ) {
		$re = "";
		foreach ( $data[$id] as $row ) {
			if ( isset( $data[$row['id']] ) ) {
				$re .= "\n\t\t<li " . ( ( isset( $_GET['id'] ) && $_GET['id'] == $row['id'] ) ? 'class="' . $active_class . '"' : '' ) . "><a href=\"" . url( "?id,{$row['id']}" ) . "\">" . $row['pavadinimas'] . "</a>\n<ul>\n\t";
				$re .= build_menu( $data, $row['id'], $active_class );
				$re .= "\t</ul>\n\t</li>";
			} else {
				$re .= "\n\t\t<li " . ( ( isset( $_GET['id'] ) && $_GET['id'] == $row['id'] ) ? 'class="' . $active_class . '"' : '' ) . "><a href=\"" . url( "?id,{$row['id']}" ) . "\">" . $row['pavadinimas'] . "" . ( isset( $row['extra'] ) ? $row['extra'] : '' ) . "</a></li>";
			}
		}

		return $re;
	} else {
		return FALSE;
	}
}


?>