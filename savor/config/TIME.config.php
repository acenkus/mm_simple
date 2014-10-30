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
if (basename($_SERVER['PHP_SELF']) == 'TIME.config.php') { die("No. You cant."); }

//need to think about timezone
date_default_timezone_set('Europe/Vilnius');//Lithuanian time zone



?>