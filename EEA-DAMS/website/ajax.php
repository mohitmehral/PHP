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

    // Exclude the original validated position and its seed position (they cannot appear red/green AND gray)
    $exclude0x = isset( $_REQUEST[ "exclude0x" ] ) ? $_REQUEST[ "exclude0x" ] : null;
    $exclude0y = isset( $_REQUEST[ "exclude0y" ] ) ? $_REQUEST[ "exclude0y" ] : null;
    $exclude1x = isset( $_REQUEST[ "exclude1x" ] ) ? $_REQUEST[ "exclude1x" ] : null;
    $exclude1y = isset( $_REQUEST[ "exclude1y" ] ) ? $_REQUEST[ "exclude1y" ] : null;
    
    if( $xtop && $ytop && $xbtm && $ybtm )
    {
      header("Cache-Control: no-cache, must-revalidate");
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header( "Content-type: text/xml" );
      echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
      echo "<dams>";
      
      $xml = "";
      $do = new DataObjects_Public_Dams();
      $where = "";
      $where .= "     (( x_val < $xtop AND y_val < $ytop AND x_val > $xbtm AND y_val > $ybtm ) ";
      $where .= "  OR ( x_prop < $xtop AND y_prop < $ytop AND x_prop > $xbtm AND y_prop > $ybtm ) ";
      $where .= "  OR ( x_icold < $xtop AND y_icold < $ytop AND x_icold > $xbtm AND y_icold > $ybtm ) )";
      if( $exclude0x && $exclude0y ) {
        $where .= " AND ( x_val <> $exclude0x AND y_val <> $exclude0y ";
        $where .= " AND x_prop <> $exclude0x AND y_prop <> $exclude0y ";
        $where .= " AND x_icold <> $exclude0x AND y_icold <> $exclude0y ) ";
      }
      if( $exclude1x && $exclude1y ) {
        $where .= " AND ( x_val <> $exclude1x AND y_val <> $exclude1y ";
        $where .= " AND x_prop <> $exclude1x AND y_prop <> $exclude1y ";
        $where .= " AND x_icold <> $exclude1x AND y_icold <> $exclude1y ) "; 
      }
      $do->whereAdd( $where );
      $file->log( "SELECT * FROM dams WHERE $where" );
      $res = $do->find();
      if( $do != null ) {
        $i = 0;    
        while ($do->fetch()) {
          $pos = $do->getDamMapCenter();
          //$xml .= "<d id=\"$do->noeea\" x=\"$do->x_icold\" y=\"$do->y_icold\" n=\"$do->name\"/>";
          $xml .= "<d id=\"$do->noeea\" x=\"$pos[0]\" y=\"$pos[1]\" n=\"$do->name\"/>";
          $i++;
        }
        $file->log( "i=$i" );
        $do->free();
      }
      echo $xml;
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

function badRequest401() 
{
  header( "HTTP/1.1 400 Bad Request", null, 400 );
  echo "<h1>HTTP/1.1 400 Bad Request</h1>";
}
?>
