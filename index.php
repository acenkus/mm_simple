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

ob_start();
//head info
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
//SESSION
if (!isset($_SESSION)) {
	session_start();
}
//code time generator
$m1 = explode(" ", microtime());
$stime = $m1[1] + $m1[0];
define('ROOT', '');
//configs include
include_once ROOT.'savor/config/MAIN.config.php';
//main functions
require_once ROOT.'entrails/control/functions/MAIN.functions.php';
//login functions include
require_once ROOT.'entrails/control/functions/LOGIN.functions.php';
//login to system
require_once ROOT.'entrails/control/LOGIN.php';
//clear POST from xss
require_once ROOT.'entrails/control/POST.php';
//showing pages
require_once ROOT.'entrails/control/PAGE.php';
//if site turned off
//TODO: maintenance should be in page include
if ($conf['Palaikymas'] == 1) {
	if (!isset($_SESSION[SECRET]['id']) || $_SESSION[SECRET]['level'] != 1) {
		redirect("remontas.php");
	}
}
//if empty languege SESSION
if (!empty($_GET['lang'])) {
	$_SESSION[SECRET]['lang'] = basename($_GET['lang'], '.php');
	redirect(url("?id," . $_GET['id']));
}
//system and site head
require_once 'entrails/control/HEAD.php';
//template
include_once "entrails/control/functions/TEMPLATE.functions.php";
include_once $sDirTemplates . $conf['Stilius'] . '/functions.php';

if (empty($_GET['ajax'])) {
	include_once $sDirTemplates . $conf['Stilius'] . '/index.php';
} else {
	include_once $sPage . '.php';
}
//MySql close
$dbCon = null;
//connecting time
$m2 = explode(" ", microtime());
$etime = $m2[1] + $m2[0];
$ttime = ($etime - $stime);
$ttime = number_format($ttime, 7);
//showing for admin
if ($_SESSION[SECRET]['level'] == 1) {
	echo '<!-- Generated ' . rounding($ttime, 2) . 's. -->';
}

ob_end_flush();
?>