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

//clear POST from xss
if (!empty($_POST)) {
	include_once ('entrails/control/functions/SAFEHTML.functions.php');
	foreach ($_POST as $key => $value) {
		if (!is_array($value)) {
			$post[$key] = safe_html($value);
		} else {
			$post[$key] = $value;
		}
	}
	unset($_POST);
	$_POST = $post;
}

?>