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

//header('Content-Type: text/html; charset=utf-8');

// ONLY SHOW THESE STATUSES IN GRAPH
// 1=compliant to guide values = MODRA, 
// 2=prohibited throughout the season = SIVA, 
// 4=not compliant = RDEČA, 
// 5=compliant to mandatory values = ZELENA
$compliance_values = array('CG','B','NC','CI','NS','NF');
$years = array(2000,2001,2002,2003,2004,2005,2006,2007,2008);

foreach($compliance_values as $key=>$val) {
  $sql = "
    SELECT
      (COUNT(IF(y2000 = '".$val."', 1, NULL))/COUNT(IF(y2000 IS NOT NULL, 1, NULL)) )*100 AS '2000',
      (COUNT(IF(y2001 = '".$val."', 1, NULL))/COUNT(IF(y2001 IS NOT NULL, 1, NULL)) )*100 AS '2001',
      (COUNT(IF(y2002 = '".$val."', 1, NULL))/COUNT(IF(y2002 IS NOT NULL, 1, NULL)) )*100 AS '2002',
      (COUNT(IF(y2003 = '".$val."', 1, NULL))/COUNT(IF(y2003 IS NOT NULL, 1, NULL)) )*100 AS '2003',
      (COUNT(IF(y2004 = '".$val."', 1, NULL))/COUNT(IF(y2004 IS NOT NULL, 1, NULL)) )*100 AS '2004',
      (COUNT(IF(y2005 = '".$val."', 1, NULL))/COUNT(IF(y2005 IS NOT NULL, 1, NULL)) )*100 AS '2005',
      (COUNT(IF(y2006 = '".$val."', 1, NULL))/COUNT(IF(y2006 IS NOT NULL, 1, NULL)) )*100 AS '2006',
      (COUNT(IF(y2007 = '".$val."', 1, NULL))/COUNT(IF(y2007 IS NOT NULL, 1, NULL)) )*100 AS '2007',
      (COUNT(IF(y2008 = '".$val."', 1, NULL))/COUNT(IF(y2008 IS NOT NULL, 1, NULL)) )*100 AS '2008',
      COUNT(*) AS No_of_stations,													# only needed to display in title total or max. number of stations for all years
      COUNT(IF(y2008 IS NOT NULL, 1, NULL)) AS No_of_stations_2008		# only needed to display in title: number of stations 2008
    FROM bwd_stations 
	 WHERE 1 ";

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
  
  foreach($years as $key1=>$val1) {
    // to shift 0 values a little above the bottom; disabled
    //$data[$val][] = ($myrow[$val1] == 0)?'':$myrow[$val1];
    $data[$val][] = $myrow[$val1];
  }
}


// 20.5.2008; values with 5 (c mandatory) have to include also values 1 (c guide),
// because if BW is compliant to mandatory than is also compliant to guide
foreach($data['CI'] as $key=>$val) 	$data['CI'][$key] += $data['CG'][$key];

// CHECK THE ARRAYS
// 1. if all 4 arrays have values 0 means that there is no data for the year - change with '' 
foreach($years as $key1=>$val1) {
  if($data['CG'][$key1] == $data['B'][$key1] && $data['B'][$key1] == $data['NC'][$key1] && $data['NC'][$key1] == $data['CI'][$key1] && $data['CI'][$key1] == 0) {
    $data['CG'][$key1] = '';
    $data['B'][$key1] = '';
    $data['NC'][$key1] = '';
    $data['CI'][$key1] = '';
  }
}

