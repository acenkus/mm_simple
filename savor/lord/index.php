<?php /**
 * * @package    MightMedia TVS
 * * @author     Coders <www.coders.lt>
 * * @author     dewdrop <www.dewdrop.lt>
 * * @copyright  2008-2014 Mightmedia Team
 * * @license    GNU General Public License v2
 * * @version    v1.7
 * * @link       http://mightmedia.org
 * */

ob_start();
//head info
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
//session start
if (!isset($_SESSION)) {
	session_start();
}

define('ROOT', '../../');
//savor
//configs include
include_once '../config/MAIN.config.php';
//main functions
require_once '../../entrails/control/functions/MAIN.functions.php';
//login functions include
require_once '../../entrails/control/functions/LOGIN.functions.php';
//login to system
require_once '../../entrails/control/LOGIN.php';
//template functions include
include_once 'control/TEMPLATE.functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?=$sHead?>
	</head>
	<body>

		<?php
		if (!empty($strError)) {
			echo $strError;
		}

		if (!isset($_SESSION[SECRET]['id'])) {

			include_once 'templates/basic/login.php';

		} elseif (isset($_SESSION[SECRET]['level']) && $_SESSION[SECRET]['level'] == 1) {

			include_once 'templates/basic/index.php';

		}
		?>
	</body>
</html>
<?php ob_end_flush(); ?>
