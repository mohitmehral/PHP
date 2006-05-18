<?php
/**
 * EEA-DAMS login.php
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
 * @abstract	 Login functions.
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */
 
require_once "Auth.php";
require_once 'DataObjects/Public_users.php';
require_once 'DataObjects/Public_metadata.php';

/**
 * 
 * Not used
 * 
 */
function loginFunction()
{
}

$a = new Auth('DB',  $paramsLogin, 'loginFunction', true);
$a->start();
$ADM = false;

if ($a->getAuth()) {
   if (isset($_GET["act"])) {
	  if($_GET["act"] == 'logout') {
	    $a->logout();										// Log user out
	  } 
  }else {  	
  	// Check lock on db is on / off
  	$doc = new DataObjects_Public_metadata();					// Get user info
	$doc->whereAdd("CODE = 'lock'");
	$doc->find(true);
  	if ($doc->value=='on')
  	{	$smarty->assign("loginfailed", "EEA-DAMS database is in maintenance. Try to connect later. Thanks for your comprehension.");
	    	$a->logout();
	}else{
		$do = new DataObjects_Public_Users();					// Get user info
		$do->whereAdd("LOGIN = '".$a->getUsername()."'");
		$do->find(true);
	  	
	  	$smarty->assign("useracc",		$do->firstname." ".$do->surname);	// Template ini for user
	    $smarty->assign("roleAdm",		$do->roleadm);			// General ADM role for current user
	   
		// Settings sessions variables 
		$_SESSION["ADM"] = $do->roleadm;
	  	$_SESSION["ID"] = $do->id;
	  	$_SESSION["LOGIN"] = $do->login;
	  	
	    $DAMnumber = $do->getDamNumber ();						// User statistics
	    $smarty->assign("userDamNumber", $DAMnumber);
	  
	    $smarty->assign("mnuUserId", 	$do->id);
	    $smarty->assign("loginfailed",  false);
	    $do->free();
	    
     }
     $doc->free();
   }
} else {
	// not authenticated - move to home page
    $smarty->assign("loginfailed", $i18n->get('notauthenticated', 'all'));        
} 

$smarty->assign("login",$a->getAuth());

?>
