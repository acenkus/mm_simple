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

//settings
$aSql = $dbCon -> query('SELECT * FROM `' . dbPrefix . 'settings`');
$conf = array();
$sRows = $aSql -> rowCount();
if ($sRows > 1) while ($aRow = $aSql -> fetch(PDO::FETCH_ASSOC)) $conf[$aRow['key']] = $aRow['val'];
//while($row = mysql_fetch_assoc($sql)) $conf[$row['key']] = $row['val'];
unset($sRows, $aRow, $aSql, $sDbHost, $sDbName, $sDbUser, $sDbPass);
//languege
$lang = array();
if (isset($conf['kalba'])) {
	require_once ROOT.'savor/language/lithuanian/' . (empty($_SESSION[SECRET]['lang']) ? basename($conf['kalba'], '.php') : $_SESSION[SECRET]['lang']) . '.php';
} else {
	require_once ROOT.'savor/language/lithuanian/lt.php';
}
//if we dont connect to settings
if (!isset($conf) || empty($conf))
	die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");

//include system engine ???
$sDirTemplates = 'savor/view/templates/';
$sDirPages = 'savor/view/pages/';
//blocks include DIR
$sBinclude = 'savor/view/blocks/';
?>