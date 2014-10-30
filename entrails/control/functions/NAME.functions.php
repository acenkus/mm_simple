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
 * Sutvarkom failo pavadinimą
 *
 * @param string $name
 *
 * @return string formated
 */
function nice_name( $name ) {

	$name = ucfirst_utf8( $name );
	$name = basename( $name, '.php' );
	$name = str_replace( "_", " ", $name );

	return $name;
}
 
/**
 * Pirma raidė didžioji (utf-8)
 *
 * @param string $str
 *
 * @return string
 */
function ucfirst_utf8( $str ) {

	if ( mb_check_encoding( $str, 'UTF-8' ) ) {
		$first = mb_substr( mb_strtoupper( $str, "utf-8" ), 0, 1, 'utf-8' );

		return $first . mb_substr( mb_strtolower( $str, "utf-8" ), 1, mb_strlen( $str ), 'utf-8' );
	} else {
		return $str;
	}
}

/**
 * Sutvarkom tekstą saugiam atvaizdavimui
 * šito reikia jei nori gražinti į input'ą informaciją.
 * dažnai tai būna su visokiais \\\'? ir pan
 *
 * @param string $s
 *
 * @return string formated
 */
function input( $s ) {

	$s = htmlspecialchars( $s, ENT_QUOTES, "UTF-8" );

	return $s;
}
/**
 * Trim a line of text to a preferred length
 *
 * @param $text
 * @param $length
 *
 * @return mixed
 */
function trimlink( $text, $length ) {

	$dec  = array( "\"", "'", "\\", '\"', "\'", "<", ">" );
	$enc  = array( "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;" );
	$text = str_replace( $enc, $dec, $text );
	if ( strlen( strip_tags( $text ) ) > $length ) {
		$text = utf8_substr( $text, 0, ( $length - 3 ) ) . "...";
	}
	$text = str_replace( $dec, $enc, $text );

	return $text;
}
?>