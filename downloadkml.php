<?php
/**
 * EEA-DAMS downloadkml.php
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
 * Agency.  Portions created by Finsiel Romania are
 * Copyright (C) European Environment Agency.  All
 * Rights Reserved.
 *
 * Contributor(s):
 *  Original Code: Cristian Banciu, Finsiel Romania
 *
 *
 * @abstract     downloadkml.php
 * @copyright    2006
 * @version      1.0
 *
 *
 */

function transf($str) 
{
  $trans = get_html_translation_table(HTML_ENTITIES);
  $encoded = strtr($str, $trans);
  return $encoded;
}

require_once ('commons/config.php');


require_once 'DataObjects/Public_dams.php';
require_once 'DataObjects/Public_user_dams.php';

// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=EEADAMS user=postgres")
   or die('Could not connect: ' . pg_last_error());

$country = '';
$coordinates = 'val';
if (isset($_GET['country'])) $country = strtoupper($_GET['country']);
if (isset($_GET['coordinates'])) $coordinates = $_GET['coordinates'];

// Performing SQL query
$query = "SELECT dams.name, dams.x_$coordinates, dams.y_$coordinates, dams.ic_city, dams.river_id, dams.river_name, country.name AS cname, dams.ic_owner AS owner, dams.ic_engineer AS engineer, dams.ic_contractor AS contractor FROM dams INNER JOIN country ON dams.country=country.code WHERE dams.country LIKE '%$country%' AND x_$coordinates<>-32768 AND (x_$coordinates BETWEEN -30 AND 57) AND (y_$coordinates BETWEEN 18 AND 80) ORDER by noeea";

$result = pg_query($query) or die('Query failed: ' . pg_last_error());
if (!pg_num_rows($result)) {
	echo '<p class="warning">No data available</p>';
	// Free resultset
pg_free_result($result);
// Closing connection
pg_close($dbconn);

}
else {
$file = 'dam';
header('Content-Disposition: attachment; filename="'.$file.strtolower($country).'-'.$coordinates.'.kml"');
header('Content-type: application/vnd.google-earth.kml+xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<kml xmlns=\"http://earth.google.com/kml/2.0\">\n";
echo "<Document>\n";
if ($country == '') {
	echo"      <name>Dams in Europe using ".$coordinates." coordinates</name>\n";
	echo"      <description><![CDATA[Dams in Europe. Arguments can be country=&lt;countrycode&gt; and coordinates=&lt;val|prop|icold&gt;]]></description>\n";
} else {
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	$line = pg_fetch_array($result, null, PGSQL_ASSOC);
	echo"      <name>Dams in ".$line['cname']." using ".$coordinates."</name>\n";
        echo"      <description><![CDATA[Dams in ".$line['cname'].". Additional argument can be coordinates=&lt;val|prop|icold&gt;]]></description>\n";
	pg_free_result($result);
}
?>
<Style id="icold">
  <IconStyle>
    <Icon>
      <href>root://icons/palette-4.png</href>
      <x>32</x>
      <y>128</y>
      <w>32</w>
      <h>32</h>
    </Icon>
  </IconStyle>
  <LabelStyle>
  </LabelStyle>
</Style>
<Style id="prop">
  <IconStyle>
    <Icon>
      <href>root://icons/palette-3.png</href>
      <x>128</x>
      <w>32</w>
      <h>32</h>
    </Icon>
  </IconStyle>
  <LabelStyle>
  </LabelStyle>
</Style>
<Style id="val">
  <IconStyle>
    <Icon>
      <href>root://icons/palette-3.png</href>
      <x>128</x>
      <y>32</y>
      <w>32</w>
      <h>32</h>
    </Icon>
  </IconStyle>
  <LabelStyle>
  </LabelStyle>
</Style>
<?php
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "<Placemark>\n";
    echo "<name>".htmlspecialchars($line['name'])."</name>\n";
    echo "<description>\n";
    echo htmlspecialchars("Name:".$line['name']."<br>\n");
    echo htmlspecialchars("River: ".$line['river_name']."<br>\n");
    echo htmlspecialchars("Country: ".$line['cname']."<br>\n");
    echo htmlspecialchars("City: ".$line['ic_city']."<br>\n");
    echo htmlspecialchars("Hydrographic code: ".$line['river_id']."<br>\n");
    echo htmlspecialchars("Owner: ".$line['owner']."<br>\n");
    echo htmlspecialchars("Engineer: ".$line['engineer']."<br>\n");
    echo htmlspecialchars("Contractor: ".$line['contractor']."<br>\n");
    echo "</description>\n";
    echo "<open>0</open>\n";
    echo "<styleUrl>#$coordinates</styleUrl>\n";

    echo "<Point>\n";
    echo "<coordinates>".$line["x_$coordinates"].','.$line["y_$coordinates"].',0</coordinates>'."\n";
    echo "</Point>\n";
    echo "</Placemark>\n";
}
echo"</Document>\n";
echo"</kml>";
// Free resultset
pg_free_result($result);
// Closing connection
pg_close($dbconn);
}
?> 
