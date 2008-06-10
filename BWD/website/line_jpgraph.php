<?php

/*

BWD water quality data/map viewer: FILE TO CREATE IMAGE WITH BAR GRAPH

21.3.2008; first version

*/
if (!isset($_GET['GeoRegion'])) $_GET['GeoRegion'] = '';
if (!isset($_GET['type'])) $_GET['type'] = '';
if (!isset($_GET['cc'])) $_GET['cc'] = '';
if (!isset($_GET['Region'])) $_GET['Region'] = '';
if (!isset($_GET['Province'])) $_GET['Province'] = '';
if (!isset($_GET['BathingPlace'])) $_GET['BathingPlace'] = '';
include('config.php');
include('functions.php');
include ("jpgraph-2.3/src/jpgraph.php");
include ("jpgraph-2.3/src/jpgraph_line.php");

// CONNECT
$db = mysql_connect($host, $dbuser,$dbpass);
mysql_select_db($database,$db);
mysql_query("SET NAMES 'utf8'");

header('Content-Type: text/html; charset=utf-8');

// ONLY SHOW THESE STATUSES IN GRAPH
// 1=compliant to guide values = MODRA, 
// 2=prohibited throughout the season = SIVA, 
// 4=not compliant = RDEČA, 
// 5=compliant to mandatory values = ZELENA
$compliance_values = array(1,2,4,5);
$leta = array(2000,2001,2002,2003,2004,2005,2006,2007);

