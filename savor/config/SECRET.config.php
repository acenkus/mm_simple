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
 
// need to block system opening with browser
if (basename($_SERVER['PHP_SELF']) == 'SECRET.config.php') { die("No. You cant."); }
//$slaptas = "2ad564e4d419af77a33bd03d8badcafe";	//for cookies
//without define using in "entrails/control/LOGIN.php"
$sSecret = "2ad564e4d419af77a33bd03d8badpapa";	//for cookies
define('SECRET', $sSecret);

?>