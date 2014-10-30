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

//Guest level = 0
$sWay = explode('/', adresas());
//something with paths
define("PATH", (!empty($sWay[sizeof($sWay) - 2]) ? "/{$sWay[sizeof($sWay)-2]}/" : "/"));
define("DOM", $sWay[2]);
//level check
if (!isset($_SESSION[SECRET]['level'])) {
	$_SESSION[SECRET]['level'] = 0;
	$_SESSION[SECRET]['mod'] = serialize(array());
}

//session cheking
if (isset($_SESSION[SECRET]['username']) && isset($_SESSION[SECRET]['password'])) {

	$aSqlInfo = $dbCon -> query("SELECT `id`, `levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . dbPrefix . "groups` WHERE `id`=levelis) as `mod` FROM `" . dbPrefix . "users` WHERE `nick`=" . escape($_SESSION[SECRET]['username']) . " AND `pass`=" . escape($_SESSION[SECRET]['password']) . " LIMIT 1");
	$aLogin = $aSqlInfo -> fetch(PDO::FETCH_ASSOC);

	if (!empty($aLogin['levelis'])) {
		login($aLogin);
	} else {
		logout();
	}

	//if ar cookie login in with it
} elseif (isset($_COOKIE[SECRET]['user']) && !empty($_COOKIE[SECRET]['user'])) {

	$user_id = explode(".", $_COOKIE[SECRET]['user'], 2);

	if (isnum($user_id['0'])) {
		$user_pass = $user_id['1'];
		$user_id = $user_id['0'];
	}

	//user info select
	$aSqlInfo2 = $dbCon -> query("SELECT `id`, `levelis`,`pass`,`nick`,`login_data`,`login_before`, (SELECT `mod` FROM `" . dbPrefix . "groups` WHERE `id`=levelis) as `mod` FROM `" . dbPrefix . "users` WHERE `id`=" . escape((int)$user_id) . " LIMIT 1");
	$aLogin2 = $aSqlInfo2 -> fetch(PDO::FETCH_ASSOC);

	if (!empty($aLogin2['levelis']) && isset($user_pass) && koduoju($sSecret . getip() . $aLogin2['pass']) === $user_pass) {

		//login information update to users table
		//$result = $dbCon->exec( "UPDATE `" . dbPrefix . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = INET_ATON(" . escape( getip() ) . ") WHERE `id` ='" . escape( $user_id ) . "' LIMIT 1" );
		$result = $dbCon -> exec("UPDATE `" . dbPrefix . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = " . escape(getip()) . " WHERE `id` ='" . escape($user_id) . "' LIMIT 1");

		login($aLogin2);

	} else {
		//insert log
		$dbCon -> exec("INSERT INTO `" . dbPrefix . "logs` (`action` ,`time` ,`ip`) VALUES (" . escape("{$lang['user']['cookie']}: UserID: " . $user_id . " Pass: " . $user_pass) . ", '" . time() . "', INET_ATON(" . escape(getip()) . "))");

		$strError = $lang['user']['cookie'];
		logout();
	}
}
//login through html form
if (isset($_POST['action']) && $_POST['action'] == 'prisijungimas') {

	//if trying did not exceed the limit
	if (!isset($_SESSION[SECRET]['login_error']) || $_SESSION[SECRET]['login_error'] <= 4) {
		$strUsername = $_POST['vartotojas'];
		// Username
		$strPassword = koduoju($_POST['slaptazodis']);
		// Password
		//user info select
		$aSqlInfo3 = $dbCon -> query("SELECT `id`,`levelis`,`pass`,`nick`,`login_data`,`login_before`,(SELECT `mod` FROM `" . dbPrefix . "groups` WHERE `id`=levelis) as `mod` FROM `" . dbPrefix . "users` WHERE hex(nick)=hex(" . escape($strUsername) . ") AND password(pass)=password('" . $strPassword . "') LIMIT 1");
		$aLogin3 = $aSqlInfo3 -> fetch(PDO::FETCH_ASSOC);

		if (!empty($aLogin3) && $strPassword === $aLogin3['pass']) {

			login($aLogin3);

			//$dbCon->exec( "UPDATE `" . dbPrefix . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = INET_ATON(" . escape( getip() ) . ") WHERE `id` ='" . $aLogin3['id'] . "' LIMIT 1" );
			$dbCon -> exec("UPDATE `" . dbPrefix . "users` SET `login_before`=login_data, `login_data` = '" . time() . "', `ip` = " . escape(getip()) . " WHERE `id` ='" . $aLogin3['id'] . "' LIMIT 1");

			//session remember
			if (isset($_POST['Prisiminti']) && $_POST['Prisiminti'] == 'on') {
				setcookie("user", $_SESSION[SECRET]['id'] . "." . koduoju($sSecret . getip() . $_SESSION[SECRET]['password']), time() + 60 * 60 * 24 * 30, PATH, DOM);
			}

			header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : adresas()));

		} else {

			$sUserInfo = "{$lang['user']['wrong']}: User: " . $strUsername . " Pass: " . str_repeat('*', strlen($_POST['slaptazodis']));

			$dbCon -> exec("INSERT INTO `" . dbPrefix . "logs` (`action` ,`time` ,`ip`) VALUES (" . escape($sUserInfo) . ", '" . time() . "', " . escape(getip()) . ");");

			$strError = $lang['user']['wrong'];
			// + try
			isset($_SESSION[SECRET]['login_error']) ? $_SESSION[SECRET]['login_error']++ : $_SESSION[SECRET]['login_error'] = 1;
			//waiting time
			$_SESSION[SECRET]['timeout_idle'] = time() + ini_get('session.cache_expire');
		}
		unset($linfo, $strUsername, $strPassword);
	} else {

		$strError = "{$lang['user']['cantlogin']}<span id='sekundes'>" . ($_SESSION[SECRET]['timeout_idle'] - time()) . "</span></b><script>startCount();</script>s. ";

		//if time ends
		if ($_SESSION[SECRET]['timeout_idle'] - time() <= 0) {
			unset($_SESSION[SECRET]['timeout_idle'], $_SESSION[SECRET]['login_error']);
		}
	}
}
//login off
if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] == $lang['user']['logout']) {
	logout();
	setcookie("PHPSESSID", "", time() - 3600, PATH, DOM);
	header("HTTP/1.0 401 Unauthorized");
	header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : adresas()));
}
?>
