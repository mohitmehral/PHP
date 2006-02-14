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

$noquote = array("int4", "int2", "float8");

if ($a->getAuth()) {
	Define(CSV,"\t");

	$file->log('Download: '.$_SESSION["ID"]);

	if ($_SESSION["ADM"] == 't'){

		$db =& DB::connect(DB);
		if (PEAR::isError($db)) {
  			  die($db->getMessage());
		}

		// Proceed with a query...
		
		if ($_REQUEST["act"]=='dam')
	 	{	$res =& $db->query('SELECT * FROM DAMS order by NOEEA');
			$file = 'dam.txt';
		}elseif ($_REQUEST["act"]=='use')                {
			$res =& $db->query('SELECT * FROM USERS');
                        $file = 'users.txt';
                }else{
			$res =& $db->query('SELECT * FROM USER_DAMS');
			$file = 'damsusers.txt';
		}
		header('Content-type: text/plain;charset=UTF-8');
		header('Content-Disposition: attachment; filename="'.$file.'"');
		// Always check that result is not an error
		if (PEAR::isError($res)) {
   			 die($res->getMessage());
		}
		
   		$types = $db->tableInfo($res);
		
		// there are no more rows
		$i = 0;
		while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
    			$head = '';
			$line = '';
			if ($i==0) {
				foreach ($row as $k => $v) {
					echo $k.CSV;
				}
				echo "\r\n";
			}
			$col = 0;
			// Assuming DB's default fetchmode is DB_FETCHMODE_ORDERED
	   		foreach ($row as $k => $v) {
				if (in_array($types[$col]['type'],$noquote) || $v =='')
					echo $v.CSV;
				else
					echo '"' . str_replace('"', '\"', $v) . '"'.CSV;
				$col++;
                        }
			echo "\r\n";
			$i++;
		}
//		echo '<script language="javascript">location.replace("'.$file.'")</script>';
		
	}	
}

?>
