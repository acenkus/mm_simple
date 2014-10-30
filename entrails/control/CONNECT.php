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
 
//TODO: if system not founding DB.config.php info in applications than DB connect by alternative: system/../DB.config.php - ???
//connecting to DB
$dbCon = new PDO("mysql:host=$sDbHost;dbname=$sDbName;charset=utf8", $sDbUser, $sDbPass);
$dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbCon->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

?>