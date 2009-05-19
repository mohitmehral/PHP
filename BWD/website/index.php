<?php

/* vi: set ts=4 sw=4:

BWD water quality data/map viewer: MAIN FILE WITH COUNTRY LISTING, MAPS, GRAPHS, ETC.

21.3.2008; first version

*/

include('config.php');
include('functions.php');
if (!isset($_GET['GeoRegion'])) $_GET['GeoRegion'] = '';
if (!isset($_GET['cc'])) $_GET['cc'] = '';
if (!isset($_GET['Region'])) $_GET['Region'] = '';
if (!isset($_GET['Province'])) $_GET['Province'] = '';
if (!isset($_GET['BathingPlace'])) $_GET['BathingPlace'] = '';

// MYSQL CONNECT
$db = mysql_connect($host, $dbuser,$dbpass);
mysql_select_db($database,$db);
// 5.5.2008; utf8: this must be included fot utf-8 charset
mysql_query("SET NAMES 'utf8'");

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>Bathing water quality data/map viewer</title>
  <link href="template.css" rel="stylesheet" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  
  <script type="text/javascript" language="JavaScript">
    // <![CDATA[
    // JS FUNCTIONS TO SHOW DIV WITH GRAPH, MAP 
	function HideContent(div_id, font_id) {
      if (div_id.length < 1) { return; }
      document.getElementById(div_id).style.display = "none";
      document.getElementById(font_id).innerHTML = "";
      // for Firefox to show "Loading ..." image by default when browsing different graphs, not closing the div
      document.getElementById(div_id).src = "images/loading.gif";
    }
    
    // div_id- ids from div, iframe elements
    // src_html - source (image) to show in div_id
	// width, height - to specify width and height of div window
    // left - how far from the left border is positioned
	// img_id - img element to show image for maps/graphs
	// div_title - title text to put in id=map_font element
    function ShowContent(div_id, font_id, img_id, src_html, div_title, width, height, left, top) {
      if (div_id.length < 1) { return; }
	  // 1. first set width, height, position of div and show it (block)
      document.getElementById(div_id).style.width = width;
      document.getElementById(div_id).style.height = height;
      document.getElementById(div_id).style.left = left;
      document.getElementById(div_id).style.top = top;
      document.getElementById(div_id).style.display = "block";
      // 2. set new headline for div (only for region/province maps)
	  if (div_title != '') {
		document.getElementById(font_id).innerHTML = div_title;
	  }
      // 3. set "Loading ..." gif in div
      document.getElementById(img_id).src = "images/loading.gif";
      // 4. when the source image is loaded, set this image in div
      //document.getElementById(font_id).innerHTML = div_title;
      document.getElementById(img_id).src = src_html;
    }

// ]]>
  </script>
</head>

<body>
<?php

