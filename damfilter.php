<?php
/**
 * EEA-DAMS damfilter.php
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
 * @abstract	 Used for Ajax call.
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
