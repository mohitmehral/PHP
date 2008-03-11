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

if ($a->getAuth()) {
	$file->log('Download-xsd: '.$_SESSION["ID"]);

	if ($_SESSION["ADM"] == 't'){

		$db =& DB::connect(DB);
		if (PEAR::isError($db)) {
  			  die($db->getMessage());
		}

		// Proceed with a query...
		
		if ($_REQUEST["act"]=='dam')
	 	{	$res =& $db->query('SELECT * FROM DAMS order by NOEEA LIMIT 0');
			$file = 'dam';
		}elseif ($_REQUEST["act"]=='use')                {
			$res =& $db->query('SELECT * FROM USERS LIMIT 0');
                        $file = 'users';
                }else{
			$res =& $db->query('SELECT * FROM USER_DAMS LIMIT 0');
			$file = 'damsusers';
		}
		header('Content-type: text/xml;charset=UTF-8');
		header('Content-Disposition: attachment; filename="'.$file.'.xsd"');
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
                echo "<xsd:schema xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:od=\"urn:schemas-microsoft-com:officedata\">
<xsd:element name=\"dataroot\">
<xsd:complexType>
<xsd:sequence>
<xsd:element ref=\"$file\" minOccurs=\"0\" maxOccurs=\"unbounded\"/>
</xsd:sequence>
<xsd:attribute name=\"generated\" type=\"xsd:dateTime\"/>
</xsd:complexType>
</xsd:element>
<xsd:element name=\"$file\">
<xsd:complexType>
<xsd:sequence>
";
		// Always check that result is not an error
		if (PEAR::isError($res)) {
   			 die($res->getMessage());
		}
		
   		$types = $db->tableInfo($res);
		
                foreach ($types as $k => $v) {
                    echo "<xsd:element name=\"$v[name]\" minOccurs=\"1\" maxOccurs=\"1\" ";
                    switch($v[type]) {
                    case "float8":
                        echo "od:jetType=\"double\" od:sqlSType=\"float\" type=\"xsd:double\"";
                        break;
                    case "int2":
                        echo "od:jetType=\"integer\" od:sqlSType=\"smallint\" type=\"xsd:short\"";
                        break;
                    case "int4":
                        echo "od:jetType=\"longinteger\" od:sqlSType=\"int\" type=\"xsd:int\"";
                        break;
                    case "bool":
                        echo "od:jetType=\"yesno\" od:sqlSType=\"bit\" type=\"xsd:boolean\"";
                        break;
                    case "varchar":
                        echo "od:jetType=\"text\" od:sqlSType=\"nvarchar\" type=\"xsd:string\"";
                        break;
                    default:
                        echo "od:jetType=\"text\" od:sqlSType=\"nvarchar\" type=\"xsd:string\"";
                        break;
                    }
                    echo "/>\n";
                }
	}	
}

?>
</xsd:sequence>
</xsd:complexType>
</xsd:element>
</xsd:schema>
