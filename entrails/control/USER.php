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
 * Users levels
 *
 * @return array
 */
unset($sql, $row);
if (basename($_SERVER['PHP_SELF']) != 'upgrade.php' && basename($_SERVER['PHP_SELF']) != 'setup.php') {

	$sql = $dbCon -> query("SELECT * FROM `" . dbPrefix . "groups` WHERE `kieno` = 'vartotojai' AND `lang`=" . escape(lang()) . " ORDER BY `id` DESC LIMIT 1");

	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {
			$levels[(int)$row['id']] = array('pavadinimas' => $row['pavadinimas'], 'aprasymas' => $row['aprasymas'], 'pav' => input($row['pav']));
		}
	}

	$levels[1] = array('pavadinimas' => $lang['system']['admin'], 'aprasymas' => $lang['system']['admin'], 'pav' => 'admin.png');

	$levels[2] = array('pavadinimas' => $lang['system']['user'], 'aprasymas' => $lang['system']['user'], 'pav' => 'user.png');

	$conf['level'] = $levels;
	unset($levels, $sql, $row);

	/**
	 * Gaunam visus puslapius ir suformuojam masyvą
	 */

	$sql = $dbCon -> query("SELECT * FROM `" . dbPrefix . "pages` WHERE `lang`=" . escape(lang()) . " ORDER BY `place` ASC");

	foreach ($sql as $row) {
		$conf['puslapiai'][$row['file']] = array('id' => $row['id'], 'pavadinimas' => input($row['pavadinimas']), 'file' => input($row['file']), 'place' => (int)$row['place'], 'show' => $row['show'], 'teises' => $row['teises']);
		$conf['titles'][$row['id']] = (isset($lang['pages'][$row['file']]) ? $lang['pages'][$row['file']] : nice_name($row['file']));
		$conf['titles_id'][strtolower(str_replace(' ', '_', (isset($lang['pages'][$row['file']]) ? $lang['pages'][$row['file']] : nice_name($row['file']))))] = $row['id'];
	}
	// Nieko geresnio nesugalvojau
	$dir = explode('/', dirname($_SERVER['PHP_SELF']));
	$conf['titles']['999'] = $dir[count($dir) - 1] . '/admin';
	$conf['titles_id']['admin'] = 999;
	// Sutvarkom nuorodas
	if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
		$_GET = url_arr(cleanurl($_SERVER['QUERY_STRING']));
		if (isset($_GET['id'])) {
			$element = strtolower($_GET['id']);
			$_GET['id'] = ((isset($conf['titles_id'][$element]) && $conf['F_urls'] != '0') ? $conf['titles_id'][$element] : $_GET['id']);
		}
		$url = $_GET;
	} else {
		$url = array();
	}
}
?>