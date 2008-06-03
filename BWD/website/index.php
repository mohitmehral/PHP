<?php

/* 

BWD water quality data/map viewer: MAIN FILE WITH COUNTRY LISTING, MAPS, GRAPHS, ETC.

21.3.2008; first version

*/

include('config.php');
include('functions.php');

// MYSQL CONNECT
$db = mysql_connect($host, $dbuser,$dbpass);
mysql_select_db($database,$db);
// 5.5.2008; utf8: this must be included fot utf-8 charset
mysql_query("SET NAMES 'utf8'");

header('Content-Type: text/html; charset=utf-8');

?>
<html>
<head>
  <title>Bathing water quality data/map viewer</title>
  <link href="template.css" rel="stylesheet" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  
  <script type="text/javascript" language="JavaScript"><!--
    // JS FUNCTIONS TO SHOW DIV WITH GRAPH, MAP 
	function HideContent(div_id) {
      if(div_id.length < 1) { return; }
      document.getElementById(div_id).style.display = "none";
      document.getElementById('graph_font').innerHTML = "";
      // for Firefox to show "Loading ..." image by default when browsing different graphs, not closing the div
      document.getElementById('graph_img').src = "images/loading.gif";
    }
    
    // div_id- ids from div, iframe elements
    // src_html - source (image) to show in div_id
	// width, height - to specify width and height of div window
    // left - how far from the left border is positioned
	// img_id - img element to show image for maps/graphs
	// div_title - title text to put in id=map_font element
    function ShowContent(div_id, img_id, src_html, div_title, width, height, left, top) {
      if(div_id.length < 1) { return; }
	  // 1. first set width, height, position of div and show it (block)
      document.getElementById(div_id).style.width = width;
      document.getElementById(div_id).style.height = height;
      document.getElementById(div_id).style.left = left;
      document.getElementById(div_id).style.top = top;
      document.getElementById(div_id).style.display = "block";
      // 2. set new headline for div (only for region/province maps)
	  if(div_title != '') {
		document.getElementById('map_font').innerHTML = div_title;
		document.getElementById('graph_font').innerHTML = div_title;
	  }
      // 3. set "Loading ..." gif in div
      document.getElementById(img_id).src = "images/loading.gif";
      // 4. when the source image is loaded, set this image in div
      //document.getElementById(font_id).innerHTML = div_title;
      document.getElementById(img_id).src = src_html;
    }

    //-->
  </script>
</head>

<body>
<?php

// HELP DIV
echo "
<div id='help_div' onclick='document.getElementById(\"help_div\").style.display=\"none\";' >
  <div style='position: relative; '>
	<h1>Bathing water quality data/map viewer - quick help</h1>
	<!-- TODO splošni opis -->
	<!-- <br/><br/> -->

	<!-- opis stolpcev -->
	<span class='stolpec'>&nbsp;Country&nbsp;</span> - All EU countries are listed, drag the mouse over country to display country name in native language.
	<br/><br/>
	<span class='stolpec'>&nbsp;Region&nbsp;</span> - Bigger countries are divided into regions, select a <u>region</u> to narrow the search.
	<br/>
	<img src='images/Regije.gif' border='0'/>&nbsp;\"region icon\" - Shows a map with all regions in selected country. Click on map to close.
	<br/><br/>
	<span class='stolpec'>&nbsp;Province&nbsp;</span> - Bigger <u>regions</u> are divided into <u>provinces</u>, select a <u>province</u> to narrow the search.
	<br/>
	<img src='images/Regije.gif' border='0'/>&nbsp;\"province icon\" - Smaller countries have only one <u>region</u> (no \"region icon\"), for these countries a <u>province map</u> is provided. Click on map to close.
	<br/><br/>
	<span class='stolpec'>&nbsp;Bathing water&nbsp;</span> - Initially number of bathing waters in each country are displayed here. When <u>region</u> or <u>province</u> is selected, number of bathing waters in selected <u>region</u> / <u>provice</u> is shown here. Select a <u>province</u> and <b>select box</b> is shown here: 
	<li>select a <u>bathing water</u> and a small window with water quality info will pop up. Brackets indicate status for each year, each status has one colour. If the bracket is empty (white), there were no measurements or not sufficient samples for that year. Click on window to close. 
	<br/><br/>
	<span class='stolpec'>&nbsp;Visualization&nbsp;</span>
	<br/>
	<img src='images/GoogleEarthMali.gif' border='0'/> - Download and/or open a <b>kml file</b> with bathing water placemarks. If <u>region</u>, <u>province</u> or <u>bathing water</u> is selected, file contains only bathing waters in selected region, province or bathing water. <b>Kml files</b> are best viewed with <a target='_NEW_WINDOW' href='http://earth.google.com/download-earth.html'>Google Earth</a>.
	<br/>

	<!-- grafi	 -->
	<img src='images/SlanaVodaGraf.jpg' border='0'/> - Graph for <b>coastal</b> bathing waters, there are 2 graph types:
		<li><b>Line graph</b> is available when <u>country</u> or <u>region</u> is selected. For each year data points show the percentage of bathing waters compliant to each of 4 statuses. A line is connected between data points to show trends.
		<li><b>Bar graph</b> is available when <u>province</u> is selected. For each year bars show distribution of 4 statuses. If sum is less than 100%, there were bathing waters with no measurements or not sufficient samples for that year.
	<br/>
	<img src='images/SlanaVodaGrafX.jpg' border='0'/> - Indicates that there are no coastal bathing waters in selected <u>country</u>, <u>region</u> or <u>province</u>, so no graph can be displayed.
	<br/>
	<img src='images/SladkaVodaGraf.jpg' border='0'/> - Graph for <b>freshwater</b> bathing waters, same as for <b>coastal graph</b> applies here.
	<br/>
	<img src='images/SladkaVodaGrafX.jpg' border='0'/> - Indicates that there are no freshwater bathing waters in selected <u>country</u>, <u>region</u> or <u>province</u>, so no graph can be displayed.
	
	<div style='position: absolute; top: 0px; right: 10px;'>
		<a onclick='javascript: document.getElementById(\"help_div\").style.display=\"none\";' style='cursor: hand; cursor: pointer; ' >[close]</a>
	</div>

  </div>
  
