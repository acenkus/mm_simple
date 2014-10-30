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
if (basename($_SERVER['PHP_SELF']) == 'ERROR.config.php') { die("No. You cant."); }

//what we do with error reporting?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');//Klaidu pranesimai On/Off

?>