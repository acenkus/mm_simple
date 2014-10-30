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
 
// I think in this file we need to leave just Mysql connect info. : Aivaras. 
// need to block system opening with browser
if (basename($_SERVER['PHP_SELF']) == 'DB.config.php') { die("No. You cant."); }
//Mysql Connect info
$sDbHost = 'localhost'; // Mysql Server
$sDbName = 'mm'; // Mysql Database Name
$sDbUser = 'root'; // Mysql Server Username
$sDbPass = ''; // Mysql Database Password
define('dbPrefix', 'mmd_'); // Mysql DB Table Prefix

?>