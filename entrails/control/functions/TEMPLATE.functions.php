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
	<base href="' . adresas() . '"></base>
	<meta name="generator" content="MightMedia TVS" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="' . lang() . '" />
	<meta name="description" content="' . input(strip_tags($conf['Pavadinimas']) . ' - ' . trimlink(trim(str_replace("\n\r", "", strip_tags($conf['Apie']))), 120)) . '" />
	<meta name="keywords" content="' . input(strip_tags($conf['Keywords'])) . '" />
	<meta name="author" content="' . input(strip_tags($conf['Copyright'])) . '" />
	<link rel="stylesheet" type="text/css" href="stiliai/system.css" />
	<link rel="stylesheet" type="text/css" href="stiliai/rating.css" />
	<link rel="stylesheet" type="text/css" href="' . $sDirTemplates . input(strip_tags($conf['Stilius'])) . '/default.css" />
	<link rel="shortcut icon" href="' . $sDirTemplates . input(strip_tags($conf['Stilius'])) . '/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href=" ' . $sDirTemplates . input(strip_tags($conf['Stilius'])) . '/favicon.ico" type="image/x-icon" />

	' . (isset($conf['puslapiai']['rss.php']) ? '<link rel="alternate" type="application/rss+xml" title="' . input(strip_tags($conf['Pavadinimas'])) . '" href="rss.php" />' : '') . '
	' . (isset($conf['puslapiai']['galerija.php']) ? '<link rel="alternate" href="gallery.php" type="application/rss+xml" title="" id="gallery" />' : '') . '
	
	<title>' . input(strip_tags($conf['Pavadinimas']) . ' - ' . $sPageName) . '</title>
	
<script type="text/javascript">
//Active mygtukas
 $(function(){
   var path = location.pathname.substring(1);
   if ( path )
     $(\'ul li a[href$="\' + path + \'"]\').attr(\'class\', \'active\');
 });

</script>';

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

//blocks

function Blocks($sAlign) {

	global $dbCon, $lang, $strError, $sPage, $sBinclude, $conf, $url;

	$aSqlBlocks = $dbCon -> query("SELECT * FROM `" . dbPrefix . "blocks` WHERE `align`='$sAlign' AND `lang` = " . escape(lang()) . " ORDER BY `place` ASC");
	//user level
	$sUserLevel = $_SESSION[SECRET]['level'];
	//if Align is Center
	if ($sAlign == 'C') {
		if (isset($strError) && !empty($strError)) {
			klaida("Klaida", $strError);
		}

	}

	foreach ($aSqlBlocks as $row_p) {

		if (teises($row_p['liberties'], $sUserLevel)) {

			if (is_file($sBinclude . $row_p['file'])) {

				include_once ($sBinclude . $row_p['file']);

				if (!isset($sTitle)) {

					$sTitle = $row_p['title'];

				}

				if ($row_p['show'] == 'Y' && isset($sText) && !empty($sText) && isset($sUserLevel) && teises($row_p['liberties'], $sUserLevel)) {

					lentele_r($sTitle, $sText);

					unset($sTitle, $sText);

				} elseif (isset($sText) && !empty($sText) && $row_p['show'] == 'N' && isset($sUserLevel) && teises($row_p['liberties'], $sUserLevel)) {

					echo $sText;

					unset($sText, $sTitle);

				} else {

					unset($sText, $sTitle);

				}

			} else {

				lentele_r($lang['system']['error'], $lang['system']['nopanel'], $row_p['file']);

			}

		}

	}//end of foreach

	//if Align is Center
	if ($sAlign == 'C') {
		include ($sPage . ".php");
	}

}

//copyright info
$sCopyRight = $conf['Copyright'];
?>