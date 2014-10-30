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
 * Svetainės adresui gauti
 *
 * @return string
 */
function adresas() {

	if ( isset( $_SERVER['HTTP_HOST'] ) ) {
		$adresas = isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) !== 'off' ? 'https' : 'http';
		$adresas .= '://' . $_SERVER['HTTP_HOST'];
		$adresas .= str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $_SERVER['SCRIPT_NAME'] );
	} else {
		$adresas = 'http://localhost/';
	}

	return $adresas;
}
/**
 * Adreso apsauga
 *
 * @param string $url
 *
 * @return string
 */
function cleanurl( $url ) {

	$bad_entities  = array( '"', "'", "<", ">", "(", ")", '\\' );
	$safe_entities = array( "", "", "", "", "", "", "" );
	$url           = str_replace( $bad_entities, $safe_entities, $url );

	return $url;
}
/**
 * Adresa verčiam į masyvą
 *
 * @param string $params
 *
 * @return array
 */
function url_arr( $params ) {

	global $conf;
	$str2 = array();
	if ( !isset( $params ) ) {
		$params = $_SERVER['QUERY_STRING'];
	}

	if ( strrchr( $params, '&' ) ) {
		$params = explode( "&", $params );
	} //Jeigu tai paprastas GET
	else {
		$params = explode( ( ( empty( $conf['F_urls'] ) || $conf['F_urls'] == '0' ) ? ';' : $conf['F_urls'] ), $params );
	}

	if ( isset( $params ) && is_array( $params ) && count( $params ) > 0 ) {
		foreach ( $params as $key => $value ) {
			if ( strrchr( $value, '=' ) ) {
				$str1 = explode( "=", $value );
			} else {
				$str1 = explode( ",", $value );
			}
			if ( isset( $str1[1] ) ) {
				if ( preg_match( '%/\*\*/|SERVER|SELECT|UNION|DELETE|UPDATE|INSERT%i', $str1[1] ) ) {
					echo "BAN";
					ban();
				}
				$str2[$str1[0]] = $str1[1];
			}
		}
	}

	return $str2;
}

/**
 * "Friendly urls" apdorojimas
 *
 * @param $str
 *
 * @return string
 */
function url( $str ) {

	global $conf;

	if ( substr( $str, 0, 1 ) == '?' ) {
		$linkai    = explode( ';', $str );
		$start     = explode( ',', $linkai[0] );
		$linkai[0] = '';
		if ( !empty( $conf['F_urls'] ) && $conf['F_urls'] != '0' ) {

			// Žodinis linkas
			$url_title = !empty( $conf['titles'][$start[1]] ) ? $conf['titles'][$start[1]] : '';

			// Išmetam tarpus
			$url_title = str_replace( ' ', '_', $url_title );

			// Atskiriam atskirus getus pasirinktu simboliu
			$return = adresas() . ROOT . $url_title . implode( ( $conf['F_urls'] != '0' ? $conf['F_urls'] : ';' ), $linkai );
		} else {

			$return = adresas() . ( substr( $str, 4, 3 ) == '999' && ( empty( $conf['F_urls'] ) || $conf['F_urls'] == '0' ) ? 'main.php' : ( substr( $str, 0, 1 ) != '?' ? '' : ROOT ) ) . $str;
		}
	} else {

		$g = '?';
		foreach ( $_GET as $k => $v ) {

			$g .= "{$k},{$v};";
		}
		$return = url( $g . $str );
	}

	return $return;
}
/**
 * Nuskaitom turinį iš adreso
 *
 * @param string $url
 *
 * @return string
 */
function http_get( $url ) {

	$request = fopen( $url, "rb" );
	$result  = "";
	while ( !feof( $request ) ) {
		$result .= fread( $request, 8192 );
	}
	fclose( $request );

	return $result;
}
/**
 * Seo url TODO
 */

function seo_url( $url, $id ) {

// Sušveplinam
	$url = iconv( 'UTF-8', 'US-ASCII//TRANSLIT', $url );
// Nuimam tarpus pradžioje bei pabaigoje
	$url = trim( $url );
// Neaiškius simbolius pakeičiam brūkšniukais
	$url = preg_replace( '/[^A-z0-9-]/', '_', $url );
// Išvalom besikartojančius brūkšniukus
	$url = preg_replace( '/-+/', "-", $url );
// Verčiam viską į mažasias raides
	$url = strtolower( $url );

	return $url . $id;
}

/**
 * Naršyklių peradresavimas
 *
 * @param string $location
 * @param string $type
 */
function redirect( $location, $type = "header" ) {

	if ( $type == "header" ) {
		header( "Location: " . $location );
		exit;
	} elseif ( $type == "meta" ) {
		echo "<meta http-equiv='Refresh' content='1;url=$location'>";
	} else {
		echo "<script type='text/javascript'>document.location.href='" . $location . "'</script>\n";
	}
}

?>