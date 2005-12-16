<?php
/**
 * EEA-DAMS login.php
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
 * @abstract	 Login functions.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */
 
require_once "Auth.php";
require_once 'DataObjects/Public_users.php';


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
} else {
	// not authenticated - move to home page
    $smarty->assign("loginfailed", $i18n->get('notauthenticated', 'all'));        
} 

$smarty->assign("login",$a->getAuth());

?>
