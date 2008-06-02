<?php

/* 

BWD water quality data/map viewer: EXPORT TO KML FILE

21.3.2008; first version
29.5.2008; changed from array to echo because of PHP memory limit error

*/

include('config.php');
include('functions.php');


if($_GET['cc'] != '')   {

// connect ta database
$db = mysql_connect($host, $dbuser,$dbpass);
mysql_select_db($database,$db);
// 5.5.2008; utf8: this must be included fot utf-8 charset
mysql_query("SET NAMES 'utf8'");

// array with fields to show in kml
$array_fields = array(
  'Id','Latitude','Longitude','Country','Region','Province','Commune','Bathing water','Type',
  //'y1990','y1991','y1992','y1993','y1994','y1995','y1996','y1997','y1998','y1999',
  'y2000','y2001','y2002','y2003','y2004','y2005','y2006','y2007');

$sql = "
  SELECT 
	UPPER(c.Country) AS Country,
	s.numind AS Id, s.latitude AS Latitude, s.longitude AS Longitude, s.WaterType AS Type, s.Region, s.Province, s.Commune, s.Prelev AS 'Bathing water', 
	#s.y1990, s.y1991, s.y1992, s.y1993, s.y1994, s.y1995, s.y1996, s.y1997, s.y1998, s.y1999, 
	s.y2000, s.y2001, s.y2002, s.y2003, s.y2004, s.y2005, s.y2006, s.y2007 
  FROM bwd_stations s
  LEFT JOIN countrycodes_iso c ON s.cc = c.ISO2 ";

if($_GET['GeoRegion'] != '')		$sql .= " INNER JOIN numind_geographic n USING (numind) ";
if($_GET['cc'] != 'EU27')			$sql .= " WHERE cc = '".$_GET['cc']."' ";
else								$sql .= " WHERE 1 ";

if($_GET['GeoRegion'] != '')		$sql .= " AND geographic = '".$_GET['GeoRegion']."'";

if($_GET['Region'] != '')			$sql .= " AND Region LIKE '".changeChars($_GET['Region'],"%")."'";
if($_GET['Province'] != '')			$sql .= " AND Province LIKE '".changeChars($_GET['Province'],"%")."'";
if($_GET['BathingPlace'] != '')		$sql .= " AND numind = '".$_GET['BathingPlace']."'";



$result = mysql_query($sql) or die($sql."<br>".mysql_error());

// piece of code copied from iz: http://code.google.com/support/bin/answer.py?answer=69906&topic=11364#outputkml
// 29.5.2008; changed on 29.5.2008 - usage of echo instead of array (remark by Søren Roug)

// generates kml file name
if($_GET['cc'] != '')             $string_filename  = $_GET['cc'];
if($_GET['GeoRegion'] != '')		$string_filename .= "-".substr($_GET['GeoRegion'],29);
if($_GET['Region'] != '')         $string_filename .= "-".$_GET['Region'];
if($_GET['Province'] != '')       $string_filename .= "-".$_GET['Province'];
if($_GET['BathingPlace'] != '')   $string_filename .= "-".$_GET['BathingPlace'];

header('Content-type: application/vnd.google-earth.kml+xml');
header("Content-disposition: attachment; filename=".changeChars(replaceUTFChars($string_filename),"_")."_bplaces.kml");

// BEGIN **************
  // Creates an array of strings to hold the lines of the KML file.
  echo "<?xml version='1.0' encoding='UTF-8'?>\n";
  echo "<kml xmlns='http://earth.google.com/kml/2.1'>\n";
  echo "<Document>\n";
  
  // style definition - how icons look like: <styleUrl>#bw_places</styleUrl>
  
  // BWD places icon - swimmer
  echo " <Style id='bw_places'>\n";
  echo " <IconStyle>\n";
  echo " <scale>1.1</scale>\n";
  echo " <Icon>\n";
  echo " <href>http://maps.google.com/mapfiles/kml/shapes/swimming.png</href>\n";
  echo " </Icon>\n";
  echo " </IconStyle>\n";
  echo " </Style>\n";

  // BWD places
  while ($row = @mysql_fetch_assoc($result)) {
    echo "<Placemark id='placemark".$row['numind']."'>\n";
    echo "<name>".$row['Bathing water']."</name>\n";
    echo "<styleUrl>#bw_places</styleUrl>\n";
    echo "<description>\n";
    echo "<center><table style='border: 1px black solid;' cellpadding='2' cellspacing='1'>\n";

	foreach($array_fields as $key=>$val) {
      echo "<tr>\n";
      //echo "<th bgcolor='#8BD1FF' width='120'>";
      //echo "<th bgcolor='#B9E8F7' width='120'>";
      echo "<th bgcolor='lightgray' width='120'>";
      echo htmlentities($val); 
      echo "</th>\n";
      
      //echo "<td bgcolor='#F7F7F7' width='300'>\n";
      
      // 14.4.2008; user-friendly output of atribute WaterTypem instead of 1,2,3 outputs text:
      // 1=Sea, 2=River, 3=Lake, 4=Estuary
      if($val == "Type") {
        echo "<td bgcolor='#F7F7F7' width='300'>";
        switch($row[$val]) {
            case 1: echo "SEA"; break;
            case 2: echo "RIVER"; break;
            case 3: echo "LAKE"; break;
            case 4: echo "ESTUARY"; break;
            default:  echo "N/A"; break;
        }
        echo "</td>\n";
      } 
      // 14.4.2008; user-friendly output of BW status for each year
      // 1=compliant to guide values, 2=prohibited throughout the season, 3=insufficiently sampled, 
      // 4=not compliant, 5=compliant to mandatory values, 6=not sampled
      elseif(substr($val,0,1) == "y")  {
        echo "<td bgcolor='".complianceColor($row[$val])."' width='300'><font color='gray'>".complianceText($row[$val])."</font></td>\n";
      }
      else {
        echo "<td bgcolor='#F7F7F7' width='300'>".$row[$val]."</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table></center>\n";
    echo "</description>\n";
    echo "<Point>\n";
    echo "<coordinates>".$row['Longitude'].",".$row['Latitude']."</coordinates>\n";
    echo "</Point>\n";
    echo "</Placemark>\n";

  } // END BWD places 
  
  // Footer XML file
  echo "</Document>\n";
  echo "</kml>\n";
  
  
// END **************
exit;

} // END if EXPORT
	 
?>