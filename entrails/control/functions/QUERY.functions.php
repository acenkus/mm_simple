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
 * Sutvarko SQL užklausą
 *
 * @param string $sql
 *
 * @return string escaped
 */
function escape($sQuery) {

	// Stripslashes
	if (get_magic_quotes_gpc()) {
		$sQuery = stripslashes($sQuery);
	}
	// if not number
	if (!isnum($sQuery) || $sQuery[0] == '0') {
		if (!isnum($sQuery)) {
			$sQuery = "'" . @mysql_real_escape_string($sQuery) . "'";
		}
	}

	return $sQuery;
}

/**
 * Suskaičiuojam kiek nurodytoje lentelėje yra įrašų
 *
 * @param string $table
 * @param string $where
 * @param string $as
 *
 * @return int
 */
function kiek($sTable, $sWhere = '') {

	global $dbCon;
	//$viso = array();
	$aAll = $dbCon -> query("SELECT * FROM `" . dbPrefix . $sTable . "` " . $sWhere . " limit 1");

	$sRow = $aAll -> rowCount();
	//return ( isset( $viso[$as] ) && $viso[$as] > 0 ? (int)$viso[$as] : (int)0 );
	return $sRow;
}
?>