</div>
";

// DIV, IMG to show graphs  
echo '<div align="center" id="graph_div" onclick="HideContent(\'graph_div\');" >';
echo '<div style="position: relative;">';
echo '<span id="graph_font" style="font-weight: bold; color: white; "></span>';
echo '<div style="position: absolute; top: 0px; right: 10px;"><a onclick="HideContent(\'graph_div\'); return true;" href="javascript:HideContent(\'graph_div\')">[close]</a></div><br/><br/>';
echo '<img id="graph_img" src="images/loading.gif" border="0" />';
echo '</div>';
echo '</div>';

// DIV, IMG to show maps (region, province, georegion)  
echo '<div align="center" id="map_div" onclick="HideContent(\'map_div\');" >';
echo '<div style="position: relative;">';
echo '<span id="map_font" style="font-weight: bold; color: white; "></span>';
echo '<div style="position: absolute; top: 0px; right: 10px;"><a onclick="HideContent(\'map_div\'); return true;" href="javascript:HideContent(\'map_div\')">[close]</a></div><br/><br/>';
echo '<img id="map_img" src="images/loading.gif" border="0" />';
echo '<br/><br/><img id="legend_img" src="provinces/legenda.png" border="0" />';
echo '</div>';
echo '</div>';


// TABLE ON TOP TO SHOW HELP LINK 
echo "<table style='border: 0px;' border='0' cellpadding='0' cellspacing='0'>";
echo "<th style='background-color: white; color: black; border: 0px; line-height: 18px;' colspan='6' width='955' align='right'>";
echo "<a style='text-decoration: none; color: #00446A; cursor: hand; cursor: pointer;' onclick=\"document.getElementById('help_div').style.display='block'; \">Help on using viewer</a>";
echo "</th>";
echo "</table>";


$counter = 0;				// row counter
$td_height = 30;		// td height (must be fixed for graph positioning above/below country to work)
$eu27_stations = array();	// array to hold number of stations

// MAIN TABLE
echo "<table border='0' cellpadding='0' cellspacing='0'>";

// IMAGE ABOVE THE MAIN TABLE WITH DATA
echo "<tr><td style='padding: 0px; margin: 0px;' colspan='5'><img width='954' height='80' src='images/Flash1.jpg' border='0' alt=''/></td></tr>";

echo "<th width='145'>Country</th>";
echo "<th width='210'>Region</th>";
echo "<th width='220'>Province</th>";
echo "<th width='300'>Bathing water</th>";
echo "<th width='75'>Visualization</th>";

