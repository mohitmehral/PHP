<?php

require_once 'Config.php';

$conf 	= 'commons/inc-config.conf';

/* Load conf */
$c 		= new Config();
$root 	=$c->parseConfig($conf, "IniFile");
$mod 	=$root->getItem("section", "SETTINGS");
$path 	=$root->getItem("section", "PATH");
$map 	=$root->getItem("section", "MAP");
$db 	=$root->getItem("section", "DB");

$title 			= & $mod->getItem("directive", "TITLE");

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
$googleiconnearby = & $map->getItem("directive", "NEARBYICON");

$dbtype 		= & $db->getItem("directive", "DB_TYPE");
$dbuser 		= & $db->getItem("directive", "DB_USER");
$dbpass 		= & $db->getItem("directive", "DB_PASS");
$dbname 		= & $db->getItem("directive", "DB_NAME");
$dbserver 		= & $db->getItem("directive", "DB_SERVER");

define ('BASEDIR', 	$pathbasedir->getContent());
define ('WWWDIR', 	$pathwwwdir->getContent() );
define ('TPLDIR', 	$pathbasedir->getContent().$pathtpldir->getContent());
define ('SCHEMADIR',$pathbasedir->getContent().$pathschemadir->getContent());
define ('TOPOPATH', $pathtopo->getContent());
define ('SPUDPATH', $pathspud->getContent());
define ('SPANPATH', $pathspan->getContent());
define ('TITLE', 	$title->getContent());
define ('DB_TYPE',	$dbtype->getContent());
define ('DB_USER',	$dbuser->getContent());
define ('DB_PASS',	$dbpass->getContent());
define ('DB_NAME',	$dbname->getContent());
define ('DB_SERVER',$dbserver->getContent());


define ('ICOLDICON',$googleiconicold->getContent());
define ('VALIDICON',$googleiconvalid->getContent());
define ('EEAICON',	$googleiconeea->getContent());
define ('NEARBYICON',  $googleiconnearby->getContent());

define ('GOOGLEMAPKEY',	$googlekey->getContent());


?>