// HELP DIV
echo "
<div id='help_div' onclick='document.getElementById(\"help_div\").style.display=\"none\";' >
  <div style='position: relative; '>
	<h1>Bathing water quality data/map viewer - quick help</h1>
	<!-- TODO general description -->

	<!-- colmun description -->
	<p>
	<span class='stolpec'>Country</span> - All EU countries are listed, drag the mouse over country to display country name in native language.
	</p>
	<p>
	<span class='stolpec'>Region</span> - Bigger countries are divided into regions, select a <u>region</u> to narrow the search.
	<br/>
	<img src='images/Regije.gif' border='0' alt='region'/> \"region icon\" - Shows a map with all regions in selected country. Click on map to close.
	</p>
	<p>
	<span class='stolpec'>Province</span> - Bigger <u>regions</u> are divided into <u>provinces</u>. Select a <u>province</u> to narrow the search.
	<br/>
	<img src='images/Regije.gif' border='0' alt='province'/> \"province icon\" - Smaller countries have only one <u>region</u> (no \"region icon\"), for these countries a <u>province map</u> is provided. Click on map to close.
	</p>
	<p>
	<span class='stolpec'>Bathing water</span> - Initially number of bathing waters in each country are displayed here. When <u>region</u> or <u>province</u> is selected, number of bathing waters in selected <u>region</u> / <u>provice</u> is shown here. Select a <u>province</u> and <b>select box</b> is shown here: 
        <br/>
	Select a <u>bathing water</u> and a small window with water quality info will pop up. Brackets indicate status for each year, each status has one colour. If the bracket is empty (white), there were no measurements or not sufficient samples for that year. Click on window to close. 
	</p>
	<p>
	<span class='stolpec'>Visualisation</span>
	<br/>
	<img src='images/kml.gif' width='16' height='16' border='0' alt='KML'/> - Download and/or open a <b>kml file</b> with bathing water placemarks. If <u>region</u>, <u>province</u> or <u>bathing water</u> is selected, file contains only bathing waters in selected region, province or bathing water. <b>Kml files</b> are best viewed with <a target='_NEW_WINDOW' href='http://earth.google.com/download-earth.html'>Google Earth</a>.
	<br/>

	<!-- graphs	 -->
	<img src='images/SlanaVodaGraf.jpg' border='0' alt='coastal graph'/> - Graph for <b>coastal</b> bathing waters, there are 2 graph types:
	</p>
        <ul>
		<li><b>Line graph</b> is available when <u>country</u> or <u>region</u> is selected. For each year data points show the percentage of bathing waters compliant to each of 4 statuses. A line is connected between data points to show trends.</li>
		<li><b>Bar graph</b> is available when <u>province</u> is selected. For each year bars show distribution of 4 statuses. If sum is less than 100%, there were bathing waters with no measurements or not sufficient samples for that year.</li>
        </ul>
	<p>
	<img src='images/SlanaVodaGrafX.jpg' border='0' alt='no coastal graph'/> - Indicates that there are no coastal bathing waters in selected <u>country</u>, <u>region</u> or <u>province</u>, so no graph can be displayed.
	<br/>
	<img src='images/SladkaVodaGraf.jpg' border='0' alt='freshwater graph'/> - Graph for <b>freshwater</b> bathing waters, same as for <b>coastal graph</b> applies here.
	<br/>
	<img src='images/SladkaVodaGrafX.jpg' border='0' alt='no freshwater graph'/> - Indicates that there are no freshwater bathing waters in selected <u>country</u>, <u>region</u> or <u>province</u>, so no graph can be displayed.
	</p>
	<div style='position: absolute; top: 0px; right: 10px;'>
		<a onclick='javascript: document.getElementById(\"help_div\").style.display=\"none\";' style='cursor: hand; cursor: pointer; ' >[close]</a>
	</div>

  </div>
  
</div>
";

// DIV, IMG to show graphs  
// 6.6.2008; removed onclick event on div, IE freezes, but only in graph div, on map div is ok: onclick="HideContent(\'graph_div\',\'graph_font\');"
echo '<div align="center" id="graph_div" >';
echo '<div style="position: relative;">';
echo '<span id="graph_font" style="font-weight: bold; color: white; "></span>';
echo '<div style="position: absolute; top: 0px; right: 10px;"><a onclick="HideContent(\'graph_div\',\'graph_font\'); return true;" href="javascript:HideContent(\'graph_div\',\'graph_font\')">[close]</a></div><br/><br/>';
echo '<img id="graph_img" src="images/loading.gif" border="0" alt=""/>';
echo '</div>';
echo '</div>';

// DIV, IMG to show maps (region, province, georegion)  
// 6.6.2008; removed onclick event on div here too just to be sure everything would be ok: onclick="HideContent(\'map_div\',\'map_font\');"
echo '<div align="center" id="map_div" >';
echo '<div style="position: relative;">';
echo '<span id="map_font" style="font-weight: bold; color: white; "></span>';
echo '<div style="position: absolute; top: 0px; right: 10px;"><a onclick="HideContent(\'map_div\',\'map_font\'); return true;" href="javascript:HideContent(\'map_div\',\'map_font\')">[close]</a></div><br/><br/>';
echo '<img id="map_img" src="images/loading.gif" border="0" alt=""/>';
echo '<br/><br/><img id="legend_img" src="provinces/legenda.png" border="0" alt="map of provinces"/>';
echo '</div>';
echo '</div>';


