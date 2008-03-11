<?php
function googleMapMain ($x = 4, $y = 55, $z = 3)
{
  return '
  <script type="text/javascript">
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
  //var WMS_URL_JRC="http://wise.jrc.it/cgi-bin/mapserv?map=/home/www/utils-cgi-bin/map/wms.map&";
  //var G_MAP_EEA_GEONODE = createWMSSpec(WMS_URL_EEAGEONODE, "EEA", "WMS", "100,200,300,Counties,Capitals,ContryBorder,RiverLarge,Riverlarge_label,RiverMedium,Coastline,Villages", "default", "image/png", "1.1.1", "(c) Teleatlas");
  //var G_MAP_JRC = createWMSSpec(WMS_URL_JRC, "CCM", "WMS", "RIVERSEGMENTS", "default", "image/png", "1.1.1", "CCM River and Catchment Database JRC/IES (c) European Commission - JRC, 2003");
  
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

  GEvent.addListener(map, "click", function(overlay, point) 
    {
      document.carto_form.x.value = point.x;
      document.carto_form.y.value = point.y;
    }
  );

  map.getMapTypes().length = 3;
  map.setMapType(G_SATELLITE_MAP);
  map.addControl(new GSmallMapControl());
  //map.addControl(new GMapTypeControl(true));
  //map.addControl(new GScaleControl());  
  
  /* TEST */
  var test_layer = new GTileLayer( new GCopyrightCollection("(c) TEST"), 1, 17 );
  test_layer.myLayers="gadm2";
  test_layer.myFormat="image/gif";
  test_layer.myBaseURL="http://bg.berkeley.edu/cgi-bin/mapserv?map=/usr/local/apache2/htdocs/gadm.map&";
  test_layer.getTileUrl=CustomGetTileUrl;
  test_layer.getOpacity = function() {return 1;}
  
  var test_overlay = new GTileLayerOverlay( test_layer );
  map.addOverlay(test_overlay);
  
  /* Image2000 */
  var i2k_layer = new GTileLayer( new GCopyrightCollection("(c) European Commission"), 1, 17 );
  i2k_layer.myLayers="0";
  i2k_layer.myFormat="image/png";
  i2k_layer.myBaseURL="http://mapserver.jrc.it/wmsconnector/com.esri.wms.Esrimap/image2000_pan?";
  i2k_layer.getTileUrl=CustomGetTileUrl;
  i2k_layer.getOpacity = function() {return 1;}
  
  var i2k_overlay = new GTileLayerOverlay( i2k_layer );
  map.addOverlay(i2k_overlay);
  GEvent.addListener(map,"zoomend",function() {
    onZoomEnd();
  });
  
  /* EEA WMS */
  var eea_layer = new GTileLayer( new GCopyrightCollection("(c) Teleatlas"), 1, 17 );
  eea_layer.myLayers="ERM2_rivers";
  eea_layer.myFormat="image/png";
  eea_layer.myBaseURL="http://dampos-demo.eea.europa.eu/cgi-bin/wseea?";
  //eea_layer.myBaseURL="http://dev.sandre.eaufrance.fr/eeadamsgeo?";
  eea_layer.getTileUrl=CustomGetTileUrl;
  eea_layer.getOpacity = function() {return 1;}
  
  var eea_overlay = new GTileLayerOverlay( eea_layer );
  map.addOverlay(eea_overlay);
  

  // Initially hide additional overlays
  test_overlay.hide();
  i2k_overlay.hide();
  eea_overlay.hide();
  
  function onTestClick() {
  	testButton.press();
  	if(test_overlay.isHidden())
  	{
  	  test_overlay.show();
  	  return;
  	}
  	test_overlay.hide();
  }
  
  function onI2KClick() {
  	i2kButton.press();
  	if(i2k_overlay.isHidden())
  	{
  	  i2k_overlay.show();
  	  return;
  	}
  	i2k_overlay.hide();
  }
  
  function onEEAClick() {
  	eeaButton.press();
  	if(eea_overlay.isHidden())
  	{
  	  eea_overlay.show();
  	  return;
  	}
  	eea_overlay.hide();
  }

  
  function onHybClick() {
    if( !hybButton.isPress() )
    {
  	  hybButton.press();
  	  satButton.press();
  	  map.setMapType(G_HYBRID_MAP);
  	  restoreOverlays();
  	}
  }
  
  function onSatClick() {
    if( !satButton.isPress() )
    {
  	  hybButton.press();
  	  satButton.press();
  	  map.setMapType(G_SATELLITE_MAP);
  	  restoreOverlays();
  	}
  }
  
  function onZoomEnd()
  {
  	restoreOverlays();
  }
  
  //When changing map type (onSatClick/onHybClick), preserve overlays state of visibility 
  function restoreOverlays()
  {
  	if(test_overlay.isHidden()) test_overlay.hide();
  	if(i2k_overlay.isHidden()) i2k_overlay.hide(); 
  	if(eea_overlay.isHidden()) eea_overlay.hide();
  }
      
  var testButton = new LayerSelectControl( "Test", onTestClick, new GSize( 5, 5 ) ); 
  var i2kButton = new LayerSelectControl( "I2K", onI2KClick, new GSize( 60, 5 ) );
  var eeaButton = new LayerSelectControl( "EEA", onEEAClick, new GSize( 115, 5 ) );
  var hybButton = new LayerSelectControl( "Hyb", onHybClick, new GSize( 190, 5 ) );
  var satButton = new LayerSelectControl( "Sat", onSatClick, new GSize( 245, 5 ) );
  
  map.addControl( testButton );
  map.addControl( i2kButton );
  map.addControl( eeaButton );
  map.addControl( hybButton );
  map.addControl( satButton );

  satButton.press();
  
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