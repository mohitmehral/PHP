<?php
/**
 * Initialize the Google maps viewport and set up the environment.
 *
 * @param float $x Center position - latitude
 * @param float $y Center position - longitude
 * @param integer $z Zoom level
 * @param string $mapClickListener JavaScript function called when user clicks the map
 * @return string JS script. Please note that the script starts with <script type ... but does not append end script tag!. Do that explicitly calling endGoogleViewport()
 */
function startGoogleViewport ( $x = 4, $y = 55, $z = 3, $mapClickListener = null )
{
  $handlerStr = "";
  if( $mapClickListener != null )
  {
    $handlerStr = "GEvent.addListener(map, 'click', $mapClickListener )";
  }
  
  return '
  <script type="text/javascript">
  // Setup the map
  var map = new GMap2( document.getElementById( "map" ) );
  map.setCenter( new GLatLng( '.$y.','.$x.'), '.$z.');

  map.getMapTypes().length = 3;
  map.setMapType(G_SATELLITE_MAP);
  map.addControl(new GSmallMapControl());
  
  /* Image2000 */
  var i2k_layer = new GTileLayer( new GCopyrightCollection("(c) European Commission"), 1, 17 );
  i2k_layer.myLayers="0";
  i2k_layer.myFormat="image/png";
  i2k_layer.myBaseURL="http://mapserver.jrc.it/wmsconnector/com.esri.wms.Esrimap/image2000_pan?";
  i2k_layer.getTileUrl=CustomGetTileUrl;
  i2k_layer.getOpacity = function() {return 1;}
  
  var i2k_overlay = new GTileLayerOverlay( i2k_layer );
  map.addOverlay( i2k_overlay );
  GEvent.addListener( map, "zoomend", function() { onZoomEnd(); } );
  
  '.$handlerStr.'
    
  /* EEA WMS */
  var eea_layer = new GTileLayer( new GCopyrightCollection("(c) Teleatlas"), 1, 17 );
  eea_layer.myLayers="ERM2_rivers";
  eea_layer.myFormat="image/png";
  eea_layer.myBaseURL="http://dampos-demo.eionet.europa.eu/cgi-bin/wseea?";
  eea_layer.getTileUrl=CustomGetTileUrl;
  eea_layer.getOpacity = function() {return 1;}
  
  var eea_overlay = new GTileLayerOverlay( eea_layer );
  map.addOverlay(eea_overlay);
  
  // Initially hide additional overlays
  i2k_overlay.hide();
  eea_overlay.hide();

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
  	  if( satButton.isPress() ) satButton.press();
  	  if( normalButton.isPress() ) normalButton.press();
  	  map.setMapType(G_HYBRID_MAP);
  	  restoreOverlays();
  	}
  }
  
  function onSatClick() {
    if( !satButton.isPress() )
    {
  	  if( hybButton.isPress() ) hybButton.press();
  	  satButton.press();
  	  if( normalButton.isPress() ) normalButton.press();
  	  map.setMapType(G_SATELLITE_MAP);
  	  restoreOverlays();
  	}
  }
  
  function onNormalClick() {
    if( !normalButton.isPress() )
    {
  	  if( hybButton.isPress() ) hybButton.press();
  	  if( satButton.isPress() ) satButton.press();
  	  normalButton.press();
  	  map.setMapType(G_NORMAL_MAP);
  	  restoreOverlays();
  	}
  }
  
  function onZoomEnd()
  {
  	restoreOverlays();
  }
  
  //When changing map type (onSatClick/onHybClick), preserve overlay state of visibility 
  function restoreOverlays()
  {
  	if(i2k_overlay.isHidden()) i2k_overlay.hide(); 
  	if(eea_overlay.isHidden()) eea_overlay.hide();
  }
      
  var i2kButton = new LayerSelectControl( "I2K", onI2KClick, new GSize( 60, 5 ) );
  var eeaButton = new LayerSelectControl( "EEA", onEEAClick, new GSize( 115, 5 ) );
  var hybButton = new LayerSelectControl( "Hyb", onHybClick, new GSize( 190, 5 ) );
  var satButton = new LayerSelectControl( "Sat", onSatClick, new GSize( 245, 5 ) );
  var normalButton = new LayerSelectControl( "Normal", onNormalClick, new GSize( 300, 5 ) );
  
  map.addControl( i2kButton );
  map.addControl( eeaButton );
  map.addControl( hybButton );
  map.addControl( satButton );
  map.addControl( normalButton );

  satButton.press();
';
}

/**
 * Create a marker on google viewport.
 *
 * @param double $x Center position - x
 * @param double $y Center position - y
 * @param integer $id Marker id (unique within page)
 * @param string $name Marker (appears as tooltip on marker)
 * @param string $iconurl Marker icon
 * @return string Returs JS code which creates the marker
 */
function googleMarkerMain ( $x, $y, $id, $name = "", $iconurl = ICOLDICON ) {
  return "var point$id = new GPoint($x, $y); var marker$id = createMarkerMain(point$id, \"$id\", \"$iconurl\", \"$name\"); map.addOverlay(marker$id);";
}

function createCrossMarker( $markerID, $title, $x, $y, $icon, $markerType ) {
  return "var point$markerID = new GPoint($x, $y); var marker$markerID = createCrossMarker(point$markerID, \"$title\", \"$icon\", $markerType); map.addOverlay(marker$markerID);";
}

/**
 * Ends the script created by startGoogleViewport. 
 * @return string
 */
function endGoogleViewport () {
  return '</script>';
}

?>