// TABLE ON TOP TO SHOW HELP LINK 
echo "<table id='banner'>";
echo "<tr>";
echo "<th style='text-align:left; width:50%'>";
echo "<a href='kmllink.php' type='application/vnd.google-earth.kml+xml'>Google Earth Network link</a>";
echo "</th>";
echo "<th style='text-align:right; width:50%'>";
echo "<a onclick=\"document.getElementById('help_div').style.display='block'; \">Help on using viewer</a>";
echo "</th>";
echo "</tr>\n";
echo "</table>\n";


$counter = 0;				// row counter
$td_height = 25;		// td height (must be fixed for graph positioning above/below country to work)
$eu27_stations = array();	// array to hold number of stations

// MAIN TABLE
echo "<table border='0' cellpadding='0' cellspacing='0'>\n";
echo "<col style='width: 141px'/>\n"; // Add 2 times 2px padding + 1px border for real cell width
echo "<col style='width: 198px'/>\n"; // Add 2 times 2px padding + 1px border for real cell width
echo "<col style='width: 212px'/>\n"; // Add 2 times 2px padding + 1px border for real cell width
echo "<col style='width: 277px'/>\n"; // Add 2 times 2px padding + 1px border for real cell width
echo "<col style='width: 103px'/>\n"; // Add 2 times 2px padding + 1px border for real cell width

// IMAGE ABOVE THE MAIN TABLE WITH DATA
echo "<tr><td style='padding: 0px; margin: 0px;' colspan='5'><img width='955' height='80' src='images/Flash1.jpg' border='0' alt=''/></td>\n</tr>\n";

echo "<tr><th>Country</th>";
echo "<th>Region</th>";
echo "<th>Province</th>";
echo "<th>Bathing water</th>";
echo "<th>Visualisation</th></tr>\n";

// FIRST DATA ROW: EU 27
echo "<tr id='eu_row' style='";
if ($_GET['GeoRegion'] != '')	echo "background-color: #D7F5FF;";
else							echo "background-color: #F7F7F7;";
echo "'>";
echo "  <td class='country' height='".$td_height."'>";
if (file_exists("images/flags/Europe.jpg"))
	echo "<img src='images/flags/Europe.jpg' border='0' alt='Europe'/> ";
echo "EU 27";
echo "</td>\n";

// EU 27-GEOGRAPHIC REGION
echo "  <td>";
echo "<select style='width: 176px;' name='EU27_georegion' id='EU27_georegion' onchange='document.location=\"index.php?cc=EU27&amp;GeoRegion=\" + this.value'>\n";
$sql_georegion = "
		SELECT geographic, COUNT(*) AS no_of_stations
		FROM bwd_stations
		GROUP BY geographic	
";
$result_georegion = mysql_query($sql_georegion);
echo "<option value='' selected='selected'>--- Geographic region ---</option>";
while ($myrow_georegion = mysql_fetch_array($result_georegion)) {
	$eu27_stations[$myrow_georegion['geographic']] = $myrow_georegion['no_of_stations'];
	if ($myrow_georegion['geographic'] != '') {
		echo "<option value='".$myrow_georegion['geographic']."'";
		if ($myrow_georegion['geographic'] == $_GET['GeoRegion']) 	echo " selected='selected'";
		echo ">".substr($myrow_georegion['geographic'],29)."</option>\n";
	} // END if
} // END while
echo "</select>\n";
//  echo "&nbsp;";
  
  // EU 27-GEOGRAPHIC REGION MAP
if (file_exists("regions/geographic_region.png")) {
	echo "<a title='Geographic region map' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','graph_font','graph_img','regions/geographic_region.png','EU 27 - Geographic region map','725px','735px','220px','160px'); \"><img src='images/Regije.gif' alt='Geographic region map'/></a>";
}
echo "</td>\n";

echo "  <td>&nbsp;</td>\n";

echo "  <td>";
echo "<span style='color:gray'>";
if ($_GET['GeoRegion'] != '')	echo $eu27_stations[$_GET['GeoRegion']];
else									echo array_sum($eu27_stations);
echo " bathing waters";
echo "</span>";
echo "</td>\n";

