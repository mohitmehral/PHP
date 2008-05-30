<?php

/* 

21.3.2008; 

SQL za BWD export (Dunja)

Skripta izpiše vse države, ko klikneš na ima države (EXPORT TO KML) ti generira kml, ki ga odpreš v google earth in preveriš koordinate.

*/

include('config.php');
include('functions.php');

// connect
$db = mysql_connect($host, $dbuser,$dbpass);
mysql_select_db($database,$db);
// 5.5.2008; Mare; utf8: tole moraš dodat da je povezava v UTF-8
mysql_query("SET NAMES 'utf8'");

// ***********************
// **** EXPORT TO KML ****
// ***********************
if(isset($_GET['cc']) && ($_GET['detail'] == 'kml'))   {

// nastavim fielde za kml prikaz
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
$sql .= " WHERE cc = '".$_GET['cc']."' ";

if($_GET['GeoRegion'] != '')		$sql .= " AND geographic = '".$_GET['GeoRegion']."'";

if($_GET['Region'] != '')			$sql .= " AND Region LIKE '".changeChars($_GET['Region'],"%")."'";
if($_GET['Province'] != '')			$sql .= " AND Province LIKE '".changeChars($_GET['Province'],"%")."'";
if($_GET['BathingPlace'] != '')		$sql .= " AND numind = '".$_GET['BathingPlace']."'";



$result = mysql_query($sql) or die($sql."<br>".mysql_error());

// 17.12.2007; Mare; kopirano iz: http://code.google.com/support/bin/answer.py?answer=69906&topic=11364#outputkml
// BEGIN **************
  // generira ime kml fajle
  
  if($_GET['cc'] != '')             $string_filename  = $_GET['cc'];
  if($_GET['GeoRegion'] != '')		$string_filename .= "-".substr($_GET['GeoRegion'],29);
  if($_GET['Region'] != '')         $string_filename .= "-".$_GET['Region'];
  if($_GET['Province'] != '')       $string_filename .= "-".$_GET['Province'];
  if($_GET['BathingPlace'] != '')   $string_filename .= "-".$_GET['BathingPlace'];
  
  header('Content-type: application/vnd.google-earth.kml+xml');
  header("Content-disposition: attachment; filename=".changeChars(replaceUTFChars($string_filename),"_")."_bplaces.kml");
  
  
  echo '<?xml version="1.0" encoding="UTF-8"?>';
  echo "\n";
  echo '<kml xmlns="http://earth.google.com/kml/2.1">';
  echo " <Document>\n";
  
  // definiranje stilov - kakšnega izgleda so ikonce - sklicuješ se z <styleUrl>#bw_places</styleUrl>
  // ikonca za BWD places
  echo ' <Style id="bw_places">';
  echo " <IconStyle>\n";
  echo " <scale>1.1</scale>\n";
  echo " <Icon>\n";
  echo " <href>http://maps.google.com/mapfiles/kml/shapes/swimming.png</href>\n";
  echo " </Icon>\n";
  echo " </IconStyle>\n";
  echo " </Style>\n";

  // BWD places
  while ($row = @mysql_fetch_assoc($result)) {
    echo ' <Placemark id="placemark' . $row['numind'] . '">';
    //echo ' <name>' . changeChars($row['BathingPlace'],"_") . '</name>';
    echo ' <name>' . $row['Bathing water'] . "</name>\n";
    echo " <styleUrl>#bw_places</styleUrl>\n";
    echo " <description>\n";
    echo ' <center><table style="border: 1px black solid;" cellpadding="2" cellspacing="1">';
    //echo ' <tr><th colspan="2" align="center"><em>Bathing place properties</em></th></tr>';
    foreach($array_fields as $key=>$val) {
      echo "<tr>\n";
      //echo "<th bgcolor='#8BD1FF' width='120'>";
      //echo "<th bgcolor='#B9E8F7' width='120'>";
      echo "<th bgcolor='lightgray' width='120'>";
      echo htmlentities($val); 
      echo "</th>\n";
      
      //echo "<td bgcolor='#F7F7F7' width='300'>";
      
      // 14.4.2008; tole sem dodal za user-friendly izpis atributa WaterType, vse zaloge vrednosti:
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
        echo "</td>";
      } 
      // 14.4.2008; izpis statusov kopalne vode, za atribute y200x
      // 1=compliant to guide values, 2=prohibited throughout the season, 3=insufficiently sampled, 
      // 4=not compliant, 5=compliant to mandatory values, 6=not sampled
      elseif(substr($val,0,1) == "y")  {
        echo "<td bgcolor='".complianceColor($row[$val])."' width='300'><font color='gray'>".complianceText($row[$val])."</font></td>";
      }
      else {
        echo "<td bgcolor='#F7F7F7' width='300'>".$row[$val]."</td>";
      }
      echo "</tr>\n";
    }
    echo "</table></center>\n";
    echo "</description>\n";
    echo " <Point>\n";
    echo ' <coordinates>' . $row['Longitude'] . ','  . $row['Latitude'] . "</coordinates>\n";
    echo " </Point>\n";
    echo " </Placemark>\n";

  } // END BWD places 
  
  // Footer XML file
  echo " </Document>\n";
  echo "</kml>\n";
  