foreach($compliance_values as $key=>$val) {
  $sql = "
    SELECT
      (COUNT(IF(y2000 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2000',
      (COUNT(IF(y2001 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2001',
      (COUNT(IF(y2002 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2002',
      (COUNT(IF(y2003 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2003',
      (COUNT(IF(y2004 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2004',
      (COUNT(IF(y2005 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2005',
      (COUNT(IF(y2006 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2006',
      (COUNT(IF(y2007 = ".$val.", 1, NULL))/COUNT(*))*100 AS '2007',
      COUNT(*) AS No_of_stations
    FROM bwd_stations ";
  
  if($_GET['GeoRegion'] != '')		$sql .= " INNER JOIN numind_geographic n USING (numind) ";
  $sql .= " WHERE 1 ";
  if($_GET['GeoRegion'] != '')		$sql .= " AND geographic = '".$_GET['GeoRegion']."'";

  if($_GET['cc'] != "")  $sql .= " AND cc = '".$_GET['cc']."'";
  if($_GET['type'] == 'coast')  $sql .= " AND SeaWater = 'O'"; 
  if($_GET['type'] == 'fresh')  $sql .= " AND SeaWater = 'N'"; 
  if($_GET['Region'] != "")     $sql .= " AND Region LIKE '".$_GET['Region']."'";
  if($_GET['Province'] != "")   $sql .= " AND Province LIKE '".$_GET['Province']."'";
  if($_GET['BathingPlace'] != "") $sql .= " AND Numind = '".$_GET['BathingPlace']."'";

  // GROUP BY
  if($_GET['BathingPlace'] != "")   $sql .= " GROUP BY Numind";
  elseif($_GET['Province'] != "")   $sql .= " GROUP BY Province";
  elseif($_GET['Region'] != "")     $sql .= " GROUP BY Region";

  $result = mysql_query($sql) or die($sql."<br>".mysql_error());
  $myrow = mysql_fetch_array($result);
  
  foreach($leta as $key1=>$val1) {
    // to shift 0 values a little above the bottom; disabled
    //$data[$val][] = ($myrow[$val1] == 0)?'':$myrow[$val1];
    $data[$val][] = $myrow[$val1];
  }
}


// 20.5.2008; values with 5 (c mandatory) have to include also values 1 (c guide), because if BW is compl. to mandatory than is also compl. to guide
foreach($data[5] as $key=>$val) 	$data[5][$key] += $data[1][$key];

// CHECK THE ARRAYS
// 1. if all 4 arrays have values 0 means that there is no data for the year - change with '' 
foreach($leta as $key1=>$val1) {
  if($data[1][$key1] == $data[2][$key1] && $data[2][$key1] == $data[4][$key1] && $data[4][$key1] == $data[5][$key1] && $data[5][$key1] == 0) {
    $data[1][$key1] = '';
    $data[2][$key1] = '';
    $data[4][$key1] = '';
    $data[5][$key1] = '';
  }
}

// 2. if all values in one array are 0 -> change them to '', so this will not be plotted
foreach($compliance_values as $key=>$val) {
  if(array_sum($data[$val]) == 0) {
    foreach($leta as $key1=>$val1) {
      $data[$val][$key1] = '';
    }
  }
}

/*
echo "<pre>";
print_r($data);
echo "</pre>";
die;
*/

// EU27 graph is bigger
if($_GET['Country'] == 'EU27')  {
  $graph_width = 900; $graph_height = 700;
} else {
  $graph_width = 600; $graph_height = 400;
}

$graph = new Graph($graph_width,$graph_height,"auto");
$graph->img->SetMargin(50,20,30,40);
//$graph->img->SetAntiAliasing("white");
$graph->SetScale("textlin",0,100);  // set lower and upper Y value of graph


// TITLE 
$title = $_GET['Country'];
if($_GET['GeoRegion'] != "") $title .= " (".substr($_GET['GeoRegion'],29).")";
if($_GET['Region'] != "") $title .= ", ".$_GET['Region'];
if($_GET['Province'] != "") $title .= ", ".$_GET['Province'];
$title .= ": ".$myrow['No_of_stations']." ";
if($_GET['type'] == 'coast')  $title .= "coastal bathing waters";
if($_GET['type'] == 'fresh')  $title .= "freshwater bathing waters";
$graph->title->Set(replaceUTFChars($title));

// AXIS TITLES AND FONTS
$graph->xaxis->SetTickLabels($leta);

$graph->xaxis->title->Set('Year');
$graph->xaxis->title->SetColor('black');
$graph->xaxis->SetColor('black');

$graph->yaxis->title->Set('% of bathing waters');
$graph->yaxis->title->SetColor('black');
$graph->yaxis->SetColor('black');

$graph->ygrid->Show(true,true); // 1st parameter to show/hide major gridline, 2nd parameter to show/hide minor gridline

// POSITION OF THE LEGEND BOX
$graph->legend->Pos(0.1,0.4,"left","top");
$graph->legend->SetShadow('darkgray@0.5');
$graph->legend->SetFillColor('lightgray@0.3');


// 1st LINE - 1=compliance with guide values = BLUE
$p1 = new LinePlot($data[1]);
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor("lightblue");
$p1->mark->SetWidth(4);
$p1->SetColor("lightblue");
$p1 ->SetWeight(3);
$p1->SetCenter();
$p1->SetLegend("% ".complianceText(1));
$graph->Add($p1);

// 2nd LINE - 5=compliance with mandatory values = GREEN
$p2 = new LinePlot($data[5]);
$p2->mark->SetType(MARK_FILLEDCIRCLE);
$p2->mark->SetFillColor("lightgreen");
$p2->mark->SetWidth(4);
$p2->SetColor("lightgreen");
$p2 ->SetWeight(3);
$p2->SetCenter();
$p2->SetLegend("% ".complianceText(5));
$graph->Add($p2);

// 3rd LINE - 4=not compliant with mandatory values = RED
$p3 = new LinePlot($data[4]);
$p3->mark->SetType(MARK_FILLEDCIRCLE);
$p3->mark->SetFillColor("#FF7F7F");
$p3->mark->SetWidth(4);
$p3->SetColor("#FF7F7F");
$p3 ->SetWeight(3);
$p3->SetCenter();
$p3->SetLegend("% ".complianceText(4));
$graph->Add($p3);

// 4th LINE - 2=prohibited throughout the season = GRAY
$p4 = new LinePlot($data[2]);
$p4->mark->SetType(MARK_FILLEDCIRCLE);
$p4->mark->SetFillColor("lightgray");
$p4->mark->SetWidth(4);
$p4->SetColor("gray");
$p4 ->SetWeight(3);
$p4->SetCenter();
$p4->SetLegend("% ".complianceText(2));
$graph->Add($p4);

// OUTPUT LINE
$graph->Stroke();

// vi: set ts=4 sw=4:
?>
