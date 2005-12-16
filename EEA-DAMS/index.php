<?php
/**
 * EEA-DAMS index.php
 *
 * Dam exact position (expressed as geographical coordinates) is generally unavailable. 
 * A special process has been developed by the EEA (AIR3) to locate as 
 * accurately as possible the dams listed in the Icold register on large 
 * dams. This pre-location task is carried out by ETC/TE. The current 
 * number of large dams is ~6000 in the EEA area.
 * Following agreement with Icold, the national focal points of Icold 
 * will be requested to accept / correct the proposed location. These 
 * organisations are based in countries and know accurately where dams are, 
 * even though they do not systematically have the coordinates at their 
 * disposal. To check / correct the position, it has been considered that 
 * an Internet tool, providing an image of the most likely position 
 * and a facility to fix a new position by drag-and-drop a marker on the 
 * image would be the best solution from the point of view of minimizing 
 * the burden, avoiding copyright issues nevertheless ensuring security in 
 * transactions.
 * This arrangement follows the methodology of pre-positioning and 
 * positioning dams developed by AIR3 and delivered to the ETC/TE that 
 * has to carry out this work.
 * 
 *
 * @abstract	 index.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */

require_once ('commons/config.php');

$i18nPage = 'home';
$smarty = iniI18n ($i18nPage, $smarty, $i18n);

require_once 'DataObjects/Public_dams.php';
require_once 'DataObjects/Public_user_dams.php';

if ($a->getAuth()) {
	$file->log('Connected: '.$_SESSION["ID"]);

	if ($_SESSION["ADM"] == 't'){
		// Maps by country access for ADM only
		$do = new DataObjects_Public_Dams();
		$smarty->assign('damCountryFilter',	$do->getCountryList ());
		$smarty->assign('map', null);
		$do->free();
	}else{
		$do = new DataObjects_Public_Dams();				// Loading dam countries
		$smarty->assign('damCountryFilter',	$do->getCountryList ());
		$do->free();		

		$do = new DataObjects_Public_User_Dams();
		$do->whereAdd("CD_USER = ".$_SESSION["ID"].""); 		// Filter on user
		$nb = $do->find();
		$userDams = array();
		
		$i = 0;
		$map = displayGoogleMapHead ();
		while ($do->fetch()) {
			$do->getLinks();
			$map .= googleMarker (	($do->_cd_dam->x_val?$do->_cd_dam->x_val:$do->_cd_dam->x_icold), 
							($do->_cd_dam->y_val?$do->_cd_dam->y_val:$do->_cd_dam->y_icold), 
							$do->_cd_dam->noeea, 
							$do->_cd_dam->name, 
							($do->_cd_dam->y_val&&$do->_cd_dam->x_val?VALIDICON:ICOLDICON)
						);
		}
		$map .= displayGoogleMapFoot ();
		$smarty->assign('map', $map);
		$do->free();
		
	}	
}

$smarty->display('index.tpl');


?>
