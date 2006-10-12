<?php
/**
 * EEA-DAMS dams.php
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
 * @abstract	 Manage dams.
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */

require_once 'commons/config.php'; 
require_once 'DataObjects/Public_dams.php';

	
if ($a->getAuth()) {
	
	
	if (isset($_REQUEST["action"]) && isset($_REQUEST["cd"])) {
		$dam = new DataObjects_Public_dams();
		$dam->whereAdd ("NOEEA = '".$_REQUEST["cd"]."'");
		$dam->find(true);
		$dam->x_val = $_REQUEST["x"];
		$dam->y_val = $_REQUEST["y"];
		$dam->x_icold = $_REQUEST["xini"];
		$dam->y_icold = $_REQUEST["yini"];
		$dam->comments = $_REQUEST["comment"];
		
		if ($_REQUEST["x"] != null)					// Unvalid if null
			$dam->valid = 1;
		else
			$dam->valid = 0;

		if ($_REQUEST["is_oncanal"] != null)
			$dam->is_oncanal = 1;
		else 
			$dam->is_oncanal = 0;

		if ($_REQUEST["is_dyke"] != null)
			$dam->is_dyke = 1;
		else 
			$dam->is_dyke = 0;
		


		$dam->update(DB_DATAOBJECT_WHEREADD_ONLY);
		$dam->free();	
		
		$file->log('Dams: '.$_REQUEST["cd"].' validated by '.$_SESSION["LOGIN"]);
	
	}

	$i18nPage = 'dams';
	$smarty = iniI18n ($i18nPage, $smarty, $i18n);
	
	if (isset($_SESSION["damIdsOrdered"])&&isset($_REQUEST["cd"])){
		$smarty->assign('first', $_SESSION["damIdsOrdered"][0]);
		$idx = array_search($_REQUEST["cd"], $_SESSION["damIdsOrdered"]);

		if ($idx>0 && $idx<sizeof($_SESSION["damIdsOrdered"])-1){
			$smarty->assign('next',  $_SESSION["damIdsOrdered"][$idx+1]);
			$smarty->assign('previous', $_SESSION["damIdsOrdered"][$idx-1]);
		}elseif ($idx==0){
			$smarty->assign('next',  $_SESSION["damIdsOrdered"][$idx+1]);
			$smarty->assign('previous', $_REQUEST["cd"]);
		}elseif ($idx==sizeof($_SESSION["damIdsOrdered"])-1){
			$smarty->assign('previous', $_SESSION["damIdsOrdered"][$idx-1]);
			$smarty->assign('next', $_REQUEST["cd"]);
		}else{
			$smarty->assign('next', $_REQUEST["cd"]);
			$smarty->assign('previous', $_REQUEST["cd"]);
		}
		$smarty->assign('last', $_SESSION["damIdsOrdered"][sizeof($_SESSION["damIdsOrdered"])-1]);
	}else{
		$smarty->assign('first', '');
		$smarty->assign('next', '');
		$smarty->assign('previous', '');
		$smarty->assign('last', '');
	}


	//DB_DataObject::debugLevel(5);
	$daml = new DataObjects_Public_dams();

	$smarty->assign('damCountryFilter',	$daml->getCountryList ());
	
	$dam = new DataObjects_Public_User_Dams();
	$urlFilter = '';
	$whereAdd = " where 1=1 ";

	if (isset($_REQUEST["cd"]))
		if ($_REQUEST["cd"]!=''){
			$whereAdd .= " and NOEEA like '%".$_REQUEST["cd"]."%'";	
			//$urlFilter .= '&cd='.$_REQUEST["cd"];
		}
	if (isset($_REQUEST["srcName"])){
		if ($_REQUEST["srcName"]!='')
			$whereAdd .= " and NAME like '%".$_REQUEST["srcName"]."%'";	
			$urlFilter .= '&amp;srcName='.$_REQUEST["srcName"];
		}
	if (isset($_REQUEST["srcCountry"])){
		if ($_REQUEST["srcCountry"]!='')
			$whereAdd .= " and COUNTRY like '%".$_REQUEST["srcCountry"]."%'";	
			$urlFilter .= '&amp;srcCountry='.$_REQUEST["srcCountry"];
		}
	$orderBy = " order by VALID desc, COUNTRY, NAME asc";

	

	if ($_SESSION["ADM"]!='t')
	{	$whereAdd .= " and NOEEA = CD_DAM ";
		$whereAdd .= "and CD_USER = ".$_SESSION["ID"].""; 	// Filter on user logged in if not ADM. Else display all dams
		$daml->query ("Select * ".
			"from $daml->__table a, $dam->__table b ".
			$whereAdd . $orderBy);
	}else{
		$daml->query ("Select * ".
			"from $daml->__table ".
			$whereAdd . $orderBy);
	}
	
	
	if ($daml->N>1){
		
		$_SESSION["urlFilter"] = $urlFilter;
		
		// Dams list if more than one
		$dt = array();
		$damIdsOrdered = array();
		$i = 0;
		while ($daml->fetch()) {
			$tmp = $daml->toArray();
			$dt[$i] = $tmp;
			$damIdsOrdered[$i] = $daml->noeea;
			$i ++;	
		}
		//print_r($dt);
		$_SESSION["damIdsOrdered"] = $damIdsOrdered;
		$smarty->assign('damIdsOrdered', $_SESSION["damIdsOrdered"] );
		$smarty->assign('dt', $dt);
		$smarty->display('dams.tpl');
	}elseif ($daml->N==1){
		// One dam go to validation and process
		$smarty->assign('urlFilter', $_SESSION["urlFilter"]);
		$smarty->assign('damIdsOrdered', $_SESSION["damIdsOrdered"] );
		
		$daml->fetch();
		$daml->get($_REQUEST["cd"]);
		$smarty->assign('dam', 				$daml);
		$smarty->assign('x_val', 			$daml->x_val);
		$smarty->assign('y_val', 			$daml->y_val);
		$smarty->assign('valid', 			$daml->valid);
		$i18nPage = 'dam';
		$smarty = iniI18n ($i18nPage, $smarty, $i18n);
		
		// File with images ...
		if (file_exists (BASEDIR.TOPOPATH.''.strtoupper($daml->noeea).'.png') || file_exists (BASEDIR.TOPOPATH.''.strtolower($daml->noeea).'.png'))
			$smarty->assign('imgTopook',true);
		else
			$smarty->assign('imgTopook',false);
		$smarty->assign('imgTopo', 	WWWDIR.TOPOPATH.''.$daml->noeea.'.png');

		if (file_exists (BASEDIR.SPUDPATH.'clip_'.strtoupper($daml->noeea).'.jpg') || file_exists (BASEDIR.SPUDPATH.'clip_'.strtolower($daml->noeea).'.jpg'))
			$smarty->assign('imgSpudok',true);
		else
			$smarty->assign('imgSpudok',false);
		$smarty->assign('imgSpud', 	WWWDIR.SPUDPATH.'clip_'.strtolower($daml->noeea).'.jpg');

		if (file_exists (BASEDIR.SPANPATH.'clip_'.strtoupper($daml->noeea).'.jpg') || file_exists (BASEDIR.SPANPATH.'clip_'.strtolower($daml->noeea).'.jpg'))
			$smarty->assign('imgSpanok',true);
		else
			$smarty->assign('imgSpanok',false);
		$smarty->assign('imgSpan', 	WWWDIR.SPANPATH.'clip_'.strtolower($daml->noeea).'.jpg');

		if ($googleOn == true)
			$smarty->assign('googleMap', 	$daml->displayGoogleMap());
		else
			$smarty->assign('googleMap', 	null);
			
		$smarty->display('dam.tpl');
	}else{
		// None
		$smarty->assign('dt', null);
		$smarty->display('dams.tpl');
	}

}else{
	$smarty->display('index.tpl');
}
 
?>
