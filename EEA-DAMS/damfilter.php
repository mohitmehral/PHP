<?php
/**
 * EEA-DAMS damfilter.php
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
 * @abstract	 Used for Ajax call.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
 * @copyright    2005
 * @version    	 1.0
 * @todo 		 Authentication
 * 
 */


error_reporting (E_ALL);

require_once 'commons/inc-config.php';
require_once 'PEAR.php';
require_once 'DB/DataObject.php';


define ('DB',DB_TYPE.'://'.DB_USER.':'.DB_PASS.'@'.DB_SERVER.'/'.DB_NAME);

$options = &PEAR::getStaticProperty('DB_DataObject','options');
$options = array(
    'database'         => DB,
    'schema_location'  => SCHEMADIR,
    'class_location'   => SCHEMADIR,
    'require_prefix'   => 'DataObjects/',
    'class_prefix'     => 'DataObjects_Public'
);
 
require_once 'DataObjects/Public_dams.php';
	
//if ($a->getAuth()) {
	$dam = new DataObjects_Public_dams();
	if (isset($_GET["cd"]))
		if ($_GET["cd"]!='')
			$dam->whereAdd("NOEEA like '%".$_GET["cd"]."%'");	
	if (isset($_GET["srcName"]))
		if ($_GET["srcName"]!='')
			$dam->whereAdd("NAME like '%".$_GET["srcName"]."%'");	
	if (isset($_GET["srcCountry"]))
		if ($_GET["srcCountry"]!='')
			$dam->whereAdd("COUNTRY like '%".$_GET["srcCountry"]."%'");	
	$dam->orderBy ("SCORE");
	$dam->orderBy ("COUNTRY");
	$dam->orderBy ("NAME");
	$nb = $dam->find();
	
	//header('Content-type:text/xml;charset:UTF-8');
	header('Content-type:text/xml;charset:ISO-8859-1');
	//echo '<'.'?xml version="1.0" encoding="UTF-8"?'.">\n";

	if ($nb>0){
		echo '<data>';
		while ($dam->fetch()) {
			printf ('<dam noeea="%s" name="%s"/>',$dam->noeea, $dam->name);
		}
		echo '</data>';
	}else{
		echo '<data/>';
	}
//}
?>
