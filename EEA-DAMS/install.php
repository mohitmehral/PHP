<?php
/**
 * EEA-DAMS install.php
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
 * @abstract	 install.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */
 
error_reporting (E_ALL);

require_once 'Config.php';
require_once 'PEAR.php';
require_once 'DB.php';

$file 		= 'commons\\inc-config.conf-dist';
$userfile 	= 'commons/inc-config.conf';
$currentdir = dirname($_SERVER["PATH_TRANSLATED"]).'/';
$conf 		= $currentdir.$userfile;
echo $conf;

/* Check if file exist */


/* Load conf */
$c 		= new Config();
$root 	= $c->parseConfig($conf, "IniFile");
$mod 	=& $root->getItem("section", "SETTINGS");
$path 	=& $root->getItem("section", "PATH");
$map 	=& $root->getItem("section", "MAP");
$db 	=& $root->getItem("section", "DB");

$title 			= & $mod->getItem("directive", "TITLE");
echo $title->getContent();
$pathbasedir 	= & $path->getItem("directive", "BASEDIR"); 	// Should be ini with basedir
$pathwwwdir 	= & $path->getItem("directive", "WWWDIR"); 		//eeadams
$pathtpldir 	= & $path->getItem("directive", "TPLDIR"); 		//templates/
$pathschemadir 	= & $path->getItem("directive", "SCHEMADIR"); 	//DataObjects/

$pathtopo 		= & $map->getItem("directive", "TOPOPATH");
$pathspud 		= & $map->getItem("directive", "SPUDPATH");
$pathspan 		= & $map->getItem("directive", "SPANPATH");

$googlekey 		= & $map->getItem("directive", "GOOGLEKEY");
$googleiconicold= & $map->getItem("directive", "ICOLDICON");
$googleiconvalid= & $map->getItem("directive", "VALIDICON");
$googleiconeea	= & $map->getItem("directive", "EEAICON");

$dbtype 		= & $db->getItem("directive", "DB_TYPE");
$dbuser 		= & $db->getItem("directive", "DB_USER");
$dbpass 		= & $db->getItem("directive", "DB_PASS");
$dbname 		= & $db->getItem("directive", "DB_NAME");
$dbserver 		= & $db->getItem("directive", "DB_SERVER");

/* Save conf if needed */
if (isset($_REQUEST["action"])){
	if ($_REQUEST["action"]=='save'){
		
		$title->setContent($_REQUEST['TITLE']);
		
		$pathbasedir->setContent($_REQUEST['BASEDIR']);
		$pathwwwdir->setContent($_REQUEST['WWWDIR']);
		$pathtpldir->setContent($_REQUEST['TPLDIR']);
		$pathschemadir->setContent($_REQUEST['SCHEMADIR']);
		
		$pathtopo->setContent($_REQUEST['TOPOPATH']);
		$pathspud->setContent($_REQUEST['SPUDPATH']);
		$pathspan->setContent($_REQUEST['SPANPATH']);
		$googlekey->setContent($_REQUEST['GOOGLEKEY']);
		$googleiconicold->setContent($_REQUEST['ICOLDICON']);
		$googleiconvalid->setContent($_REQUEST['VALIDICON']);
		$googleiconeea->setContent($_REQUEST['EEAICON']);
		
		$dbtype->setContent($_REQUEST['DB_TYPE']);
		$dbuser->setContent($_REQUEST['DB_USER']);
		$dbpass->setContent($_REQUEST['DB_PASS']);
		$dbname->setContent($_REQUEST['DB_NAME']);
		$dbserver->setContent($_REQUEST['DB_SERVER']);
		
		$c->writeConfig();
	}
}

echo 'param';
/* Conf form to edit */
?>
<form action="#" method="POST">
	<input type='hidden' name='action' value='save'/>
	<fieldset>
		<legend>General settings</legend>
		Title : <input type='text' name='TITLE' value='<?php echo $title->getContent(); ?>'/><br/>
		
	</fieldset>
	<fieldset>
		<legend>Database connection</legend>
		DB Type : <input type='text' name='DB_TYPE' value='<?php echo $dbtype->getContent(); ?>'/><br/>
		DB User : <input type='text' name='DB_USER' value='<?php echo $dbuser->getContent(); ?>'/><br/>
		DB Password : <input type='text' name='DB_PASS' value='<?php echo $dbpass->getContent(); ?>'/><br/>
		DB Name : <input type='text' name='DB_NAME' value='<?php echo $dbname->getContent(); ?>'/><br/>
		DB Server : <input type='text' name='DB_SERVER' value='<?php echo $dbserver->getContent(); ?>'/><br/>
	</fieldset>
	<fieldset>
		<legend>Directory settings</legend>
		Module directory <input type='text' name='BASEDIR' value='<?php echo $pathbasedir->getContent(); ?>'/><br/>
		Web directory (or alias) <input type='text' name='WWWDIR' value='<?php echo $pathwwwdir->getContent(); ?>'/><br/>
		Templates directory <input type='text' name='TPLDIR' value='<?php echo $pathtpldir->getContent(); ?>'/><br/>
		Schema directory <input type='text' name='SCHEMADIR' value='<?php echo $pathschemadir->getContent(); ?>'/><br/>
	</fieldset>
	<fieldset>
		<legend>Maps settings</legend>
		<fieldset>
		<legend>Google maps settings</legend>
		Key : <input type='text' name='GOOGLEKEY' value='<?php echo $googlekey->getContent(); ?>'/><br/>
		Icon ICOLD : <input type='text' name='ICOLDICON' value='<?php echo $googleiconicold->getContent(); ?>'/><br/>
		Icon EEA : <input type='text' name='EEAICON' value='<?php echo $googleiconeea->getContent(); ?>'/><br/>
		Icon VALID : <input type='text' name='VALIDICON' value='<?php echo $googleiconvalid->getContent(); ?>'/><br/>
		
		</fieldset>
		<fieldset>
		<legend>Overview maps</legend>
		Image 2000 (large scale) maps : <input type='text' name='SPUDPATH' value='<?php echo $pathspud->getContent(); ?>'/><br/>
		Image 2000 (small scale) maps : <input type='text' name='SPANPATH' value='<?php echo $pathspan->getContent(); ?>'/><br/>
		Topographics maps : <input type='text' name='TOPOPATH' value='<?php echo $pathtopo->getContent(); ?>'/><br/>
		</fieldset>
		
	</fieldset>
	<input type='submit'>
</form>

  
		<?php
		// DB ok or not
		define ('DB',$dbtype->getContent().'://'.$dbuser->getContent().':'.$dbpass->getContent().'@'.$dbserver->getContent().'/'.$dbname->getContent());
		$db =& DB::connect(DB);
		
		if (PEAR::isError($db)) 
		    die($db->getMessage());
		else
			echo "Database connection ok.<br/>";
	

		/*if ((include 'Config.php') == 'OK') {
		   echo 'Pear / Config package ok';
		}
		if ((include 'Log.php') == 'OK') {
		   echo 'Pear / Log package ok';
		}*/
		
?>
