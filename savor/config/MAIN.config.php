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
if (basename($_SERVER['PHP_SELF']) == 'MAIN.config.php') { die("No. You cant."); }
//errors config
require_once (ROOT.'savor/config/ERROR.config.php');
//secret config
require_once (ROOT.'savor/config/SECRET.config.php');
//time zone config
require_once (ROOT.'savor/config/TIME.config.php');
//database config
require_once (ROOT.'savor/config/DB.config.php');
//MySql connecting file
include_once ROOT.'entrails/control/CONNECT.php';
//settings
require_once ROOT.'entrails/control/SETTINGS.php';

?>