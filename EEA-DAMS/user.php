<?php
/**
 * EEA-DAMS user.php
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
 * @abstract	 Manage user properties.
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */
 
require_once 'commons/config.php'; 
require_once 'DataObjects/Public_users.php';
require_once 'DataObjects/Public_dams.php';
require_once 'DataObjects/Public_user_dams.php';

// TODO : error SQL


if ($a->getAuth()) {
	$i18nPage = 'user';
	$smarty = iniI18n ($i18nPage, $smarty, $i18n);

	if (isset($_REQUEST["action"])){
		$smarty->assign('action', 		$_REQUEST["action"]);
			
		// Dam for user management
		if ($_REQUEST["action"]=='savedams'){
			
			// Remove all curent dams for this user
			$do = new DataObjects_Public_User_Dams();
			$do->whereAdd("cd_user = ".$_REQUEST["id"]."");
			$do->delete(DB_DATAOBJECT_WHEREADD_ONLY);	
			$do->free();
			
			// Add all new dams for this user - TODO : unique
			if (isset($_REQUEST["listselect"])){
				$do = new DataObjects_Public_User_Dams();
				$lst = $_REQUEST["listselect"];
				for ($i=0; $i<count($lst ); $i++) { 
					$do->cd_dam = $lst [$i];
					$do->cd_user = $_REQUEST["id"];
					//echo $lst [$i]."- ". $_REQUEST["id"];
					$do->insert();
					/*if (PEAR::isError($do->insert()))
						echo "error";*/
				}
				$do->free();
			}
			$do = new DataObjects_Public_Users();
			$do->whereAdd("cd_user = ".$_REQUEST["id"]."");
			$do->find(true);
			$smarty->assign('user', 		$do);
			$do->free();
		}elseif ($_REQUEST["action"]=='new'){ 
			$smarty->assign('user',		 		null);
		}elseif ($_REQUEST["action"]=='cre'){
			$do = new DataObjects_Public_Users();
			$do->id			= $do->getNextId();
			$_REQUEST["id"] = $do->id;
			$do->login 		= $_REQUEST["userlogin"];
			$do->firstname 	= $_REQUEST["userfirstname"];
			$do->surname 	= $_REQUEST["usersurname"];
			$do->phone 		= $_REQUEST["userphone"];
			$do->address 	= $_REQUEST["useraddress"];
			$do->email 		= $_REQUEST["useremail"];
			$do->roleadm 	= (isset($_REQUEST["userroleadm"])?1:0);
			//$do->rolelang 	= (isset($_REQUEST["userrolelang"]) && $_REQUEST["userrolelang"]=='on'?true:false);
			$do->roledam 	= (isset($_REQUEST["userroledam"])?1:0);
			
			if ($_REQUEST["userpassword1"]!='')
				$do->password = md5($_REQUEST["userpassword1"]); // TODO check 1 eq 2
			
			$do->insert();
			$smarty->assign('user', 		$do);
			$isADM = $do->roleadm;
			$do->free();


		}elseif ($_REQUEST["action"]=='sav'){
			$do = new DataObjects_Public_Users();
			$do->whereAdd("ID = ".$_REQUEST["id"]."");
			$do->find(true);
			$do->login 		= $_REQUEST["userlogin"];
			$do->firstname 	= $_REQUEST["userfirstname"];
			$do->surname 	= $_REQUEST["usersurname"];
			$do->phone 		= $_REQUEST["userphone"];
			$do->address 	= $_REQUEST["useraddress"];
			$do->email 		= $_REQUEST["useremail"];
			$do->roleadm 	= (isset($_REQUEST["userroleadm"])?1:0);
			$do->roledam 	= (isset($_REQUEST["userroledam"])?1:0);
			
			if ($_REQUEST["userpassword1"]!='')
				$do->password = md5($_REQUEST["userpassword1"]); // TODO check 1 eq 2
			
		    $do->update(DB_DATAOBJECT_WHEREADD_ONLY);
			$smarty->assign('user', 			$do);
			$isADM = $do->roleadm;
			$do->free();
			
			
		}elseif ($_REQUEST["action"]=='upd'){
			$do = new DataObjects_Public_Users();
			$do->whereAdd("ID = ".$_REQUEST["id"]."");
			$do->find(true);
			$smarty->assign('user', 			$do);
			$isADM = $do->roleadm;
			$do->free();
			
			
		}if ($_REQUEST["action"]=='del'){
			// Remove all curent dams for this user
			$do = new DataObjects_Public_User_Dams();
			$do->whereAdd("cd_user = ".$_REQUEST["id"]."");
			$do->delete(DB_DATAOBJECT_WHEREADD_ONLY);	
			$do->free();

			// Remove user
			$do = new DataObjects_Public_Users();
			$do->whereAdd("ID = ".$_REQUEST["id"]."");
			$smarty->assign('user', 		$do);
			$do->delete(DB_DATAOBJECT_WHEREADD_ONLY);	
			$do->free();
		}



		/* User dam manager  */
		if ($_REQUEST["action"]!='new'){ 
			$do = new DataObjects_Public_User_Dams();
			$do->whereAdd("CD_USER = ".$_REQUEST["id"].""); 	// Filter on user
			$do->orderBy("CD_DAM");
			$nb = $do->find();
			
			$userDams = array();
			$i = 0;

			while ($do->fetch()) {
				$do->getLinks();
				$userDams[$i++] = array("noeea" => $do->_cd_dam->noeea,
										"name" => $do->_cd_dam->name);
			}
			$smarty->assign('userDams', $userDams);
			$do->free();
				
	
			if ($_SESSION["ADM"]) // Search for dam only if user is ADM
			{
				$dam = new DataObjects_Public_dams();
			
				$smarty->assign('damCountryFilter',	$dam->getCountryList ());
/*
				$dam->orderBy ("COUNTRY");
				$dam->orderBy ("NAME");
				$nb = $dam->find(true);
				$allDams = array();
				$i = 0;
				while ($dam->fetch()) 
					$allDams[$i++] = array("noeea" => $dam->noeea,"name" => $dam->name);		
				$smarty->assign('allDams', $allDams);
				$dam->free();*/
				$smarty->assign('allDams', null);
			}
		}
		$smarty->display('user.tpl');
	}else  
		$smarty->display('index.tpl');
}else{
	$smarty->display('index.tpl');
}
?>
