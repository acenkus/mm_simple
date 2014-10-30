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
 * Vartotojui atvaizduoti
 *
 * @param string $user    nickas
 * @param int    $id
 * @param int    $level   levelis
 * @param bool   $extra
 *
 * @return string
 */
function user( $user, $id = 0, $level = 0, $extra = FALSE ) {

	global $lang, $conf;
	if ( $user == '' || $user == $lang['system']['guest'] ) {

		return $lang['system']['guest'];
	} else {
		if ( isset( $conf['puslapiai']['pm.php'] ) && $id != 0 && isset( $_SESSION[SLAPTAS]['id'] ) && $id != $_SESSION[SLAPTAS]['id'] ) {
			$pm = "<a href=\"" . url( "?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace( "=", "", base64_encode( $user ) ) ) . "\"><img src=\"" . ROOT . "images/pm/mail.png\"  style=\"vertical-align:middle\" alt=\"pm\" border=\"0\" /></a>";
		} else {
			$pm = '';
		}
		if ( isset( $conf['level'][$level]['pav'] ) ) {
			$img = '<img src="' . ROOT . 'images/icons/' . $conf['level'][$level]['pav'] . '" border="0" class="middle" alt="" /> ';
		} else {
			$img = '';
		}
		if ( isset( $conf['puslapiai']['view_user.php']['id'] ) && $id != 0 ) {
			return $img . '<a href="' . url( '?id,' . $conf['puslapiai']['view_user.php']['id'] . ';' . $user ) . '" title="' . input( $user ) . " " . $extra . '">' . trimlink( $user, 10 ) . '</a> ' . $pm;
		} else {
			return '<div style="display:inline;" title="' . input( $user ) . '" "' . $extra . '">' . $img . ' ' . trimlink( $user, 10 ) . ' ' . $pm . '</div>';
		}
	}
}
 
 /**
 * Grąžina amžių, nurodžius datą
 *
 * @param string $data
 *
 * @return int
 */
function amzius( $data ) {

	if ( !isset( $data ) || $data == '' || $data == '0000-00-00' ) {
		return "- ";
	} else {
		$data   = explode( "-", $data );
		$amzius = time() - mktime( 0, 0, 0, $data['1'], $data['2'], $data['0'] );
		$amzius = date( "Y", $amzius ) - 1970;

		return $amzius;
	}
}
/**
 * Grąžina lankytojo IP
 *
 * @return string
 */
 function IPv4To6($ip) {
 if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === true) {
  if (strpos($ip, '.') > 0) {
   $ip = substr($ip, strrpos($ip, ':')+1);
  } else { //native ipv6
   return $ip;
  }
 }
 $is_v4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
 if (!$is_v4) { return false; }
 $iparr = array_pad(explode('.', $ip), 4, 0);
    $Part7 = base_convert(($iparr[0] * 256) + $iparr[1], 10, 16);
    $Part8 = base_convert(($iparr[2] * 256) + $iparr[3], 10, 16);
    return '::ffff:'.$Part7.':'.$Part8;
}
 
function getip() {


		$ip = IPv4To6($_SERVER['REMOTE_ADDR']);
	if ( $ip == "" ) {
		$ip = "x.x.x.x";
	}

	return $ip;
}
/**
 * Gražina true arba false (nustatom vartotojo teises)
 *
 * @param array $mas serialize
 * @param int   $lvl
 *
 * @return true/false
 */
function teises( $mas, $lvl ) {

	if ( !empty( $mas ) && !is_array( $mas ) ) {
		$mas = @unserialize( $mas );
	}
	if ( $lvl == 1 || ( is_array( $mas ) && in_array( $lvl, $mas ) ) || empty( $mas ) ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/**
 * Uždrausti IP ant serverio
 *
 * @param string $ipas
 * @param string $kodel
 */
function ban( $ipas = '', $kodel = '' ) {

	global $lang, $_SERVER, $ip, $forwarded, $remoteaddress;
	if ( empty( $kodel ) ) {
		$kodel = $lang['system']['forhacking'] . ' - ' . input( str_replace( "\n", "", $_SERVER['QUERY_STRING'] ) );
	}
	if ( empty( $ipas ) ) {
		$ipas = getip();
	}
	$atidaryti = fopen( ROOT . ".htaccess", "a" );
	fwrite( $atidaryti, '# ' . $kodel . " \nSetEnvIf Remote_Addr \"^{$ipas}$\" draudziam\n" );
	fclose( $atidaryti );
	//@chmod(".htaccess", 0777);

	$forwarded     = ( isset( $forwarded ) ? $forwarded : 'N/A' );
	$remoteaddress = ( isset( $remoteaddress ) ? $remoteaddress : 'N/A' );
	$ip            = ( isset( $ip ) ? $ip : getip() );
	$referer       = ( isset( $_SERVER["HTTP_REFERER"] ) ? $_SERVER["HTTP_REFERER"] : 'N/A' );

	$message = <<< HTML
  FROM:{$referer}
  REQ:{$_SERVER['REQUEST_METHOD']}
  FILE:{$_SERVER['SCRIPT_FILENAME']}
  QUERY:{$_SERVER['QUERY_STRING']}
  IP:{$ip} - Forwarded = {$forwarded} - Remoteaddress = {$remoteaddress}
HTML;

	if ( $kodel == $lang['system']['forhacking'] ) {
		die( "<center><h1>{$lang['system']['nohacking']}!</h1><font color='red'><b>" . $kodel . " - {$lang['system']['forbidden']}<blink>!</blink></b></font><hr/></center>" );
	}
}
/**
 * Patikrina ar vartotojas turi "admin" teises.
 * grąžina true arba false
 *
 * @param  string $failas
 *
 * @global array  $_SESSION
 * @return bool <type>
 */
function ar_admin( $failas ) {

	global $_SESSION;

	if ( ( is_array( unserialize( $_SESSION[SLAPTAS]['mod'] ) ) && in_array( $failas, unserialize( $_SESSION[SLAPTAS]['mod'] ) ) ) || $_SESSION[SLAPTAS]['level'] == 1 ) {
		return TRUE;
	} else {
		return FALSE;
	}
}
/**
 * Grąžina vartotojo avatarą
 *
 * @param $mail string emeilas
 * @param $size int
 *
 * @return string formated html
 */
function avatar( $mail, $size = 80 ) {
	global $conf;
	if ( file_exists( ROOT . 'images/avatars/' . md5( $mail ) . '.jpeg' ) ) {
		$result = '<img src="' . ROOT . 'images/avatars/' . md5( $mail ) . '.jpeg?' . time() . '" width="' . $size . '" height="' . $size . '" alt="avataras" />';
	} else {
		$avatardir = (
		file_exists( ROOT . 'stiliai/' . $conf['Stilius'] . '/no_avatar.png' )
			? 'stiliai/' . $conf['Stilius'] . '/no_avatar.png'
			: 'images/avatars/no_avatar.png'
		);
		$result    = '<img src="http://www.gravatar.com/avatar/' . md5( strtolower( $mail ) ) . '?s=' . htmlentities( $size . '&r=any&default=' . urlencode( adresas() . $avatardir ) . '&time=' . time() ) . '"  width="' . $size . '" alt="avataras" />';
	}

	return $result;
}
?>