echo "  <td align='right'>";
// EU 27-GOOGLE EARTH
if ($_GET['cc'] == 'EU27' && $_GET['GeoRegion'] != '')	{
	$title_string = "EU 27: ".substr($_GET['GeoRegion'],29);
} else {
	$title_string = "EU 27";
}

echo "<a title='Google Earth KML - ".$title_string."' href='kml_export.php?cc=EU27&amp;GeoRegion=".$_GET['GeoRegion']."'><img src='images/kml.gif' width='16' height='16' border='0'  alt='Google Earth KML - ".$title_string."'/></a>";
echo "&nbsp;";

// EU 27-GRAPH COASTAL
echo "<a title='Quality of coastal bathing waters' style='cursor:pointer; cursor:hand;' onclick=\"ShowContent('graph_div','graph_font','graph_img','line_jpgraph.php?Country=EU27&amp;GeoRegion=".$_GET['GeoRegion']."&amp;type=coast','','950px','750px','10px','160px'); return true;\"><img src='images/SlanaVodaGraf.jpg' border='0'  alt='Quality of coastal bathing waters'/></a>";
echo "&nbsp;";
// EU 27-GRAPH FRESHWATER
echo "<a title='Quality of freshwater bathing waters' style='cursor:pointer; cursor:hand;' onclick=\"ShowContent('graph_div','graph_font','graph_img','line_jpgraph.php?Country=EU27&amp;GeoRegion=".$_GET['GeoRegion']."&amp;type=fresh','','950px','750px','10px','160px'); return true;\" ><img  src='images/SladkaVodaGraf.jpg' border='0' alt='Quality of freshwater bathing waters'/></a>";
echo "</td>\n";
echo "</tr>\n";


// CREATE SQL TO GET ALL THE COUNTRY-DATA
$sql = '
  SELECT 
      UPPER(c.Country) AS Country, s.cc, c.NationalName AS NationalName,
      COUNT(IF(SeaWater = "O",1,NULL)) AS "coast_stations",
      COUNT(IF(SeaWater = "N",1,NULL)) AS "freshwater_stations"
  FROM bwd_stations s
  LEFT JOIN countrycodes_iso c ON s.cc = c.ISO2 ';

if ($_GET['GeoRegion'] != '')	
	$sql .= "WHERE geographic = '".$_GET['GeoRegion']."' ";

$sql .= 'GROUP BY s.cc ORDER BY c.Country';

$result = mysql_query($sql) or die(mysql_error()."<br/>".$sql);

// Initialise variables
$country_coast_stations = array();
$country_freshwater_stations = array();
$region_freshwater_stations = array();
$region_coast_stations = array();
$province_coast_stations = array();
$province_freshwater_stations = array();

