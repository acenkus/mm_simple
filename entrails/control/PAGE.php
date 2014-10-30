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
 
//pages
//page id check
if ( isset( $url['id'] ) && !empty( $url['id'] ) && isnum( $url['id'] ) ) {
	$pslid = (int)$url['id'];
} else {
	$pslid            = $conf['puslapiai'][$conf['pirminis'] . '.php']['id'];
	$sPage             = $sDirPages . $conf['pirminis'];
	$sPageName = $conf['puslapiai'][$conf['pirminis'] . '.php']['pavadinimas'];
	$_GET['id']       = $pslid;
	$url['id']        = $pslid;
}

//if  page id is more than 0
if ( isset( $pslid ) && isnum( $pslid ) && $pslid > 0 ) {
	//select page info from table
	$aSqlPage = $dbCon->query( "SELECT * FROM `" . dbPrefix . "pages` WHERE `id` = " . escape( (int)$pslid ) . " and `lang` = " . escape( lang() ) . " LIMIT 1" );
	$aSqlP = $aSqlPage->fetch(PDO::FETCH_ASSOC);
	//page check
	if ( !empty( $aSqlP ) ) {
		
		if ( !preg_match( "/\.php$/i", $aSqlP['file'] ) ) {
			header( "Location:{$aSqlP['file']}" );
			exit;
		}
			
		if ( puslapis( $aSqlP['file'] ) ) {
			$sPage             = $sDirPages . basename( $aSqlP['file'], '.php' );
			$sPageName = $aSqlP['pavadinimas'];
		} else {
			$sPage             = $sDirPages.'error';
			$sPageName = '404 - ' . $lang['pages']['NotFound'] . '';
		}
		
		if ( !file_exists( $sPage . '.php' ) ) {
			$sPage             = $sDirPages.'error';;
			$sPageName = '404 - ' . $lang['pages']['NotFound'] . '';
		}
		
	} else {
		$sPage             = $sDirPages.'error';;
		$sPageName = '404 - ' . $lang['pages']['NotFound'] . '';
	}
	
}

?>