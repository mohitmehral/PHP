<?php
function googleMapMain ($x = 4, $y = 55, $z = 3)
{
    return '
  <input type="checkbox" name="test" value="test" onclick="onclickTest()" />
  
  <script type="text/javascript">
  function onClickTest()
  {
    alert( "push" );
  }
  
  
  function createMarkerMain( point, id, iconimg, damName ) {
    var icon = new GIcon();
    icon.image = iconimg;
    icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
    icon.iconSize = new GSize(20, 34);
    icon.shadowSize = new GSize(37, 34);
    icon.iconAnchor = new GPoint(6, 20);
    icon.infoWindowAnchor = new GPoint(5, 1);
    var marker = new GMarker( point, {icon: icon, title: damName} );
    GEvent.addListener(marker, "click", function() { location.replace ("dams.php?cd="+id);});
    return marker;
  }

  //if (GBrowserIsCompatible()) {
  // WMS servers definitions
  var WMS_URL_JRC="http://wise.jrc.it/cgi-bin/mapserv?map=/home/www/utils-cgi-bin/map/wms.map&";
    
  //var G_MAP_EEA = createWMSSpec(WMS_URL_EEA, "Admin.", "WMS", "SM,A7", "default", "image/png", "1.1.1", "(c) Teleatlas");
  //var G_MAP_EEA_GEONODE = createWMSSpec(WMS_URL_EEAGEONODE, "EEA", "WMS", "100,200,300,Counties,Capitals,ContryBorder,RiverLarge,Riverlarge_label,RiverMedium,Coastline,Villages", "default", "image/png", "1.1.1", "(c) Teleatlas");
  //var G_MAP_JRC = createWMSSpec(WMS_URL_JRC, "CCM", "WMS", "RIVERSEGMENTS", "default", "image/png", "1.1.1", "CCM River and Catchment Database JRC/IES (c) European Commission - JRC, 2003");
  //var G_MAP_JRC_I2K = createWMSSpec(WMS_URL_I2K,"I2K.", "WMS", "0", "default", "image/png", "1.1.1", "(c) European Commission");
  // Create a transparent overlay on a Google MapSpec
  //var G_MAP_EEA_OVER_SAT = createWMSOverlaySpec(G_SATELLITE_TYPE, G_MAP_EEA, "Admin", "Admin");
  //var G_MAP_JRC_OVER_SAT = createWMSOverlaySpec(G_SATELLITE_TYPE, G_MAP_JRC, "CCM", "CCM");
  //var G_MAP_EEA_OVER_I2K = createWMSOverlaySpec(G_MAP_JRC_I2K, G_MAP_EEA, "I2K", "I2K");
  
  // Setup the map
  var map = new GMap2(document.getElementById("map"));
  map.setCenter(new GLatLng('.$y.','.$x.'), '.$z.');
  
  var tile_WMS_GEONODE = new GTileLayer( new GCopyrightCollection("(c) Teleatlas"), 1, 17 );
  tile_WMS_GEONODE.myLayers="100,200,300,Counties,Capitals,ContryBorder,RiverLarge,Riverlarge_label,RiverMedium,Coastline,Villages";
  tile_WMS_GEONODE.myFormat="image/png";
  tile_WMS_GEONODE.myBaseURL="http://geonode.eea.europa.eu/wmsconnector/com.esri.wms.Esrimap?";
  tile_WMS_GEONODE.getTileUrl=CustomGetTileUrl;
  tile_WMS_GEONODE.getOpacity = function() {return 0.5;}
  var layer_WMS_GEONODE = [G_SATELLITE_MAP.getTileLayers()[0], tile_WMS_GEONODE];
  var map_WMS_GEONODE = new GMapType(layer_WMS_GEONODE, G_SATELLITE_MAP.getProjection(), "EEA Geonode", G_SATELLITE_MAP);

  //var baseLayer = [G_SATELLITE_MAP.getTileLayers()[0], G_NORMAL_MAP.getTileLayers()[0], G_HYBRID_MAP.getTileLayers()[0]];
  var baseLayer = [G_SATELLITE_MAP.getTileLayers()[0]];
  var baseMap   = new GMapType(baseLayer, G_SATELLITE_MAP.getProjection(), "Google", G_SATELLITE_MAP);
  
  GEvent.addListener(map, "click", function(overlay, point) 
    {
      document.carto_form.x.value = point.x;
      document.carto_form.y.value = point.y;
    }
  );

  //map.getMapTypes().length = 0;
  //map.addMapType(baseMap);
  //map.addMapType(map_WMS_EEA);
  //map.addMapType(map_tile_test);
  //map.addMapType(map_I2K);
  map.setMapType(G_SATELLITE_MAP);
  
  /* TEST */
  var test_layer = new GTileLayer( new GCopyrightCollection("(c) TEST"), 1, 17 );
  test_layer.myLayers="gadm2";
  test_layer.myFormat="image/gif";
  test_layer.myBaseURL="http://bg.berkeley.edu/cgi-bin/mapserv?map=/usr/local/apache2/htdocs/gadm.map&";
  test_layer.getTileUrl=CustomGetTileUrl;
  test_layer.getOpacity = function() {return 1;}
  
  var test_overlay = new GTileLayerOverlay( test_layer );
  map.addOverlay(test_overlay);
  test_overlay.hide();
  
  /* Image2000 */
  var i2k_layer = new GTileLayer( new GCopyrightCollection("(c) European Commission"), 1, 17 );
  i2k_layer.myLayers="0";
  i2k_layer.myFormat="image/png";
  i2k_layer.myBaseURL="http://mapserver.jrc.it/wmsconnector/com.esri.wms.Esrimap/image2000_pan?";
  i2k_layer.getTileUrl=CustomGetTileUrl;
  i2k_layer.getOpacity = function() {return 1;}
  
  var i2k_overlay = new GTileLayerOverlay( i2k_layer );
  map.addOverlay(i2k_overlay);
  i2k_overlay.hide();

  /* EEA WMS */
  var eea_layer = new GTileLayer( new GCopyrightCollection("(c) Teleatlas"), 1, 17 );
  eea_layer.myLayers="SM,A7";
  eea_layer.myFormat="image/png";
  eea_layer.myBaseURL="http://dampos-demo.eea.europa.eu/cgi-bin/wseea?";
  //eea_layer.myBaseURL="http://dev.sandre.eaufrance.fr/eeadamsgeo?";
  eea_layer.getTileUrl=CustomGetTileUrl;
  eea_layer.getOpacity = function() {return 1;}
  
  var eea_overlay = new GTileLayerOverlay( eea_layer );
  map.addOverlay(eea_overlay);
  eea_overlay.hide();
  
  
  map.addControl(new GLargeMapControl());
  map.addControl(new GMapTypeControl());  
';
}

/**
 * Create a marker on google map applet.
 *
 * @param integer $x
 * @param integer $y
 * @param integer $id
 * @param string $name
 * @param string $iconurl
 * @return string
 */
function googleMarkerMain ( $x, $y, $id, $name = "", $iconurl = ICOLDICON ) {
  return "var point$id = new GPoint($x, $y); var marker$id = createMarkerMain(point$id, \"$id\", \"$iconurl\", \"$name\"); map.addOverlay(marker$id);\n";
}

function googleMapEnd () {
  return '</script>';
}

?>