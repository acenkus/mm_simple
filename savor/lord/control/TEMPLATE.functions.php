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

//head tags main info
$sHead = '
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>' . input( strip_tags( $conf['Pavadinimas'] ) ) . ' - Admin</title>
	<meta name="description" content="' . input( strip_tags( $conf['Pavadinimas'] ) . ' - ' . trimlink( strip_tags( $conf['Apie'] ), 120 ) ) . '" />
	<meta name="keywords" content="' . input( strip_tags( $conf['Keywords'] ) ) . '" />
	<meta name="author" content="' . input( strip_tags( $conf['Copyright'] ) ) . '" />
	

        <!-- Core CSS - Include with every page -->
    <link href="savor/lord/templates/basic/css/bootstrap.min.css" rel="stylesheet">
    <link href="savor/lord/templates/basic/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="savor/lord/templates/basic/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="savor/lord/templates/basic/css/plugins/timeline/timeline.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="savor/lord/templates/basic/css/sb-admin.css" rel="stylesheet">
	';

//Menu
function Menu($limit = '8') {

	global $dbCon;

	$aSqlMenu = $dbCon -> query("SELECT * FROM `" . dbPrefix . "pages` WHERE `parent` = 0 AND `show` = 'Y' AND `lang` = " . escape(lang()) . " ORDER BY `place` ASC LIMIT {$limit}");

	$sText = '';

	foreach ($aSqlMenu as $aRow) {

		if (teises($aRow['teises'], $_SESSION[SECRET]['level'])) {

			$sText .= '<li><a href="' . url('?id,' . (int)$aRow['id']) . '">' . input($aRow['pavadinimas']) . '</a></li>';

		}
	}

	return $sText;
}


//copyright info
$sCopyRight = $conf['Copyright'];
?>