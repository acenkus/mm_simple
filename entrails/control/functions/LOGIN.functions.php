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
 * SESSION seting - login in
 *
 * @param $array string
 */
 
function login( $array ) {

	$_SESSION[SECRET]['username'] = $array['nick'];
	$_SESSION[SECRET]['password'] = $array['pass'];
	$_SESSION[SECRET]['id']       = (int)$array['id'];
	$_SESSION[SECRET]['lankesi']  = $array['login_before'];
	$_SESSION[SECRET]['level']    = $array['levelis'];
	$_SESSION[SECRET]['mod']      = $array['mod'];
}

//logout
 
function logout() {

	unset( $_SESSION[SECRET]['username'], $_SESSION[SECRET]['password'], $_SESSION[SECRET]['id'], $_SESSION[SECRET]['level'], $_SESSION[SECRET]['mod'] ); // Isvalom sesija
	$_SESSION[SECRET]['level'] = 0;
	$_SESSION[SECRET]['mod']   = serialize( array() );
	setcookie( "user", "", time() - 3600, PATH, DOM ); // Sunaikinam sesija
}

/**
 * Password coding
 *
 * @param $pass
 *
 * @return string
 */
 
function koduoju( $pass ) {

	return md5( sha1( md5( $pass ) ) );
}


?>