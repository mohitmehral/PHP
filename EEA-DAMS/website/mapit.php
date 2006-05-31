<?php
/**
 * EEA-DAMS mapit.php
 *
 * The contents of this file are subject to the Mozilla Public
 * License Version 1.1 (the "License"); you may not use this file
 * except in compliance with the License. You may obtain a copy of
 * the License at http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS
 * IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * rights and limitations under the License.
 *
 * The Original Code is "EEA-DAMS version 1.0".
 *
 * The Initial Owner of the Original Code is European Environment
 * Agency.  Portions created by I.O.Water are
 * Copyright (C) European Environment Agency.  All
 * Rights Reserved.
 *
 * Contributor(s):
 *  Original Code: François-Xavier Prunayre, I.O.Water <fx.prunayre@oieau.fr>
 *
 *
 * @abstract	 Make a google map for a country.
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */


require_once ('commons/config.php');

require_once 'DataObjects/Public_dams.php';

if ($a->getAuth()) {
	
$do = new DataObjects_Public_User_Dams();

	$daml = new DataObjects_Public_dams();
	$smarty->assign('damCountryFilter',	$daml->getCountryList ());
	
	if (isset($_REQUEST["country"]))
			$daml->whereAdd ("COUNTRY = '".$_REQUEST["country"]."'");	
	
	$daml->find();
	$i = 0;
	$damIdsOrdered = array();

	$map = displayGoogleMapHead (	$daml->countryCoord[$_REQUEST["country"]]["X"], 
						$daml->countryCoord[$_REQUEST["country"]]["Y"], 
						$daml->countryCoord[$_REQUEST["country"]]["Z"]);
	while ($daml->fetch()) {
		$map .= googleMarker (	($daml->x_val?$daml->x_val:$daml->x_icold), 
							($daml->y_val?$daml->y_val:$daml->y_icold), 
							$daml->noeea, 
							$daml->name, 
							($daml->y_val&&$daml->x_val?VALIDICON:ICOLDICON)
						);
		$damIdsOrdered[$i] = $daml->noeea;
		$i ++;	

//googleMarker ($daml->x_icold, $daml->y_icold, $daml->noeea, $daml->name);
	}
	
	$_SESSION["damIdsOrdered"] = $damIdsOrdered;
		
	$daml->free();

	$map .= displayGoogleMapFoot ();
	$smarty->assign('map', $map);

	$smarty->display('mapit.tpl');

}else
	$smarty->display('index.tpl');





?>
