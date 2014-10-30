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

$aSqlMenu = $dbCon -> query("SELECT * FROM `" . dbPrefix . "pages` WHERE `show`='Y' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC");

$aData = array();
if ( sizeof( $aSqlMenu ) > 0 ) {
	foreach ( $aSqlMenu as $aRow ) {
		if ( teises( $aRow['teises'], $_SESSION[SECRET]['level'] ) ) {
			$aData[$aRow['parent']][] = $aRow;
		}
	}
	$sText = "<div id=\"navigation\"><ul>" . build_menu( $aData ) . "</ul></div>";
} else {
	$sText = "";
}
unset( $aData, $aSqlMenu);

?>