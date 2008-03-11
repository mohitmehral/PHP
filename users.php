<?php
/**
 * EEA-DAMS users.php
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
 * @abstract	 Manage all users.
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */

 
require_once 'commons/config.php'; 
require_once 'DataObjects/Public_users.php';

if ($a->getAuth()) {
	$i18nPage = 'user';
	$smarty = iniI18n ($i18nPage, $smarty, $i18n);
	
	$do = new DataObjects_Public_Users();
	$do->orderBy ("LOGIN");
	$do->find();
	$userList = array ();
	$i = 0;
	while ($do->fetch()) {
		$tmp = $do->toArray();
		$userList[$i++] = $tmp;
	}

	$smarty->assign('users', $userList);
	
	$smarty->display('users.tpl'); 
}else{
	$smarty->display('index.tpl');
}
?>