// 2. if all values in one array are 0 -> change them to '', so this will not be plotted
foreach($compliance_values as $key=>$val) {
  if(array_sum($data[$val]) == 0) {
    foreach($years as $key1=>$val1) {
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
  $graph_width = 600; $graph_height = 450;
}

$graph = new Graph($graph_width,$graph_height,"auto");
$graph->img->SetMargin(50,20,30,80);
//$graph->img->SetAntiAliasing("white");
$graph->SetScale("textlin",0,100);  // set lower and upper Y value of graph


// TITLE 
$title = $_GET['Country'];
if($_GET['GeoRegion'] != "") $title .= " (".substr($_GET['GeoRegion'],29).")";
if($_GET['Region'] != "") $title .= ", ".$_GET['Region'];
if($_GET['Province'] != "") $title .= ", ".$_GET['Province'];
$title .= ": ".$myrow['No_of_stations']." ";
if($_GET['type'] == 'coast')  $title .= "coastal BW";
if($_GET['type'] == 'fresh')  $title .= "freshwater BW";
$title .= " (".$myrow['No_of_stations_2008']." in 2008)";

$graph->title->Set(replaceUTFChars($title));

// AXIS TITLES AND FONTS
$graph->xaxis->SetTickLabels($years);

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
$p1 = new LinePlot($data['CG']);
$p1->mark->SetType(MARK_FILLEDCIRCLE);
$p1->mark->SetFillColor("lightblue");
$p1->mark->SetWidth(4);
$p1->SetColor("lightblue");
$p1 ->SetWeight(3);
$p1->SetCenter();
$p1->SetLegend("% ".complianceText('CG'));
$graph->Add($p1);

// 2nd LINE - 5=compliance with mandatory values = GREEN
$p2 = new LinePlot($data['CI']);
$p2->mark->SetType(MARK_FILLEDCIRCLE);
$p2->mark->SetFillColor("lightgreen");
$p2->mark->SetWidth(4);
$p2->SetColor("lightgreen");
$p2 ->SetWeight(3);
$p2->SetCenter();
$p2->SetLegend("% ".complianceText('CI'));
$graph->Add($p2);

// 3rd LINE - 4=not compliant with mandatory values = RED
$p3 = new LinePlot($data['NC']);
$p3->mark->SetType(MARK_FILLEDCIRCLE);
$p3->mark->SetFillColor("#FF7F7F");
$p3->mark->SetWidth(4);
$p3->SetColor("#FF7F7F");
$p3 ->SetWeight(3);
$p3->SetCenter();
$p3->SetLegend("% ".complianceText('NC'));
$graph->Add($p3);

// 4th LINE - Closed or banned /*2=prohibited throughout the season*/ = GRAY
$p4 = new LinePlot($data['B']);
$p4->mark->SetType(MARK_FILLEDCIRCLE);
$p4->mark->SetFillColor("lightgray");
$p4->mark->SetWidth(4);
$p4->SetColor("gray");
$p4 ->SetWeight(3);
$p4->SetCenter();
$p4->SetLegend("% ".complianceText('B'));
$graph->Add($p4);

/*
// 5th LINE - Insufficiently samples = ORANGE
$p5 = new LinePlot($data['NF']);
$p5->mark->SetType(MARK_FILLEDCIRCLE);
$p5->mark->SetFillColor("orange");
$p5->mark->SetWidth(4);
$p5->SetColor("orange");
$p5 ->SetWeight(3);
$p5->SetCenter();
$p5->SetLegend("% ".complianceText('NF'));
$graph->Add($p5);

// 6th LINE - Not sampled = YELLOW
$p6 = new LinePlot($data['NS']);
$p6->mark->SetType(MARK_FILLEDCIRCLE);
$p6->mark->SetFillColor("yellow");
$p6->mark->SetWidth(4);
$p6->SetColor("yellow");
$p6 ->SetWeight(3);
$p6->SetCenter();
$p6->SetLegend("% ".complianceText('NS'));
$graph->Add($p6);
*/

if($_GET['cc'] == 'DE')  {
	$t1 = new Text("* Since type for some bathing waters has been changed from freshwater to coastal or vice versa\nand are presented here as coastal and freshwater respectively from the beginning of reporting,\nthe graph is slightly different as in the national report.");
	$t1->SetPos(0.05,405);
	$t1->ParagraphAlign("left");
	$t1->SetColor("black");
	$graph->AddText($t1);
}

/*


*/

// OUTPUT LINE
$graph->Stroke();

// vi: set ts=4 sw=4:
?>
