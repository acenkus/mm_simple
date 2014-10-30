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
 * Title gairės papildymui
 *
 * @param $add string
 */
function addtotitle($add) {

	//$add = input($add);
	echo <<<HTML
		<script type="text/javascript">
		var cur_title = new String(document.title);
      document.title = cur_title+" - {$add}";
    </script>
HTML;
}

/**
 * Patikrina ar puslapis egzistuoja ir ar vartotojas turi teise ji matyti bei grazinam puslapio ID
 *
 * @param string $puslapis
 * @param bool   $extra
 *
 * @return bool|int
 */
function puslapis($puslapis, $extra = FALSE) {

	global $conf, $sDirPages;
	$teises = @unserialize($conf['puslapiai'][$puslapis]['teises']);

	if (isset($conf['puslapiai'][$puslapis]['id']) && !empty($conf['puslapiai'][$puslapis]['id']) && is_file($sDirPages . $puslapis)) {

		if ($_SESSION[SECRET]['level'] == 1 || (is_array($teises) && in_array($_SESSION[SECRET]['level'], $teises)) || empty($teises)) {

			if ($extra && isset($conf['puslapiai'][$puslapis][$extra])) {
				return $conf['puslapiai'][$puslapis][$extra];
			}//Jei reikalinga kita informacija apie puslapi - grazinam ja.
			else {
				return (int)$conf['puslapiai'][$puslapis]['id'];
			}
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

/**
 * Puslapiavimui generuoti
 *
 * @param string   $start puslapis
 * @param int      $count limitas
 * @param int      $total viso
 * @param int      $range ruožas
 *
 * @return unknown
 */
function puslapiai($start, $count, $total, $range = 0) {

	$res = "";
	$pg_cnt = ceil($total / $count);

	if ($pg_cnt > 1) {
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page = ceil(($start + 1) / $count);
		$res .= "";
		$res .= "<div class=\"puslapiavimas\">\n";

		if ($idx_back >= 0) {

			if ($cur_page > ($range + 1)) {
				$res .= "<a href='" . url("p,0") . "'><span class=\"nuoroda\">««</span></a>\n";
			}
			$res .= "<a href='" . url("p,{$idx_back}") . "'><span class=\"nuoroda\">«</span></a>\n";
		}
		$idx_fst = max($cur_page - $range, 1);
		$idx_lst = min($cur_page + $range, $pg_cnt);

		if ($range == 0) {
			$idx_fst = 1;
			$idx_lst = $pg_cnt;
		}
		for ($i = $idx_fst; $i <= $idx_lst; $i++) {
			$offset_page = ($i - 1) * $count;

			if ($i == $cur_page) {
				$res .= "<span class=\"paspaustas\">{$i}</span>\n";
			} else {
				$res .= "<a href='" . url("p,{$offset_page}") . "'><span class=\"nuoroda\">{$i}</span></a>\n";
			}
		}
		if ($idx_next < $total) {
			$res .= "<a href='" . url("p,{$idx_next}") . "'><span class=\"nuoroda\">»</span></a>\n";

			if ($cur_page < ($pg_cnt - $range)) {
				$res .= "<a href='" . url("p," . ($pg_cnt - 1) * $count . "") . "'><span class=\"nuoroda\">»»</span></a>\n";
			}
		}
		$res .= "</div>\n";
	}

	return $res;
}
?>