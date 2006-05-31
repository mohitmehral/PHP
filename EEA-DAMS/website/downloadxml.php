<?php
/**
 * EEA-DAMS index.php
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
 * @abstract	 index.
 * @copyright    2005
 * @version    	 1.0
 *
 * 
 */

require_once ('commons/config.php');


require_once 'DataObjects/Public_dams.php';
require_once 'DataObjects/Public_user_dams.php';

$noquote = array("int4", "int2", "float8");

if ($a->getAuth()) {
	$file->log('Download-xml: '.$_SESSION["ID"]);

	if ($_SESSION["ADM"] == 't'){

		$db =& DB::connect(DB);
		if (PEAR::isError($db)) {
  			  die($db->getMessage());
		}

		// Proceed with a query...
		
		if ($_REQUEST["act"]=='dam')
	 	{	$res =& $db->query('SELECT * FROM DAMS order by NOEEA');
			$file = 'dam';
		}elseif ($_REQUEST["act"]=='use')                {
			$res =& $db->query('SELECT * FROM USERS');
                        $file = 'users';
                }else{
			$res =& $db->query('SELECT * FROM USER_DAMS');
			$file = 'damsusers';
		}
		header('Content-type: text/xml;charset=UTF-8');
		header('Content-Disposition: attachment; filename="'.$file.'.xml"');
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
                echo "<dataroot xmlns:od=\"urn:schemas-microsoft-com:officedata\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
";
//xsi:noNamespaceSchemaLocation=\"$file.xsd\"
		// Always check that result is not an error
		if (PEAR::isError($res)) {
   			 die($res->getMessage());
		}
		
   		$types = $db->tableInfo($res);
		
		// there are no more rows
		$i = 0;
		while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
			echo "<$file>\r\n";
			$col = 0;
			// Assuming DB's default fetchmode is DB_FETCHMODE_ORDERED
	   		foreach ($row as $k => $v) {
				echo "<$k>" . htmlspecialchars($v,ENT_NOQUOTES,'UTF-8') . "</$k>\r\n";
				$col++;
                        }
			$i++;
			echo "</$file>\r\n";
		}
		
	}	
}

?>
</dataroot>
