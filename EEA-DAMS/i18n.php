<?php
/**
 * EEA-DAMS i18n.php
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
 * @abstract	 i18n manager.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
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