// FIRST DATA ROW: EU 27
echo "<tr style='";
if($_GET['GeoRegion'] != '')	echo "background-color: #D7F5FF;";
else							echo "background-color: #F7F7F7;";
echo "'>";
echo "<td style='border-bottom: 2px #3180BB solid' height='".$td_height."'>";
	if(file_exists("images/flags/Europe.jpg")) echo "<img src='images/flags/Europe.jpg' border='0' />&nbsp;";
	echo "<b>EU 27</b>";
echo "</td>";

// EU 27-GEOGRAPHIC REGION
echo "<td style='border-bottom: 2px #3180BB solid'>";
  echo "<select style='width: 180px;' name='EU27_georegion' id='EU27_georegion' onchange='document.location=\"index.php?cc=EU27&GeoRegion=\" + this.value'>";
	$sql_georegion = "
		SELECT geographic, COUNT(*) AS no_of_stations
		FROM numind_geographic
		GROUP BY geographic	
	";
	$result_georegion = mysql_query($sql_georegion);
	echo "<option value='' selected>--- Geographic region ---</option>";
	while($myrow_georegion = mysql_fetch_array($result_georegion))  {
	  $eu27_stations[$myrow_georegion['geographic']] = $myrow_georegion['no_of_stations'];
	  if($myrow_georegion['geographic'] != '')	{
		echo "<option value='".$myrow_georegion['geographic']."' ";
		  if($myrow_georegion['geographic'] == $_GET['GeoRegion']) 	echo "SELECTED";
		echo ">".substr($myrow_georegion['geographic'],29)."</option>";
	  } // END if
	} // END while
  echo "</select>";
  echo "&nbsp;";
  
  // EU 27-GEOGRAPHIC REGION MAP
  if(file_exists("regions/geographic_region.png"))  {
	echo "<a alt='Geographic region map' title='Geographic region map' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','graph_img','regions/geographic_region.png','EU 27 - Geographic region map','725px','735px','220px','160px'); \"><img src='images/Regije.gif' /></a>";
  }
echo "</td>";

echo "<td style='border-bottom: 2px #3180BB solid'>&nbsp;</td>";

echo "<td style='border-bottom: 2px #3180BB solid' >";
  echo "<span style='color:gray'>&nbsp;";
	if($_GET['GeoRegion'] != '')	echo $eu27_stations[$_GET['GeoRegion']];
	else							echo array_sum($eu27_stations);
	echo " bathing waters";
  echo "</span>";
echo "</td>";

echo "<td style='border-bottom: 2px #3180BB solid' align='right'>";
	// EU 27-GOOGLE EARTH
	if($_GET['cc'] == 'EU27' && $_GET['GeoRegion'] != '')	{
		$title_string = "EU 27: ".substr($_GET['GeoRegion'],29);
	} else {
		$title_string = "EU 27";
	}
	
	echo "<a alt='Google Earth KML - ".$title_string."' title='Google Earth KML - ".$title_string."' onclick='document.location = \"kml_export.php?cc=EU27&GeoRegion=".$_GET['GeoRegion']."\"; return false;' href=''><img src='images/GoogleEarthMali.gif' border='0' /></a>";
	echo "&nbsp;";

	// EU 27-GRAPH COASTAL
	echo "<a alt='Quality of coastal bathing waters' title='Quality of coastal bathing waters' style='cursor:pointer; cursor:hand;' onclick=\"ShowContent('graph_div','graph_img','line_jpgraph.php?Country=EU27&GeoRegion=".$_GET['GeoRegion']."&type=coast','','950px','750px','10px','160px'); return true;\"><img src='images/SlanaVodaGraf.jpg' border='0' /></a>";
	echo "&nbsp;";
	// EU 27-GRAPH FRESHWATER
	echo "<a alt='Quality of freshwater bathing waters' title='Quality of freshwater bathing waters' style='cursor:pointer; cursor:hand;' onclick=\"ShowContent('graph_div','graph_img','line_jpgraph.php?Country=EU27&GeoRegion=".$_GET['GeoRegion']."&type=fresh','','950px','750px','10px','160px'); return true;\" ><img  src='images/SladkaVodaGraf.jpg' border='0' /></a>";
echo "</td>";
echo "</tr>";


// CREATE SQL TO GET ALL THE COUNTRY-DATA
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

$result = mysql_query($sql) or die(mysql_error()."<br/>".$sql);