// LOOP THROUGH COUNTRY-DATA
while ($myrow = mysql_fetch_array($result)) {

	$country_coast_stations[$counter] = 0;
	$country_freshwater_stations[$counter] = 0;
	$region_freshwater_stations[$counter] = 0;
	$region_coast_stations[$counter] = 0;
	$province_coast_stations[$counter] = 0;
	$province_freshwater_stations[$counter] = 0;

	echo "<tr id='tr_".$counter."' class='";
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "selected";
	else {
		if ($counter % 2 == 1)  echo "alternate";
		else                    echo "";
	}
	echo "'>\n";

    // COUNTRY
    echo "  <td title='".$myrow['NationalName']."' class='country' height='".$td_height."'>";
	$filename_zastava = "images/flags/".ucfirst(strtolower(changeChars($myrow['Country'],"_"))).".jpg";
	if (file_exists($filename_zastava))
		echo "<img src='".$filename_zastava."' border='0' alt='".$myrow['NationalName']."'/> ";
	echo $myrow['Country'];
	echo "</td>\n";
    
    // Number of bathing places
    $country_coast_stations[$counter] = $myrow['coast_stations'];
    $country_freshwater_stations[$counter] = $myrow['freshwater_stations']; 

    // REGION
    echo "  <td>";
	echo "<select style='width: 176px;' name='".$myrow['cc']."_region' id='".$myrow['cc']."_region' onchange='document.location=\"index.php?cc=".$myrow['cc']."&amp;GeoRegion=".$_GET['GeoRegion']."&amp;Region=\" + this.value'>\n";
	$sql_region = "
          SELECT Region, COUNT(IF(SeaWater = 'O',1,NULL)) AS 'coast_stations', COUNT(IF(SeaWater = 'N',1,NULL)) AS 'freshwater_stations'
          FROM bwd_stations ";
	if ($_GET['GeoRegion'] != '')	
		  $sql_region .= "WHERE geographic = '".$_GET['GeoRegion']."' AND ";
	else 
		$sql_region .= "WHERE ";
	$sql_region .= "
           cc = '".$myrow['cc']."'
          GROUP BY Region
          ORDER BY Region
	";
	$result_region = mysql_query($sql_region);
	echo "<option value='' selected='selected'>--- Region ---</option>\n";
	while ($myrow_region = mysql_fetch_array($result_region)) {
		echo "<option value='".$myrow_region['Region']."'";
		if ($myrow['cc'] == $_GET['cc'] && $myrow_region['Region'] == $_GET['Region']) {
			echo " selected='selected'";
			$region_freshwater_stations[$counter]  = $myrow_region['freshwater_stations'];
			$region_coast_stations[$counter]  = $myrow_region['coast_stations'];
		}
		echo ">".$myrow_region['Region']."</option>\n";
	}
	echo "</select>\n";
//  echo "&nbsp;";
      
	// SHOW BUTTON FOR REGION-MAP IF MAP EXISTS 
	// set region-map position - shift from top 
	if ($counter < 20)		$top_shift = 190+($counter*($td_height+5));
	else					$top_shift = ($counter*($td_height+5))-585;

	if (file_exists("regions/pdf_".strtolower($myrow['cc'])."_regions.png")) {
		echo "<a title='".$myrow['Country']." region map' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','graph_font','graph_img','regions/pdf_".strtolower($myrow['cc'])."_regions.png','".$myrow['Country']." - region map','725px','735px','150px','".$top_shift."px'); \"><img src='images/Regije.gif' alt='".$myrow['Country']." - region map'/></a>";
	}
	echo "</td>\n";

	// PROVINCE
	echo "  <td>";
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '') {
		echo "<select style='visibility: visible; width: 190px;' ";
		echo "name='".$myrow['cc']."_province' id='".$myrow['cc']."_province' onchange='document.location=\"index.php?cc=".$_GET['cc']."&amp;GeoRegion=".$_GET['GeoRegion']."&amp;Region=".$_GET['Region']."&amp;Province=\" + (this.value);'>\n";
		echo "<option value='' selected='selected'>--- Province ---</option>\n";
		$sql_province = "
			SELECT Province, COUNT(IF(SeaWater = 'O',1,NULL)) AS 'coast_stations', COUNT(IF(SeaWater = 'N',1,NULL)) AS 'freshwater_stations'
			FROM bwd_stations ";
		if ($_GET['GeoRegion'] != '')	$sql_province .= "WHERE geographic = '".$_GET['GeoRegion']."' AND ";
		else
			$sql_province .= "WHERE ";
		$sql_province .= "
			 cc = '".$_GET['cc']."'
			AND Region LIKE '".changeChars($_GET['Region'],"%")."'
			GROUP BY Province
			ORDER BY Province
		";
		$result_province = mysql_query($sql_province);
		while ($myrow_province = mysql_fetch_array($result_province)) {
			echo "<option value='".convertUTFtoHTML($myrow_province['Province'])."'";
			
			// 10.5.2008; če je enojni apostrof ' v stringu, tukaj vržem ven escape character: L\'AQUILA -> L'AQUILA
			if ($myrow_province['Province'] == str_replace("\\","",$_GET['Province'])) {
				echo " selected='selected'";
				$province_freshwater_stations[$counter] = $myrow_province['freshwater_stations'];
				$province_coast_stations[$counter]  = $myrow_province['coast_stations'];
			}
			echo ">".$myrow_province['Province']."</option>\n";
		}
		echo "</select>\n";
//      echo "&nbsp;";
	  
		// SHOW BUTTON FOR PROVINCE-MAP IF MAP EXISTS 
		// set region-map position - shift from top 
		if ($counter < 24)		$top_shift = 190+($counter*($td_height+5));
		else					$top_shift = ($counter*($td_height+5))-720;

		if ($_GET['Region'] == $myrow['Country'])	{
			$file_province_map = "provinces/".strtolower($myrow['cc'])."_p.png";
		} else {
			$file_province_map = "provinces/".strtolower($myrow['cc'])."_p_".strtolower(changeChars(replaceUTFChars($_GET['Region']),"_")).".png";
		}

// debug output
// echo $file_province_map;

		if ($_GET['Region'] != '' && file_exists($file_province_map )) {
			echo "<a id='".$myrow['cc']."_province_link' ";
			if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "style='visibility: visible; cursor:pointer; cursor: hand;'";
			else                                                      echo "style='visibility: hidden; cursor:pointer; cursor: hand;'";
			echo "title='".$myrow['Country']." province map'  onclick=\"ShowContent('map_div','map_font','map_img','".$file_province_map ."','".$myrow['Country']." - ".$_GET['Region']." - province map','725px','925px','150px','".$top_shift."px'); \"><img src='images/Regije.gif' border='0' alt='".$myrow['Country']." - province map'/></a>";
		}
	}
	else
        echo "&nbsp;";
    echo "</td>\n";
    
    
    // BATHING PLACES
	
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '')
		$sum_bw = $province_coast_stations[$counter]+$province_freshwater_stations[$counter];
	elseif ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')
		$sum_bw = $region_coast_stations[$counter]+$region_freshwater_stations[$counter];
	else
		$sum_bw = $country_coast_stations[$counter]+$country_freshwater_stations[$counter]; 
	
	// set graph position - shift from top 
	if ($counter < 14)		$top_shift = 190+($counter*($td_height+5));
	else					$top_shift = ($counter*($td_height+5))-115;
	
	echo "  <td id='td_".$counter."'>";
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '')
	    echo "";
	else {
	    echo "<span style='color:gray'>";
		echo "$sum_bw bathing waters ";
		if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')        echo "in selected region";
		echo "</span>";
	}

	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '') {
		echo "<select style='display: block; width: 100%' ";
		echo "name='".$myrow['cc']."_bplace' id='".$myrow['cc']."_bplace' ";
		
		echo "onchange=\"if(this.value != '') {HideContent('map_div','map_font'); ShowContent('graph_div','graph_font','graph_img','bar_jpgraph.php?cc=".$myrow['cc']."&amp;Country=".$myrow['Country']."&amp;GeoRegion=".$_GET['GeoRegion']."&amp;Region=".convertUTFtoHTML($_GET['Region'])."&amp;Province=".convertUTFtoHTML($_GET['Province'])."&amp;BathingPlace=' + document.getElementById('".$myrow['cc']."_bplace').value,'','550px','270px','300px','".$top_shift."px'); return true;} else {HideContent('graph_div','graph_font');}\" ";
		
		echo ">\n";
		echo "<option value='' selected='selected'>--- ".($province_coast_stations[$counter]+$province_freshwater_stations[$counter])." bathing waters in selected province ---</option>\n";
		if ($_GET['cc'] == $myrow['cc']) {
			$sql_bplace = "
			  SELECT numind, Prelev 
			  FROM bwd_stations ";
			  if ($_GET['GeoRegion'] != '')	$sql_bplace .= "WHERE geographic = '".$_GET['GeoRegion']."' AND ";
			  else 							$sql_bplace .= "WHERE ";
			  $sql_bplace .= "
				cc = '".$_GET['cc']."' 
			   AND Region LIKE '".changeChars($_GET['Region'],"%")."' 
			   AND Province LIKE '".changeChars($_GET['Province'],"%")."' 
			   ORDER BY Prelev
			  ";
			$result_bplace = mysql_query($sql_bplace);
			while ($myrow_bplace = mysql_fetch_array($result_bplace)) {
				echo "<option value='".$myrow_bplace['numind']."'";
				if ($myrow_bplace['numind'] == $_GET['BathingPlace'])  echo " selected='selected'";
				echo ">".$myrow_bplace['Prelev']."</option>";           
			}
	    }
		echo "</select>";
	}
    else    echo "&nbsp;";
    echo "</td>\n";

    // VISUALISATION BUTTONS
    echo "  <td align='right'>";

	// GENERATES KML LINK
	$link_za_kml = "kml_export.php?cc=".$myrow['cc'];
	if ($_GET['GeoRegion'] != "")
		$link_za_kml .= "&amp;GeoRegion=".$_GET['GeoRegion'];
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != "")
        $link_za_kml .= "&amp;Region=".htmlspecialchars($_GET['Region'], ENT_NOQUOTES);
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Province'] != "")
        $link_za_kml .= "&amp;Province=".htmlspecialchars($_GET['Province'], ENT_NOQUOTES);
	if ($myrow['cc'] == $_GET['cc'] && $_GET['BathingPlace'] != "")
        $link_za_kml .= "&amp;BathingPlace=".$_GET['BathingPlace'];
	$string_title_kml = $myrow['Country'];
	if ($_GET['Region'] != "" && $_GET['cc'] == $myrow['cc'])
		$string_title_kml .= ", ".$_GET['Region'];
	if ($_GET['Province'] != "" && $_GET['cc'] == $myrow['cc'])
		$string_title_kml .= ", ".$_GET['Province'];
	//if ($_GET['BathingPlace'] != "" && $_GET['cc'] == $myrow['cc']) $string_title_kml .= ", ".$_GET['BathingPlace'];

    // PDF LINK; 20.5.2008; excluded till further
	/*
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != "") {
		$link_za_pdf = "pdf_maps/pdf_".strtolower($myrow['cc'])."_".strtolower(changeChars(replaceUTFChars($_GET['Region']),"_")).".pdf";
		$title_za_pdf = "PDF map - ".$myrow['Country'].", ".$_GET['Region'];
	} else {
		$link_za_pdf = "pdf_maps/pdf_".strtolower($myrow['cc']).".pdf";
		$title_za_pdf = "PDF map - ".$myrow['Country'];
	}
	*/

	// GRAPH LINK, BAR GRAPH FOR PROVINCES, LINE GRAPH FOR OTHERS
	$link_za_graf = "line_jpgraph.php?cc=".$myrow['cc']."&amp;Country=".$myrow['Country']."&amp;GeoRegion=".$_GET['GeoRegion'];
	if ($_GET['Region'] != "" && $_GET['cc'] == $myrow['cc'])
        $link_za_graf .= "&amp;Region=".$_GET['Region'];
      
	if (($_GET['Province'] != "") && $_GET['cc'] == $myrow['cc'])
        $link_za_graf = "bar_jpgraph.php?cc=".$myrow['cc']."&amp;Country=".$myrow['Country']."&amp;GeoRegion=".$_GET['GeoRegion']."&amp;Region=".$_GET['Region']."&amp;Province=".$_GET['Province'];

	// 10.5.2008; CHANGE DEFAULT ICON WITH DISABLED-ICON, IF THERE IS NO COASTAL/FRESHWATER BW IN COUNTRY/REGION/PROVINCE
	$coast_disabled = 0;
	$fresh_disabled = 0;
	if ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '') {
        if ($province_coast_stations[$counter] == 0)       $coast_disabled = 1; 
        if ($province_freshwater_stations[$counter] == 0)  $fresh_disabled = 1;
	} elseif ($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '') {
        if ($region_coast_stations[$counter] == 0)         $coast_disabled = 1; 
        if ($region_freshwater_stations[$counter] == 0)    $fresh_disabled = 1; 
	} else {
        if ($myrow['coast_stations'] == 0)       $coast_disabled = 1; 
        if ($myrow['freshwater_stations'] == 0)  $fresh_disabled = 1; 
	}


	$baseurl = "http://".$_SERVER['SERVER_NAME']."/".substr($_SERVER['REQUEST_URI'],1 , strrpos($_SERVER['REQUEST_URI'],"/"));
	// LIVE MAPS
	if ($sum_bw < 200) {
		echo "<a title='Live Maps - ".$string_title_kml."' href='http://maps.live.com?mapurl=".$baseurl."$link_za_kml'><img src='images/livemaps.png' border='0' alt='Live Maps - ".$string_title_kml."'/></a>";
	}
	// GOOGLE MAPS
	if ($sum_bw < 200) {
		// Google wants doubly encoded spaces (which is probably correct behaviour)
        $encoded_link = str_replace(" ","%2520", str_replace("&amp;","%26",$link_za_kml));
		echo "<a title='Google Maps - ".$string_title_kml."' href='http://maps.google.com?q=".$baseurl.$encoded_link."'><img src='images/googlemaps.png' border='0' alt='Google Maps - ".$string_title_kml."'/></a>";
	}
	// GOOGLE EARTH
	echo "<a title='Google Earth KML - ".$string_title_kml."' href='$link_za_kml'><img src='images/kml.gif' width='16' height='16' border='0' alt='Google Earth KML - ".$string_title_kml."'/></a>";
	echo "&nbsp;";
      
	// PDF; 20.5.2008; excluded till further
	/*
	if (file_exists($link_za_pdf))
	  echo "<a title='".$title_za_pdf."' target='NewWindow' href='".$link_za_pdf."'><img src='images/PDFmala.gif' border='0' alt='".$title_za_pdf."'/></a>";
	else 
	  echo "<img title='No PDF map for this region' src='images/PDFmalaX.gif' border='0' alt='No PDF map for this region'/>";
	echo "&nbsp;";
	*/

	// GRAPH
	// set graph position - shift from top 
	if ($counter < 14)		$top_shift = 190+($counter*($td_height+5));
	else					$top_shift = ($counter*($td_height+5))-295;
	  
	// IF NO COASTAL BW: ONLY IMG ONMOUSEOVER
	if ($coast_disabled) {
		$title_graf = "No coastal bathing waters in this Country/Region/Province";
		echo "<img alt='".$title_graf."' title='".$title_graf."' src='images/SlanaVodaGrafX.jpg' border='0'/>";
	} else {
		$title_graf = "Quality of coastal bathing waters in this Country/Region/Province"; // - ".$myrow['Country'];
		echo "<a title='".$title_graf."' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','graph_font','graph_img','".convertUTFtoHTML($link_za_graf)."&amp;type=coast','','640px','500px','180px','".$top_shift."px'); \"><img src='images/SlanaVodaGraf.jpg' border='0' alt='".$title_graf."'/></a>";
	}
	echo "&nbsp;";
        
	// IF NO FRESHWATER BW: ONLY IMG ONMOUSEOVER
	if ($fresh_disabled) {
		$title_graf = "No freshwater bathing waters in this Country/Region/Province";
		echo "<img alt='".$title_graf."' title='".$title_graf."' src='images/SladkaVodaGrafX.jpg' border='0'/>";
	} else {
		$title_graf = "Quality of freshwater bathing waters in this Country/Region/Province"; // - ".$myrow['Country'];
		echo "<a title='".$title_graf."' style='cursor:pointer; cursor: hand;'  onclick=\"ShowContent('graph_div','graph_font','graph_img','".convertUTFtoHTML($link_za_graf)."&amp;type=fresh','','640px','500px','180px','".$top_shift."px'); \"><img src='images/SladkaVodaGraf.jpg' border='0' alt='".$title_graf."'/></a>";
	}
 
    echo "</td>\n";
	echo "</tr>\n";
	flush();
	$counter++;
}


echo "</table>\n";

?>
<p><img src='images/kml.gif' width='16' height='16' border='0'  alt='KML icon'/>&nbsp;Don't have Google Earth? Download it here:
<a target='_NEW_WINDOW' href='http://earth.google.com/download-earth.html'>http://earth.google.com/download-earth.html</a></p>
<?php
//echo "<p><img src='images/PDFmala.gif' border='0' />&nbsp;Adobe Acrobat Reader - download it here: <a target='_NEW_WINDOW' href='http://www.adobe.com/products/acrobat/readstep2.html'>http://www.adobe.com/products/acrobat/readstep2.html</a></p>";
?>

</body>
</html>
