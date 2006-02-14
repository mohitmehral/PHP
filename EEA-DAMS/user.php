<?php
/**
 * EEA-DAMS user.php
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
 * @abstract	 Manage user properties.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
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
