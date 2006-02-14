<?php
/**
 * EEA-DAMS index.php
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
 * @abstract	 index.
 * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
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
                    echo "<xsd:element name=\"$v[name]\" minOccurs=\"1\" maxOccurs=\"1\"";
                    switch($v[type]) {
                    case "float8":
                        echo "od:jetType=\"double\" od:sqlSType=\"float\" type=\"xsd:double\"";
                        break;
                    case "int2":
                        echo "od:jetType=\"integer\" od:sqlSType=\"smallint\" type=\"xsd:short\"";
                        break;
                    case "int4":
                        echo "od:jetType=\"integer\" od:sqlSType=\"smallint\" type=\"xsd:short\"";
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