// LOOP THROUGH COUNTRY-DATA
while($myrow = mysql_fetch_array($result))  {

  echo "<tr id='tr_".$counter."' class='";
  if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "selected";
  else {
    if($counter % 2 == 1)  echo "alternate";
    else                    echo "";
  }
  echo "'>";

    // COUNTRY
    echo "<td alt='".$myrow['NationalName']."' title='".$myrow['NationalName']."' style='cursor:help;' height='".$td_height."'>";
	$filename_zastava = "images/flags/".ucfirst(strtolower(changeChars($myrow['Country'],"_"))).".jpg";
	if(file_exists($filename_zastava)) echo "<img src='".$filename_zastava."' border='0' />&nbsp;";
	echo "<b>".$myrow['Country']."</b>";
	echo "</td>";
    
    // Number of bathing places
    $country_coast_stations[$counter] = $myrow['coast_stations'];
    $country_freshwater_stations[$counter] = $myrow['freshwater_stations']; 

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
        echo "<option value='' selected>--- Region ---</option>";
        while($myrow_region = mysql_fetch_array($result_region))  {
          echo "<option value='".$myrow_region['Region']."' ";
          if($myrow['cc'] == $_GET['cc'] && $myrow_region['Region'] == $_GET['Region'])  {
            echo "SELECTED";
            $region_freshwater_stations[$counter]  = $myrow_region['freshwater_stations'];
            $region_coast_stations[$counter]  = $myrow_region['coast_stations'];
          }
          echo ">".$myrow_region['Region']."</option>";
        }
      echo "</select>";
      echo "&nbsp;";
      
      // SHOW BUTTON FOR REGION-MAP IF MAP EXISTS 
	  // set region-map position - shift from top 
	  if($counter < 20)		$top_shift = 190+($counter*$td_height);
	  else					$top_shift = ($counter*$td_height)-585;

	  if(file_exists("regions/pdf_".strtolower($myrow['cc'])."_regions.png"))  {
        echo "<a alt='".$myrow['Country']." - region map' title='".$myrow['Country']." region map' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','graph_img','regions/pdf_".strtolower($myrow['cc'])."_regions.png','".$myrow['Country']." - region map','725px','735px','150px','".$top_shift."px'); \"><img src='images/Regije.gif' /></a>";
      }
    echo "</td>";
  
    // PROVINCE
    echo "<td>";
      echo "<select ";
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "style='visibility: visible; width: 190px;' ";
      else                                                      echo "style='visibility: hidden; width: 190px;' ";
      echo "name='".$myrow['cc']."_province' id='".$myrow['cc']."_province' onchange='document.location=\"index.php?cc=".$_GET['cc']."&GeoRegion=".$_GET['GeoRegion']."&Region=".$_GET['Region']."&Province=\" + (this.value);'>";
      echo "<option value='' selected>--- Province ---</option>";
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
              $province_freshwater_stations[$counter] = $myrow_province['freshwater_stations'];
              $province_coast_stations[$counter]  = $myrow_province['coast_stations'];
            }
            echo ">".$myrow_province['Province']."</option>";           
          }
       }
      echo "</select>";
      echo "&nbsp;";
      
      // SHOW BUTTON FOR PROVINCE-MAP IF MAP EXISTS 
	  // set region-map position - shift from top 
	  if($counter < 24)		$top_shift = 190+($counter*$td_height);
	  else					$top_shift = ($counter*$td_height)-720;

	  if($_GET['Region'] == $myrow['Country'])	{
		$file_province_map = "provinces/".strtolower($myrow['cc'])."_p.png";
	  } else {
		$file_province_map = "provinces/".strtolower($myrow['cc'])."_p_".strtolower(changeChars(replaceUTFChars($_GET['Region']),"_")).".png";
      }

	  if($_GET['Region'] != '' && file_exists($file_province_map ))  {
        echo "<a id='".$myrow['cc']."_province_link' ";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  echo "style='visibility: visible; cursor:pointer; cursor: hand;'";
        else                                                      echo "style='visibility: hidden; cursor:pointer; cursor: hand;'";
        echo "alt='".$myrow['Country']." - province map' title='".$myrow['Country']." province map'  onclick=\"ShowContent('map_div','map_img','".$file_province_map ."','".$myrow['Country']." - ".$_GET['Region']." - province map','725px','875px','150px','".$top_shift."px'); \"><img src='images/Regije.gif' border='0' /></a>";
      }
    echo "</td>";
    
    
    // BATHING PLACES
	
	// set graph position - shift from top 
	if($counter < 14)		$top_shift = 190+($counter*$td_height);
	else					$top_shift = ($counter*$td_height)-115;
	
	echo "<td id='td_".$counter."' >";
      echo "<span style=\"display:";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '')   echo "none";
        else                                                                                  echo "block";
      echo "\" color='gray' >";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')        echo ($region_coast_stations[$counter]+$region_freshwater_stations[$counter]);
        else                                                            echo ($country_coast_stations[$counter]+$country_freshwater_stations[$counter]); 
      echo " bathing waters ";
        if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')        echo "in selected region";
      echo "</span>";

      echo "<select ";
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '')   echo "style='display: block; width: 290px;' ";
      else                                                                                  echo "style='display: none; width: 290px;' ";
      echo "name='".$myrow['cc']."_bplace' id='".$myrow['cc']."_bplace' ";
      
      echo "onchange=\"if(this.value != '') {ShowContent('graph_div','graph_img','bar_jpgraph.php?cc=".$myrow['cc']."&Country=".$myrow['Country']."&GeoRegion=".$_GET['GeoRegion']."&Region=".convertUTFtoHTML($_GET['Region'])."&Province=".convertUTFtoHTML($_GET['Province'])."&BathingPlace=' + document.getElementById('".$myrow['cc']."_bplace').value,'','550px','270px','300px','".$top_shift."px'); return true;} else {HideContent('graph_div');}\" ";
      
      echo ">";
      echo "<option value='' selected>--- ".($province_coast_stations[$counter]+$province_freshwater_stations[$counter])." bathing waters in selected province ---</option>";
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
            echo ">".$myrow_bplace['Prelev']."</option>";           
          }
       }
      echo "</select>";
    echo "</td>";

    // VISUALIZATION BUTTONS
    echo "<td bgcolor='' align='right'>";
      // GENERATES KML LINK
      $link_za_kml = "\"kml_export.php?cc=".$myrow['cc']."&GeoRegion=".$_GET['GeoRegion']."\"";
      if($_GET['Region'] != "")
        $link_za_kml .= " + \"&Region=\" + document.getElementById(\"".$myrow['cc']."_region\").value";
      if(isset($_GET['Province']))
        $link_za_kml .= "+ \"&Province=\" + document.getElementById(\"".$myrow['cc']."_province\").value";
        $link_za_kml .= "+ \"&BathingPlace=\" + document.getElementById(\"".$myrow['cc']."_bplace\").value";
      $string_title_kml = $myrow['Country'];
      if($_GET['Region'] != "" && $_GET['cc'] == $myrow['cc']) $string_title_kml .= ", ".$_GET['Region'];
      if($_GET['Province'] != "" && $_GET['cc'] == $myrow['cc']) $string_title_kml .= ", ".$_GET['Province'];
      //if($_GET['BathingPlace'] != "" && $_GET['cc'] == $myrow['cc']) $string_title_kml .= ", ".$_GET['BathingPlace'];

      // PDF LINK; 20.5.2008; excluded till further
	  /*
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != "")  {
        $link_za_pdf = "pdf_maps/pdf_".strtolower($myrow['cc'])."_".strtolower(changeChars(replaceUTFChars($_GET['Region']),"_")).".pdf";
        $title_za_pdf = "PDF map - ".$myrow['Country'].", ".$_GET['Region'];
      } else {
        $link_za_pdf = "pdf_maps/pdf_".strtolower($myrow['cc']).".pdf";
        $title_za_pdf = "PDF map - ".$myrow['Country'];
     }
	 */

      // GRAPH LINK, BAR GRAPH FOR PROVINCES, LINE GRAPH FOR OTHERS
      $link_za_graf = "line_jpgraph.php?cc=".$myrow['cc']."&Country=".$myrow['Country']."&GeoRegion=".$_GET['GeoRegion'];
      if($_GET['Region'] != "" && $_GET['cc'] == $myrow['cc'])
        $link_za_graf .= "&Region=".$_GET['Region'];
      
	  if(($_GET['Province'] != "") && $_GET['cc'] == $myrow['cc'])
        $link_za_graf = "bar_jpgraph.php?cc=".$myrow['cc']."&Country=".$myrow['Country']."&GeoRegion=".$_GET['GeoRegion']."&Region=".$_GET['Region']."&Province=".$_GET['Province'];

      // 10.5.2008; CHANGE DEFAULT ICON WITH DISABLED-ICON, IF THERE IS NO COASTAL/FRESHWATER BW IN COUNTRY/REGION/PROVINCE
      $coast_disabled = 0;
      $fresh_disabled = 0;
      if($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '' && $_GET['Province'] != '') {
        if($province_coast_stations[$counter] == 0)       $coast_disabled = 1; 
        if($province_freshwater_stations[$counter] == 0)  $fresh_disabled = 1;
      } elseif($myrow['cc'] == $_GET['cc'] && $_GET['Region'] != '')  {
        if($region_coast_stations[$counter] == 0)         $coast_disabled = 1; 
        if($region_freshwater_stations[$counter] == 0)    $fresh_disabled = 1; 
      } else {
        if($myrow['coast_stations'] == 0)       $coast_disabled = 1; 
        if($myrow['freshwater_stations'] == 0)  $fresh_disabled = 1; 
      }

      // GOOGLE EARTH
      echo "<a alt='Google Earth KML - ".$string_title_kml."' title='Google Earth KML - ".$string_title_kml."' onclick='document.location = ".$link_za_kml."; return false;' href=''><img src='images/GoogleEarthMali.gif' border='0' /></a>";
      echo "&nbsp;";
      
      // PDF; 20.5.2008; excluded till further
	  /*
      if(file_exists($link_za_pdf))
        echo "<a alt='".$title_za_pdf."' title='".$title_za_pdf."' target='NewWindow' href='".$link_za_pdf."'><img src='images/PDFmala.gif' border='0' /></a>";
      else 
        echo "<img alt='No PDF map for this region' title='No PDF map for this region' src='images/PDFmalaX.gif' border='0' />";
      echo "&nbsp;";
      */

      // GRAPH
	  // set graph position - shift from top 
	  if($counter < 14)		$top_shift = 190+($counter*$td_height);
	  else					$top_shift = ($counter*$td_height)-295;
        
		// IF NO COASTAL BW: ONLY IMG ONMOUSEOVER
        if($coast_disabled) {
          $title_graf = "No coastal bathing waters in this Country/Region/Province";
          echo "<img alt='".$title_graf."' title='".$title_graf."' src='images/SlanaVodaGrafX.jpg' border='0' />";
        } else {
          $title_graf = "Quality of coastal bathing waters in this Country/Region/Province"; // - ".$myrow['Country'];
          echo "<a alt='".$title_graf."' title='".$title_graf."' style='cursor:pointer; cursor: hand;' onclick=\"ShowContent('graph_div','graph_img','".convertUTFtoHTML($link_za_graf)."&type=coast','','640px','450px','180px','".$top_shift."px'); \"><img src='images/SlanaVodaGraf.jpg' border='0' /></a>";
        }
        echo "&nbsp;";
        
		// IF NO FRESHWATER BW: ONLY IMG ONMOUSEOVER
        if($fresh_disabled) {
          $title_graf = "No freshwater bathing waters in this Country/Region/Province";
          echo "<img alt='".$title_graf."' title='".$title_graf."' src='images/SladkaVodaGrafX.jpg' border='0' />";
        } else {
          $title_graf = "Quality of freshwater bathing waters in this Country/Region/Province"; // - ".$myrow['Country'];
          echo "<a alt='".$title_graf."' title='".$title_graf."' style='cursor:pointer; cursor: hand;'  onclick=\"ShowContent('graph_div','graph_img','".convertUTFtoHTML($link_za_graf)."&type=fresh','','640px','450px','180px','".$top_shift."px'); \"><img src='images/SladkaVodaGraf.jpg' border='0' /></a>";
        }
 
    echo "</td>";
  echo "</tr>";
  flush();
  $counter++;
}


echo "</table>";

echo "<br/><img src='images/GoogleEarthMali.gif' border='0' />&nbsp;Don't have Google Earth? Download it here: <a target='_NEW_WINDOW' href='http://earth.google.com/download-earth.html'>http://earth.google.com/download-earth.html</a>";
//echo "<br/><img src='images/PDFmala.gif' border='0' />&nbsp;Adobe Acrobat Reader - download it here: <a target='_NEW_WINDOW' href='http://www.adobe.com/products/acrobat/readstep2.html'>http://www.adobe.com/products/acrobat/readstep2.html</a>";

//echo array_sum($country_coast_stations) + array_sum($country_freshwater_stations);

?>

</body>
</html>