// END **************
exit;

} // END if EXPORT


// ****************************
// **** SHOW ALL COUNTRIES ****
// ****************************
else {

header('Content-Type: text/html; charset=utf-8');


?>

<html>
<head>
  <title>Bathing water quality data/map viewer</title>
  <link href="template.css" rel="stylesheet" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <script type="text/javascript" language="JavaScript"><!--
    // ZA PRIKAZ DIVa in IFRAMEa z GRAFOM (http://www.willmaster.com/blog/css/show-hide-floating-div.php)
    function HideContent(div_id) {
      if(div_id.length < 1) { return; }
      document.getElementById(div_id).style.display = "none";
      document.getElementById('graph_font').innerHTML = "";
      // ta mora ostat, da tudi v firefoxu po defaultu pokaže Loading (če ne ugašaš okna ampak samo klikaš ikonce)
      document.getElementById('graph_img').src = "images/loading.gif";
    }
    
    // div_id, iframe_id - idja od elementov div, iframe
    // src_html - to je source ki se pokaže v iframe_id
    // width, height - da lahko v f. specificiraš širino in višino
    // left - odmaknjenost od levega roba
    function ShowContent(div_id, src_html, div_title, width, height, left, top) {
      if(div_id.length < 1) { return; }
      // 1. najprej moraš postavit širino, višino, pozicijo diva in ga pokazat - dat na "block"
      document.getElementById(div_id).style.width = width;
      document.getElementById(div_id).style.height = height;
      document.getElementById(div_id).style.left = left;
      document.getElementById(div_id).style.top = top;
      document.getElementById(div_id).style.display = "block";
      // 2. potem mu nastaviš nov naslov 
      document.getElementById('graph_font').innerHTML = div_title;
      // 3. potem pa še loading slikico
      document.getElementById('graph_img').src = "images/loading.gif";
      // 4. nato pa mu nastaviš nov src (to najdlje traja)
      document.getElementById('graph_font').innerHTML = div_title;
      document.getElementById('graph_img').src = src_html;
    }

    //-->
  </script>


</head>

<body>

<?php

//echo "<b><u><font color='red'>ČŠŽ ÖÄÜ Ő É Ã Ű Ñ</font></u></b><br>";
//echo "<b>Bathing water quality data/map viewer</b><br><br>";

// DIV z HELPom
echo "
<div id='help_div' onclick='document.getElementById(\"help_div\").style.display=\"none\";' >
  <div style='position: relative; '>
	<h1>Bathing water quality data/map viewer - quick help</h1>
	<!-- TODO splošni opis -->
	<!-- <br><br> -->

	<!-- opis stolpcev -->
	<font class='stolpec'>&nbsp;Country&nbsp;</font> - All EU countries are listed, drag the mouse over country to display country name in native language.
	<br><br>
	<font class='stolpec'>&nbsp;Region&nbsp;</font> - Bigger countries are divided into regions, select a <U>region</U> to narrow the search.
	<br>
	<img src='images/Regije.gif' border='0'>&nbsp;\"region icon\" - Shows a map with all regions in selected country. Click on map to close.
	<br><br>
	<font class='stolpec'>&nbsp;Province&nbsp;</font> - Bigger <U>regions</U> are divided into <U>provinces</U>, select a <U>province</U> to narrow the search.
	<br>
	<img src='images/Regije.gif' border='0'>&nbsp;\"province icon\" - Smaller countries have only one <U>region</U> (no \"region icon\"), for these countries a <U>province map</U> is provided. Click on map to close.
	<br><br>
	<font class='stolpec'>&nbsp;Bathing water&nbsp;</font> - Initially number of bathing waters in each country are displayed here. When <U>region</U> or <U>province</U> is selected, number of bathing waters in selected <U>region</U> / <U>provice</U> is shown here. Select a <U>province</U> and <b>select box</b> is shown here: 
	<li>select a <U>bathing water</U> and a small window with water quality info will pop up. Brackets indicate status for each year, each status has one colour. If the bracket is empty (white), there were no measurements or not sufficient samples for that year. Click on window to close. 
	<br><br>
	<font class='stolpec'>&nbsp;Visualization&nbsp;</font>
	<br>
	<img src='images/GoogleEarthMali.gif' border='0'> - Download and/or open a <b>kml file</b> with bathing water placemarks. If <U>region</U>, <U>province</U> or <U>bathing water</U> is selected, file contains only bathing waters in selected region, province or bathing water. <b>Kml files</b> are best viewed with <a target='_NEW_WINDOW' href='http://earth.google.com/download-earth.html'>Google Earth</a>.
	<br>

	<!-- grafi	 -->
	<img src='images/SlanaVodaGraf.jpg' border='0'> - Graph for <B>coastal</B> bathing waters, there are 2 graph types:
		<li><B>Line graph</B> is available when <U>country</U> or <U>region</U> is selected. For each year data points show the percentage of bathing waters compliant to each of 4 statuses. A line is connected between data points to show trends.
		<li><B>Bar graph</B> is available when <U>province</U> is selected. For each year bars show distribution of 4 statuses. If sum is less than 100%, there were bathing waters with no measurements or not sufficient samples for that year.
	<br>
	<img src='images/SlanaVodaGrafX.jpg' border='0'> - Indicates that there are no coastal bathing waters in selected <U>country</U>, <U>region</U> or <U>province</U>, so no graph can be displayed.
	<br>
	<img src='images/SladkaVodaGraf.jpg' border='0'> - Graph for <B>freshwater</B> bathing waters, same as for <b>coastal graph</b> applies here.
	<br>
	<img src='images/SladkaVodaGrafX.jpg' border='0'> - Indicates that there are no freshwater bathing waters in selected <U>country</U>, <U>region</U> or <U>province</U>, so no graph can be displayed.
	
	<div style='position: absolute; top: 0px; right: 10px;'>
		<a onclick='javascript: document.getElementById(\"help_div\").style.display=\"none\";' style='cursor: hand; cursor: pointer; ' >[close]</a>
	</div>

  </div>
  
</div>
";

// DIV, IMG za prikaz grafa / alt="close" title="close" 
echo '<div align="center" id="graph_div" onclick="HideContent(\'graph_div\');" >';
echo '<div style="position: relative;">';
echo '<font id="graph_font" style="font-weight: bold; color: white; "></font>';
echo '<div style="position: absolute; top: 0px; right: 10px;"><a onclick="HideContent(\'graph_div\'); return true;" href="javascript:HideContent(\'graph_div\')">[close]</a></div><br><br>';
echo '<img id="graph_img" src="images/loading.gif" border="0" />';
echo '</div>';
echo '</div>';


// TABELA ZA PRIKAZ HELP LINKA
echo "<table style='border: 0px;' border='0' cellpadding='0' cellspacing='0'>";
echo "<th style='background-color: white; color: black; border: 0px; line-height: 18px;' colspan='6' width='955' align='right'>";
echo "<font color='#00446A'>Need help using viewer?</font>&nbsp;<a style='text-decoration: none; color: #6FA7DA; cursor: hand; cursor: pointer;' onclick=\"document.getElementById('help_div').style.display='block'; \">Click here!</a>";
echo "</th>";
echo "</table>";


$outliers = 0;
$stevec = 0;
$visina_stolpca = 30; // višina stolpca td
$eu27_stations = array();

// GLAVNA TABELA
echo "<table border='0' cellpadding='0' cellspacing='0'>";

/*
echo "<th width='130' id='prvi_stolpec' rowspan='29'>";
	//echo "<img border='0' src='images/Flash1.jpg'>";
	// FLASH
	echo "<object width='130' height='862'>";
		echo "<param name='movie' value='images/Kopalisca.swf'>";
		echo "<embed src='images/Kopalisca.swf' width='130' height='862'></embed>";
	echo "</object>";

echo "</th>";
*/

// sliko sem dal nad tabelo ! POZOR ! width sem moral povečat na 954px, ker je pri 950 (seštevek stolpcev) blo premalo
echo "<tr><td style='padding: 0px; margin: 0px;' colspan='5'><img width='954' height='80' src='images/Flash1.jpg' border='0' /></td></tr>\n";

echo "<th width='145'>Country</th>";
echo "<th width='210'>Region</th>";
echo "<th width='220'>Province</th>";
echo "<th width='300'>Bathing water</th>";
echo "<th width='75'>Visualization</th>";

// *****
// EU 27
// *****

// iz vseh držav izračuna še koliko je postaj za EU in ga da kot zadnjega v array
//$country_coast_stations[$stevec] = array_sum($country_coast_stations);
//$country_freshwater_stations[$stevec] = array_sum($country_freshwater_stations);
echo "<tr style='";
if($_GET['GeoRegion'] != '')	echo "background-color: #D7F5FF;";
else							echo "background-color: #F7F7F7;";
echo "'>";
echo "<td style='border-bottom: 2px #3180BB solid' height='".$visina_stolpca."'>";
	if(file_exists("images/flags/Europe.jpg")) echo "<img src='images/flags/Europe.jpg' border='0' />&nbsp;";
	echo "<B>EU 27</B>";
echo "</td>";

// GEOGRAPHIC REGION
echo "<td style='border-bottom: 2px #3180BB solid'>";
  echo "<select style='width: 180px;' name='EU27_georegion' id='EU27_georegion' onchange='document.location=\"index.php?cc=EU27&GeoRegion=\" + this.value'>";
	$sql_georegion = "
		SELECT geographic, COUNT(*) AS no_of_stations
		FROM numind_geographic
		GROUP BY geographic	
	";
	$result_georegion = mysql_query($sql_georegion);
	echo "<option value='' selected>--- Geographic region ---</option>\n";
	while($myrow_georegion = mysql_fetch_array($result_georegion))  {
	  $eu27_stations[$myrow_georegion['geographic']] = $myrow_georegion['no_of_stations'];
	  if($myrow_georegion['geographic'] != '')	{
		echo "<option value='".$myrow_georegion['geographic']."' ";
		  if($myrow_georegion['geographic'] == $_GET['GeoRegion']) 	echo "SELECTED";
		echo ">".substr($myrow_georegion['geographic'],29)."</option>\n";
	  } // END if
	} // END while
  echo "</select>\n";
  echo "&nbsp;";
  
  // POKAŽE GUMB GEOGRAPHIC REGION 
  if(file_exists("regions/geographic_region.png"))  {
	echo "<a alt='Geographic region map' title='Geographic region map' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','regions/geographic_region.png','Geographic region map',725,735,220,160); \"><img src='images/Regije.gif' /></a>";
  }
echo "</td>";

echo "<td style='border-bottom: 2px #3180BB solid'>&nbsp;</td>";

echo "<td style='border-bottom: 2px #3180BB solid' >";
  echo "<font color='gray'>";
	if($_GET['GeoRegion'] != '')	echo $eu27_stations[$_GET['GeoRegion']];
	else							echo array_sum($eu27_stations);
	echo " bathing waters";
  echo "</font>";
echo "</td>";

echo "<td style='border-bottom: 2px #3180BB solid' align='right'>";
	// GOOGLE EARTH
	/*
	if($_GET['cc'] == 'EU27' && $_GET['GeoRegion'] != '')	{
		echo "<a alt='Google Earth KML - ".$title_string."' title='Google Earth KML - ".$title_string."' href='javascript: document.location = \"index.php?detail=kml&cc=EU27&GeoRegion=".$_GET['GeoRegion']."\";'><img src='images/GoogleEarthMali.gif' border='0' /></a>";
		echo "&nbsp;";
		$title_string = substr($_GET['GeoRegion'],29);
	} else {
		$title_string = "EU 27";
	}
	*/
	// GRAF SLANA
	echo "<a alt='Quality of coastal bathing waters' title='Quality of coastal bathing waters' style='cursor:pointer; cursor:hand;' onclick=\"ShowContent('graph_div','line_jpgraph.php?Country=EU27&GeoRegion=".$_GET['GeoRegion']."&type=coast','',950,750,10,160); return true;\"><img src='images/SlanaVodaGraf.jpg' border='0' /></a>";
	echo "&nbsp;";
	// GRAF SLADKA
	echo "<a alt='Quality of freshwater bathing waters' title='Quality of freshwater bathing waters' style='cursor:pointer; cursor:hand;' onclick=\"ShowContent('graph_div','line_jpgraph.php?Country=EU27&GeoRegion=".$_GET['GeoRegion']."&type=fresh','',950,750,10,160); return true;\" ><img  src='images/SladkaVodaGraf.jpg' border='0' /></a>";
echo "</td>";
echo "</tr>\n";

$sql = '
  SELECT 
      UPPER(c.Country) AS Country, s.cc, c.NationalName AS NationalName,
      COUNT(IF(SeaWater = "O",1,NULL)) AS "coast_stations",
      COUNT(IF(SeaWater = "N",1,NULL)) AS "freshwater_stations"
  FROM bwd_stations s
  LEFT JOIN countrycodes_iso c ON s.cc = c.ISO2 ';

if($_GET['GeoRegion'] != '')	
	$sql .= "INNER JOIN numind_geographic n USING (numind) WHERE geographic = '".$_GET['GeoRegion']."' ";

$sql .= 'GROUP BY s.cc ORDER BY c.Country';

$result = mysql_query($sql) or die(mysql_error()."<br>".$sql);


// podatki tabele
while($myrow = mysql_fetch_array($result))  {

  echo "<tr id='tr_".$stevec."' class='";
  if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "selected";
  else {
    if($stevec % 2 == 1)  echo "alternate";
    else                    echo "";
  }
  echo "'>";

    // COUNTRY
    echo "<td alt='".$myrow['NationalName']."' title='".$myrow['NationalName']."' style='cursor:help;' height='".$visina_stolpca."'>";
	$filename_zastava = "images/flags/".ucfirst(strtolower(changeChars($myrow['Country'],"_"))).".jpg";
	if(file_exists($filename_zastava)) echo "<img src='".$filename_zastava."' border='0' />&nbsp;";
	echo "<B>".$myrow['Country']."</B>";
	echo "</td>";
    
    // Number of bathing places
    $country_coast_stations[$stevec] = $myrow['coast_stations'];
    $country_freshwater_stations[$stevec] = $myrow['freshwater_stations']; 

    // REGION
    echo "<td >";
      echo "<select style='width: 180px;' name='".$myrow['cc']."_region' id='".$myrow['cc']."_region' onchange='document.location=\"index.php?cc=".$myrow['cc']."&GeoRegion=".$_GET['GeoRegion']."&Region=\" + this.value'>";
        $sql_region = "
          SELECT Region, COUNT(IF(SeaWater = 'O',1,NULL)) AS 'coast_stations', COUNT(IF(SeaWater = 'N',1,NULL)) AS 'freshwater_stations'
          FROM bwd_stations ";
		if($_GET['GeoRegion'] != '')	
			$sql_region .= "INNER JOIN numind_geographic n USING (numind) WHERE geographic = '".$_GET['GeoRegion']."' AND ";
		else 
			$sql_region .= "WHERE ";
		$sql_region .= "
           cc = '".$myrow['cc']."'
          GROUP BY Region
          ORDER BY Region
        ";
        $result_region = mysql_query($sql_region);
        echo "<option value='' selected>--- Region ---</option>\n";
        while($myrow_region = mysql_fetch_array($result_region))  {
          echo "<option value='".$myrow_region['Region']."' ";
          if($myrow['cc'] == $_GET['cc'] && $myrow_region['Region'] == $_GET['Region'])  {
            echo "SELECTED";
            $region_freshwater_stations[$stevec]  = $myrow_region['freshwater_stations'];
            $region_coast_stations[$stevec]  = $myrow_region['coast_stations'];
          }
          echo ">".$myrow_region['Region']."</option>\n";
        }
      echo "</select>\n";
      echo "&nbsp;";
      
      // POKAŽE GUMB ZA PNG REGIJE, ČE OBSTAJA PNG 
      if(file_exists("regions/pdf_".strtolower($myrow['cc'])."_regions.png"))  {
        echo "<a alt='".$myrow['Country']." - region map' title='".$myrow['Country']." region map' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','regions/pdf_".strtolower($myrow['cc'])."_regions.png','".$myrow['Country']." - region map',725,735,350,170); \"><img src='images/Regije.gif' /></a>";
      }
    echo "</td>";
  
    // PROVINCE
    echo "<td>";
      echo "<select ";
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "style='visibility: visible; width: 190px;' ";
      else                                                      echo "style='visibility: hidden; width: 190px;' ";
      echo "name='".$myrow['cc']."_province' id='".$myrow['cc']."_province' onchange='document.location=\"index.php?cc=".$_GET['cc']."&GeoRegion=".$_GET['GeoRegion']."&Region=".$_GET['Region']."&Province=\" + (this.value);'>";
      echo "<option value='' selected>--- Province ---</option>\n";
       if($_GET['cc'] == $myrow['cc'])  {
          $sql_province = "
            SELECT Province, COUNT(IF(SeaWater = 'O',1,NULL)) AS 'coast_stations', COUNT(IF(SeaWater = 'N',1,NULL)) AS 'freshwater_stations'
            FROM bwd_stations ";
		  if($_GET['GeoRegion'] != '')	$sql_province .= "INNER JOIN numind_geographic n USING (numind) WHERE geographic = '".$_GET['GeoRegion']."' AND ";
		  else 							$sql_province .= "WHERE ";
		  $sql_province .= "
             cc = '".$_GET['cc']."'
            AND Region LIKE '".changeChars($_GET['Region'],"%")."'
            GROUP BY Province
            ORDER BY Province
          ";
          $result_province = mysql_query($sql_province);
          while($myrow_province = mysql_fetch_array($result_province))  {
            echo "<option value='".convertUTFtoHTML($myrow_province['Province'])."' ";
            
            // 10.5.2008; če je enojni apostrof ' v stringu, tukaj vržem ven escape character: L\'AQUILA -> L'AQUILA
            if($myrow_province['Province'] == str_replace("\\","",$_GET['Province']))  {
              echo "SELECTED";
              $province_freshwater_stations[$stevec] = $myrow_province['freshwater_stations'];
              $province_coast_stations[$stevec]  = $myrow_province['coast_stations'];
            }
            echo ">".$myrow_province['Province']."</option>\n";           
          }
       }
      echo "</select>\n";
      echo "&nbsp;";
      
      // POKAŽE GUMB ZA PNG PROVINCe, ČE OBSTAJA PNG 
      if(file_exists("provinces/".strtolower($myrow['cc'])."_provinces.png"))  {
        echo "<a id='".$myrow['cc']."_province_link' ";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "style='visibility: visible; cursor:pointer; cursor: hand;'";
        else                                                      echo "style='visibility: hidden; cursor:pointer; cursor: hand;'";
        echo "alt='".$myrow['Country']." - province map' title='".$myrow['Country']." province map'  onclick=\"ShowContent('graph_div','provinces/".strtolower($myrow['cc'])."_provinces.png','".$myrow['Country']." - province map',725,735,350,230); \"><img src='images/Regije.gif' border='0' /></a>";
      }
    echo "</td>";
    
    
    // BATHING PLACES
	
	// nastavim odmik grafa od zg.roba 
	if($stevec < 14)		$top_odmik_grafa = 190+($stevec*$visina_stolpca);
	else					$top_odmik_grafa = ($stevec*$visina_stolpca)-95;
	
	echo "<td id='td_".$stevec."' >";
      echo "<font style=\"display:";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '')   echo "none";
        else                                                                                  echo "block";
      echo "\" color='gray' >";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')        echo ($region_coast_stations[$stevec]+$region_freshwater_stations[$stevec]);
        else                                                            echo ($country_coast_stations[$stevec]+$country_freshwater_stations[$stevec]); 
      echo " bathing waters ";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')        echo "in selected region";
      echo "</font>";

      echo "<select ";
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '')   echo "style='display: block; width: 290px;' ";
      else                                                                                  echo "style='display: none; width: 290px;' ";
      echo "name='".$myrow['cc']."_bplace' id='".$myrow['cc']."_bplace' ";
      
      echo "onchange=\"if(this.value != '') {ShowContent('graph_div','bar_jpgraph.php?cc=".$myrow['cc']."&Country=".$myrow['Country']."&GeoRegion=".$_GET['GeoRegion']."&Region=".convertUTFtoHTML($_GET['Region'])."&Province=".convertUTFtoHTML($_GET['Province'])."&BathingPlace=' + document.getElementById('".$myrow['cc']."_bplace').value,'',550,250,300,".$top_odmik_grafa."); return true;} else {HideContent('graph_div');}\" ";
      
      echo ">";
      echo "<option value='' selected>--- ".($province_coast_stations[$stevec]+$province_freshwater_stations[$stevec])." bathing waters in selected province ---</option>\n";
       if($_GET['cc'] == $myrow['cc'])  {
          $sql_bplace = "
			SELECT numind, Prelev 
			FROM bwd_stations ";
		    if($_GET['GeoRegion'] != '')	$sql_bplace .= "INNER JOIN numind_geographic n USING (numind) WHERE geographic = '".$_GET['GeoRegion']."' AND ";
			else 							$sql_bplace .= "WHERE ";
			$sql_bplace .= "
			  cc = '".$_GET['cc']."' 
			 AND Region LIKE '".changeChars($_GET['Region'],"%")."' 
			 AND Province LIKE '".changeChars($_GET['Province'],"%")."' 
			 ORDER BY Prelev
		    ";
          $result_bplace = mysql_query($sql_bplace);
          while($myrow_bplace = mysql_fetch_array($result_bplace))  {
            echo "<option value='".$myrow_bplace['numind']."'";
            if($myrow_bplace['numind'] == $_GET['BathingPlace'])  echo "SELECTED";
            echo ">".$myrow_bplace['Prelev']."</option>\n";           
          }
       }
      echo "</select>\n";
    echo "</td>";

    // GUMBKI ZA LINKE - VIZUALIZACIJO
    echo "<td bgcolor='' align='right'>";
      // generira link za KML
      $link_za_kml = "\"index.php?detail=kml&cc=".$myrow['cc']."&GeoRegion=".$_GET['GeoRegion']."\"";
      if($_GET['Region'] != "")
        $link_za_kml .= " + \"&Region=\" + document.getElementById(\"".$myrow['cc']."_region\").value";
      if(isset($_GET['Province']))
        $link_za_kml .= "+ \"&Province=\" + document.getElementById(\"".$myrow['cc']."_province\").value";
        $link_za_kml .= "+ \"&BathingPlace=\" + document.getElementById(\"".$myrow['cc']."_bplace\").value";
      $string_title_kml = $myrow['Country'];
      if($_GET['Region'] != "" && $_GET['cc'] == $myrow['cc']) $string_title_kml .= ", ".$_GET['Region'];
      if($_GET['Province'] != "" && $_GET['cc'] == $myrow['cc']) $string_title_kml .= ", ".$_GET['Province'];
      //if($_GET['BathingPlace'] != "" && $_GET['cc'] == $myrow['cc']) $string_title_kml .= ", ".$_GET['BathingPlace'];

      // LINK ZA PDF; 20.5.2008; izpuščeno
	  /*
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != "")  {
        $link_za_pdf = "pdf_maps/pdf_".strtolower($myrow['cc'])."_".strtolower(changeChars(replaceUTFChars($_GET['Region']),"_")).".pdf";
        $title_za_pdf = "PDF map - ".$myrow['Country'].", ".$_GET['Region'];
      } else {
        $link_za_pdf = "pdf_maps/pdf_".strtolower($myrow['cc']).".pdf";
        $title_za_pdf = "PDF map - ".$myrow['Country'];
     }
	 */

      // LINKA ZA GRAF, ZA PROVINCE IZBERE BAR GRAPH, ZA OSTALO PA LINE GRAPH
      $link_za_graf = "line_jpgraph.php?cc=".$myrow['cc']."&Country=".$myrow['Country']."&GeoRegion=".$_GET['GeoRegion'];
      if($_GET['Region'] != "" && $_GET['cc'] == $myrow['cc'])
        $link_za_graf .= "&Region=".$_GET['Region'];
      
	  if(($_GET['Province'] != "") && $_GET['cc'] == $myrow['cc'])
        $link_za_graf = "bar_jpgraph.php?cc=".$myrow['cc']."&Country=".$myrow['Country']."&GeoRegion=".$_GET['GeoRegion']."&Region=".$_GET['Region']."&Province=".$_GET['Province'];

      // 10.5.2008; ZAMENJA IN DISABLA IKONCO, ČE NI NOBENE SLADKE/SLANE VODE V DRŽAVI/REGIJI/PROVINCE
      $coast_disabled = 0;
      $fresh_disabled = 0;
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '') {
        if($province_coast_stations[$stevec] == 0)       $coast_disabled = 1; 
        if($province_freshwater_stations[$stevec] == 0)  $fresh_disabled = 1;
      } elseif($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  {
        if($region_coast_stations[$stevec] == 0)         $coast_disabled = 1; 
        if($region_freshwater_stations[$stevec] == 0)    $fresh_disabled = 1; 
      } else {
        if($myrow['coast_stations'] == 0)       $coast_disabled = 1; 
        if($myrow['freshwater_stations'] == 0)  $fresh_disabled = 1; 
      }

      // GOOGLE EARTH
      echo "<a alt='Google Earth KML - ".$string_title_kml."' title='Google Earth KML - ".$string_title_kml."' href='javascript: document.location = ".$link_za_kml.";'><img src='images/GoogleEarthMali.gif' border='0' /></a>";
      echo "&nbsp;";
      
      // PDF; 20.5.2008; izpuščeno
	  /*
      if(file_exists($link_za_pdf))
        echo "<a alt='".$title_za_pdf."' title='".$title_za_pdf."' target='NewWindow' href='".$link_za_pdf."'><img src='images/PDFmala.gif' border='0' /></a>";
      else 
        echo "<img alt='No PDF map for this region' title='No PDF map for this region' src='images/PDFmalaX.gif' border='0' />";
      echo "&nbsp;";
      */

      // GRAF
	  // nastavim odmik grafa od zg.roba 
	  if($stevec < 14)		$top_odmik_grafa = 190+($stevec*$visina_stolpca);
	  else					$top_odmik_grafa = ($stevec*$visina_stolpca)-295;
        
		// ČE Ni SLANIH KOPALNIH VOD, POKAŽE SAMO IMG + ONMOUSEOVER
        if($coast_disabled) {
          $title_graf = "No coastal bathing waters in this Country/Region/Province";
          echo "<img alt='".$title_graf."' title='".$title_graf."' src='images/SlanaVodaGrafX.jpg' border='0' />";
        } else {
          $title_graf = "Quality of coastal bathing waters in this Country/Region/Province"; // - ".$myrow['Country'];
          echo "<a alt='".$title_graf."' title='".$title_graf."' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','".convertUTFtoHTML($link_za_graf)."&type=coast','',640,450,180,".$top_odmik_grafa."); \"><img src='images/SlanaVodaGraf.jpg' border='0' /></a>";
        }
        echo "&nbsp;";
        
        // ČE Ni SLADKIH KOPALNIH VOD, POKAŽE SAMO IMG + ONMOUSEOVER
        if($fresh_disabled) {
          $title_graf = "No freshwater bathing waters in this Country/Region/Province";
          echo "<img alt='".$title_graf."' title='".$title_graf."' src='images/SladkaVodaGrafX.jpg' border='0' />";
        } else {
          $title_graf = "Quality of freshwater bathing waters in this Country/Region/Province"; // - ".$myrow['Country'];
          echo "<a alt='".$title_graf."' title='".$title_graf."' style='cursor:pointer; cursor: hand;'  onclick=\"ShowContent('graph_div','".convertUTFtoHTML($link_za_graf)."&type=fresh','',640,450,180,".$top_odmik_grafa."); \"><img src='images/SladkaVodaGraf.jpg' border='0' /></a>";
        }
 
    echo "</td>";
  echo "</tr>\n";
  flush();
  $stevec++;
}


echo "</table>";

echo "<br><img src='images/GoogleEarthMali.gif' border='0' />&nbsp;Don't have Google Earth? Download it here: <a target='_NEW_WINDOW' href='http://earth.google.com/download-earth.html'>http://earth.google.com/download-earth.html</a>";
//echo "<br><img src='images/PDFmala.gif' border='0' />&nbsp;Adobe Acrobat Reader - download it here: <a target='_NEW_WINDOW' href='http://www.adobe.com/products/acrobat/readstep2.html'>http://www.adobe.com/products/acrobat/readstep2.html</a>";

//echo array_sum($country_coast_stations) + array_sum($country_freshwater_stations);

} // END if-else SHOW QUERY

?>

</body>
</html>
