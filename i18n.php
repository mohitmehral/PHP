<?php
/**
 * EEA-DAMS i18n.php
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
 * @abstract	 i18n manager.
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */

require_once 'commons/config.php';
require_once 'DataObjects/Public_i18n.php';


if ($a->getAuth()) {


	if (isset($_REQUEST["action"]))	{
		
		$file->log('i18n: '.$_REQUEST["action"].' done by '.$_SESSION["LOGIN"]);
		if ($_REQUEST["action"]=="insert")	{
			// Insert a new term
			//DB_DataObject::debuglevel(5);
			$do = new DataObjects_Public_i18n();
			
			foreach ($_REQUEST as $k => $v) {
				if ($k != 'action')
				$do->$k		= $v;
				//echo $k .":". $v;
			}
			$do->insert();
			$do->free();
		}elseif ($_REQUEST["action"]=="update" && isset ($_REQUEST["id"]))	{
			// Update one
			$do = new DataObjects_Public_i18n();
			//DB_DataObject::debuglevel (5);
			$do->whereAdd("ID='".$_REQUEST["id"]."'");
			foreach ($_REQUEST as $k => $v) {
				if ($k != 'action')
					$do->$k		= $v;
			}
			$do->update(DB_DATAOBJECT_WHEREADD_ONLY);
			$do->free();
		}elseif ($_REQUEST["action"]=="delete"  && isset ($_REQUEST["id"]))	{
			// Delete one
			//DB_DataObject::debuglevel (5);

			$do = new DataObjects_Public_i18n();
			$do->whereAdd("ID='".$_REQUEST["id"]."'");
			$do->delete(DB_DATAOBJECT_WHEREADD_ONLY);
			$do->free();
		}
	}

	$do = new DataObjects_Public_i18n();
	//$do->orderBy("ID");
	
	if (isset($_REQUEST["listselect"]))			// Get langs based on i18n conf or user demand
		$lst = $_REQUEST["listselect"];
	else
		$lst = $i18n->getLangs('ids');	
	
	$do->selectAdd();
	$lang = '';
	for ($i=0; $i<count($lst ); $i++) { 
		$lang .= ','.$lst [$i];
	}
	$do->selectAdd('PAGE_ID, ID'.$lang);		
	$do->orderBy ('ID');
	$do->find();	
	$termList = array();
	$i = 0;
	while ($do->fetch()) {
		$tmp["page_id"] = $do->page_id;
		$tmp["id"] = $do->id;
		for ($k=0; $k<count($lst ); $k++)  
			$tmp[$lst [$k]] = $do->$lst [$k];
		$termList[$i++] = $tmp;
	}
	$do->free();

	$smarty->assign('selectedLang', $lst);
	$smarty->assign('terms', $termList);
	$smarty->display('i18n.tpl'); 
}else{
	$smarty->display('index.tpl');
}

?>
