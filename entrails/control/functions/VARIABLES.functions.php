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
 * Tikrina ar kintamasis teigiamas skaičius
 *
 * @param int $value
 *
 * @return int 1 arba NULL
 */
function isNum( $value ) {

	return @preg_match( "/^[0-9]+$/", $value ); //}
}
/**
 * Suapavalinimas
 *
 * @param     $sk
 * @param int $kiek
 *
 * @return float
 */
function rounding( $sk, $kiek = 2 ) {

	if ( $kiek < 0 ) {
		$kiek = 0;
	}
	$mult = pow( 10, $kiek );

	return ceil( $sk * $mult ) / $mult;
}
?>