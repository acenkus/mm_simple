<?php

$aSqlBlocks = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape(lang()) . " ORDER BY `place` ASC", 120);
//user level
$sUserLevel = $_SESSION[SLAPTAS]['level'];
//blocks include DIR
$sBinclude = "blokai/";

foreach ($aSqlBlocks as $row_p) {

	if (teises($row_p['teises'], $sUserLevel)) {

		if (is_file($sBinclude . $row_p['file'])) {

			include_once ($sBinclude . $row_p['file']);

			if (!isset($title)) {

				$title = $row_p['panel'];

			}

			if ($row_p['show'] == 'Y' && isset($text) && !empty($text) && isset($sUserLevel) && teises($row_p['teises'], $sUserLevel)) {

				lentele_r($title, $text);

				unset($title, $text);

			} elseif (isset($text) && !empty($text) && $row_p['show'] == 'N' && isset($sUserLevel) && teises($row_p['teises'], $sUserLevel)) {

				echo $text;

				unset($text, $title);

			} else {

				unset($text, $title);

			}

		} else {

			echo lentele_r("{$lang['system']['error']}", "{$lang['system']['nopanel']}.", $row_p['file']);

		}

	}

}//end of foreach

unset($sql_p, $row_p);
?>