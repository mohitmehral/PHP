<?php
require_once 'commons/config.php';
require_once 'DataObjects/Public_dams.php';
require_once 'DataObjects/Public_user_dams.php';

if ($a->getAuth()) 
{
  // We do not want cache for this output
  session_cache_limiter( 'nocache' );
  $op = isset( $_REQUEST[ "op" ] ) ? $_REQUEST[ "op" ] : "";
  if( $op == "displayNearbyDams" )
  {
    // Check if bounding box is passed correctly
    $xtop = isset( $_REQUEST[ "xtop" ] ) ? $_REQUEST[ "xtop" ] : null;
    $ytop = isset( $_REQUEST[ "ytop" ] ) ? $_REQUEST[ "ytop" ] : null;
    $xbtm = isset( $_REQUEST[ "xbtm" ] ) ? $_REQUEST[ "xbtm" ] : null;
    $ybtm = isset( $_REQUEST[ "ybtm" ] ) ? $_REQUEST[ "ybtm" ] : null;
    
    if( $xtop && $ytop && $xbtm && $ybtm )
    {
      header("Cache-Control: no-cache, must-revalidate");
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header( "Content-type: text/xml" );
      echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
      echo "<dams>";
      echo displayNearbyDams( $xtop, $ytop, $xbtm, $ybtm, $file );
      echo "</dams>";
    } else {
      badRequest401();
    }
  } else {
    badRequest401();
  }
} else {
  header( "Location: loginpage.php" );
}

function displayNearbyDams( $xtop, $ytop, $xbtm, $ybtm, $logger ) {
  $ret = "";
  $do = new DataObjects_Public_Dams();
  $where = "x_icold < $xtop AND y_icold < $ytop AND x_icold > $xbtm AND y_icold > $ybtm"; 
  $do->whereAdd( $where );
  $logger->log( $where );
  $res = $do->find();
  if( $do != null ) {
    $i = 0;    
    while ($do->fetch()) {
      $ret .= "<d id=\"$do->noeea\" x=\"$do->x_icold\" y=\"$do->y_icold\" n=\"$do->name\"/>";
      $i++;
    }
    $logger->log( "i=$i" );
    $do->free();
  }
  return $ret;
}

function badRequest401() 
{
  header( "HTTP/1.1 400 Bad Request", null, 400 );
  echo "<h1>HTTP/1.1 400 Bad Request</h1>";
}
?>