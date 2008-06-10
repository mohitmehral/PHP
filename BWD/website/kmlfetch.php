<?php

/* vi: set ts=4 sw=4:

BWD water quality data/map viewer: EXPORT TO KML FILE

21.3.2008; first version

*/

include('config.php');
include('functions.php');

$bbox="19.5,51.5,20.5,52.5";
if (isset($_GET['BBOX'])) {
    $bbox = $_GET['BBOX'];
}
list($maxw,$maxs,$maxe,$maxn) = split(",",$bbox);

function TypeAsText($key) {
    switch($key) {
	case 1: return "SEA"; break;
	case 2: return "RIVER"; break;
	case 3: return "LAKE"; break;
	case 4: return "ESTUARY"; break;
	default:  return "N/A"; break;
    }
}

header('Content-type: application/vnd.google-earth.kml+xml');

$baseurl = "http://".$_SERVER['SERVER_NAME']."/".substr($_SERVER['REQUEST_URI'],1 , strrpos($_SERVER['REQUEST_URI'],"/"));

echo "<?xml version='1.0' encoding='UTF-8'?>\n";
echo "<kml xmlns='http://earth.google.com/kml/2.1'>\n";
echo "<Document>\n";

// style definition - how icons look like: <styleUrl>#bw_places</styleUrl>

// BWD places icon - swimmer
echo " <Style id='bw_places'>\n";
echo " <IconStyle>\n";
echo " <scale>1.1</scale>\n";
echo " <Icon>\n";
echo " <href>".$baseurl."images/swimming.png</href>\n";
echo " </Icon>\n";
echo " </IconStyle>\n";
echo " </Style>\n";

if (($maxe - $maxw) > 1) {
    $longcenter = ($maxe + $maxw) / 2;
    $maxe = $longcenter + 0.5;
    $maxw = $longcenter - 0.5;
}
if (($maxn - $maxs) > 1) {
    $latcenter = ($maxn + $maxs) / 2;
    $maxn = $latcenter + 0.5;
    $maxs = $latcenter - 0.5;
}
if(($maxe - $maxw) <= 1 && ($maxn - $maxs) <= 1)   {

// connect to database
$db = mysql_connect($host, $dbuser,$dbpass);
mysql_select_db($database,$db);
// 5.5.2008; utf8: this must be included for utf-8 charset
mysql_query("SET NAMES 'utf8'");

// array with fields to show in kml
$array_fields = array(
  'numind' => 'Id',
  'latitude' => 'Latitude',
  'longitude'=> 'Longitude',
  'Country' => 'Country',
  'Region' => 'Region',
  'Province' => 'Province',
  'Commune' => 'Commune',
  'Prelev' => 'Bathing water',
  'Type' => 'Type',
  'y2000' => 'Year 2000',
  'y2001' => 'Year 2001',
  'y2002' => 'Year 2002',
  'y2003' => 'Year 2003',
  'y2004' => 'Year 2004',
  'y2005' => 'Year 2005',
  'y2006' => 'Year 2006',
  'y2007' => 'Year 2007');

$lastknownyear = 'y2007';

$sql = "
  SELECT 
	UPPER(c.Country) AS Country,
	s.numind, s.latitude, s.longitude, s.WaterType AS Type, s.Region, s.Province, s.Commune, s.Prelev, 
	s.y2000, s.y2001, s.y2002, s.y2003, s.y2004, s.y2005, s.y2006, s.y2007 
  FROM bwd_stations s
  LEFT JOIN countrycodes_iso c ON s.cc = c.ISO2 ";

$sql .= " WHERE latitude > ".$maxs." AND latitude < ".$maxn." AND longitude > ".$maxw." AND longitude < ".$maxe;
$sql .= " ORDER BY Prelev";



$result = mysql_query($sql) or die($sql."<br>".mysql_error());

  // BWD places
  while ($row = @mysql_fetch_assoc($result)) {
    echo "<Placemark id='p_".$row['numind']."'>\n";
    echo "<name>".$row['Prelev']."</name>\n";
    echo "<styleUrl>#bw_places</styleUrl>\n";
    echo "<Snippet>\n";
    echo $array_fields['Type'].": ".TypeAsText($row['Type'])."\n";
    echo $array_fields[$lastknownyear].": ".complianceText($row[$lastknownyear])."\n";
    echo "</Snippet>\n";
    echo "<description>\n";
    echo "<![CDATA[<table style='border: 1px black solid;' cellpadding='2' cellspacing='1'>\n";

	foreach($array_fields as $key=>$val) {
      echo "<tr>\n";
      //echo "<th bgcolor='#8BD1FF' width='120'>";
      //echo "<th bgcolor='#B9E8F7' width='120'>";
      echo "<th bgcolor='#C0C0C0' width='120'>";
      echo htmlentities($val); 
      echo "</th>\n";
      
      //echo "<td bgcolor='#F7F7F7' width='300'>\n";
      
      // 14.4.2008; user-friendly output of attribute WaterType instead of 1,2,3 output text:
      if($key == "Type") {
        echo "<td bgcolor='#F7F7F7' width='300'>".TypeAsText($row[$key])."</td>\n";
      } 
      // 14.4.2008; user-friendly output of BW status for each year
      // 1=compliant to guide values, 2=prohibited throughout the season, 3=insufficiently sampled, 
      // 4=not compliant, 5=compliant to mandatory values, 6=not sampled
      elseif(substr($key,0,1) == "y")  {
        echo "<td bgcolor='".complianceColor($row[$key])."' width='300'>".complianceText($row[$key])."</td>\n";
      }
      else {
        echo "<td bgcolor='#F7F7F7' width='300'>".$row[$key]."</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table>]]>\n";
    echo "</description>\n";
    echo "<Point>\n";
    echo "<coordinates>".$row['longitude'].",".$row['latitude']."</coordinates>\n";
    echo "</Point>\n";
    echo "</Placemark>\n";

  } // END BWD places 
  
  
  

} // END if EXPORT

// Footer XML file
echo "</Document>\n";
echo "</kml>\n";
?>
