/* Custom control for Google Maps */
function LayerSelectControl(text, clickHandler, position ) 
{
  this.text = text;
  this.onClickHandler = clickHandler;
  this.pressed = false;
  this.buttonDiv = null;
  this.btnPosition = position;
}

LayerSelectControl.prototype = new GControl();


LayerSelectControl.prototype.press = function()
{
  this.pressed = !this.pressed;
  this.setButtonStyle_( this.buttonDiv, this.pressed );
}


LayerSelectControl.prototype.isPress = function()
{
  return this.pressed;
}


LayerSelectControl.prototype.getDiv = function()
{
  return this.buttonDiv;
}


LayerSelectControl.prototype.initialize = function(map) 
{
  var container = document.createElement("div");
  this.buttonDiv = document.createElement("div");
  this.setButtonStyle_(this.buttonDiv, false);
  container.appendChild(this.buttonDiv);
  this.buttonDiv.appendChild(document.createTextNode(this.text));
  GEvent.addDomListener(this.buttonDiv, "click", this.onClickHandler);
  map.getContainer().appendChild(container);
  return container;
}


LayerSelectControl.prototype.getDefaultPosition = function() 
{
  return new GControlPosition( G_ANCHOR_TOP_RIGHT, this.btnPosition );
}

      
LayerSelectControl.prototype.setButtonStyle_ = function(button, pressed) 
{
  button.style.borderBottomColor = "#B0B0B0";
  button.style.borderBottomStyle = "solid";
  button.style.borderBottomWidth = "1px";
  button.style.borderLeftColor = "white";
  button.style.borderLeftStyle = "solid";
  button.style.borderLeftWidth = "1px";
  button.style.borderRightColor = "#B0B0B0";
  button.style.borderRightStyle = "solid";
  button.style.borderRightWidth = "1px";
  button.style.borderTopColor = "#B0B0B0";
  button.style.borderTopStyle = "solid";
  button.style.borderTopWidth = "1px";
  button.style.color = "black";
  button.style.backgroundColor = "white";
  button.style.cursor = "pointer";
  button.style.fontSize = "12px";
  button.style.width = "50px";
  button.style.textAlign = "center";
  button.style.fontFamily = "Arial,sans-serif";
  button.style.fontWeight = ( pressed == true ) ? "bold" : "normal";
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


function createCrossMarker(point, desc, iconimg, mkType ) {
  var icon = new GIcon();
  icon.image = iconimg;
  icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
  icon.iconSize = new GSize(37, 37);
  icon.shadowSize = new GSize(37, 34);
  icon.iconAnchor = new GPoint(19, 19);
  icon.infoWindowAnchor = new GPoint(5, 1);
  var marker = null;
  if( mkType == 3 ) // Green cross
  {
    marker = new GMarker(point, {icon: icon, draggable: true, title: desc });
    marker.enableDragging();
    GEvent.addListener( marker, "dragend", damDragEndListener );
  } else {
    marker = new GMarker(point, {icon: icon, draggable: false, title: desc });
  }
  marker.markerType = mkType;
  var html = "" + desc + " <br/>Longitude :"+ point.x+"<br/>Latitude :"+ point.y;
  GEvent.addListener(marker, "click", function() 
  {
    marker.openInfoWindowHtml(html);
  });
  return marker;
}

function createCrossMarker2(id, point, desc, iconimg, mkType ) {
  var icon = new GIcon();
  icon.image = iconimg;
  icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
  icon.iconSize = new GSize(37, 37);
  icon.shadowSize = new GSize(37, 34);
  icon.iconAnchor = new GPoint(19, 19);
  icon.infoWindowAnchor = new GPoint(5, 1);
  var marker = null;
  if( mkType == 3 ) // Green cross
  {
    marker = new GMarker(point, {icon: icon, draggable: true, title: desc });
    marker.enableDragging();
    GEvent.addListener( marker, "dragend", damDragEndListener );
  } else {
    marker = new GMarker(point, {icon: icon, draggable: false, title: desc });
  }
  marker.markerType = mkType;
  var url = new String ( document.location );
  url = url.substr( 0, url.lastIndexOf( "/" ) );
  url += "/dams.php?cd=" + id;
  var html = "" + desc + " <br/>Longitude :"+ point.x+"<br/>Latitude :"+ point.y+"<br/><a href=\"" + url + "\">Make dam active</a>";
  GEvent.addListener(marker, "click", function() 
  {
    marker.openInfoWindowHtml(html);
  });
  return marker;
}


/**
  Whenever user clicks on map, this handler is called, filling the form fields.
  This method is a click handler on the map object in google viewport of dam detail (dam.php).
*/
function damMapClickListener( overlay, point ) {
  var ctrl = document.getElementById( "setWhichPoint" );
  try {
    if( ctrl != null )
    {
      if ( ctrl.checked == true ) {
        var xCtrl = document.getElementById( "x" );
        var yCtrl = document.getElementById( "y" );
        if( xCtrl != null ) xCtrl.value = point.x;
        if( yCtrl != null ) yCtrl.value = point.y;
      } else {
        var xIniCtrl = document.getElementById( "xini" );
        var yIniCtrl = document.getElementById( "yini" );
        if( xIniCtrl != null ) xIniCtrl.value = point.x;
        if( yIniCtrl != null ) yIniCtrl.value = point.y;       
      }
    }
  } catch( e ) {
    //alert( "Exception while setting values into controls (x, y, xini, yini). Reason: " + e.message );
  }
}


function damDragEndListener() {
  try {
    var marker = this;
    var position = marker.getLatLng();
    switch( marker.markerType ) {
      case 3: // Validated position - Green cross
        var xCtrl = document.getElementById( "x" );
        var yCtrl = document.getElementById( "y" );
        if( xCtrl != null ) xCtrl.value = position.x;
        if( yCtrl != null ) yCtrl.value = position.y;
      break;
/*
        case 1: // Seed position - Red cross
        var xIniCtrl = document.getElementById( "xini" );
        var yIniCtrl = document.getElementById( "yini" );
        if( xIniCtrl != null ) xIniCtrl.value = position.x;
        if( yIniCtrl != null ) yIniCtrl.value = position.y;       
      break;
*/      
    }
  } catch( e ) {
    alert( "Dragging exception. Reason: " + e.message );
  } 
  startRequestNearbyDams();
}


function resetSeed( x, y ) {
  var xCtrl = document.getElementById( "x" );
  var yCtrl = document.getElementById( "y" );
  if( xCtrl != null ) xCtrl.value = x;
  if( yCtrl != null ) yCtrl.value = y;
}

var reqObj = false;

function startRequestNearbyDams() {
  var bbox = map.getBounds();
  var xtop = bbox.getNorthEast().lng();
  var ytop = bbox.getNorthEast().lat();
  var xbtm = bbox.getSouthWest().lng();
  var ybtm = bbox.getSouthWest().lat();

  var url = new String ( document.location );
  url = url.substr( 0, url.lastIndexOf( "/" ) );
  url += "/ajax.php?op=displayNearbyDams&xtop=" + xtop + "&ytop=" + ytop + "&xbtm=" + xbtm + "&ybtm=" + ybtm;
  url += "&exclude0x=" + exclude0x + "&exclude0y=" + exclude0y + "&exclude1x=" + exclude0y + "&exclude1y=";
  serverRequest( url, endRequestNearbyDams );
}

var nearbydamsPoints = new Array();

function endRequestNearbyDams() {
  try {
    if ( reqObj.readyState == 4 ) { // Loaded
      if (reqObj.status == 200) { // OK
        var items = reqObj.responseXML.getElementsByTagName( "d" );
        var batch = [];
        if( map.getZoom() < 8 ) // Hide all the markers
        {
        } else { // Display all the markers plus new ones
          for( i = 0; i < items.length; i++ ) {
            var node = items[ i ];
            var p = new GPoint( node.getAttribute( "x" ), node.getAttribute( "y" ) );
            var title = node.getAttribute( "id" ) + ": " + node.getAttribute( "n" );
            var marker = createCrossMarker2( node.getAttribute( "id" ), p, title, nearbyicon, 2 );
            {
              map.addOverlay( marker );
            }
          }
        }
      } else {
        alert("There was a problem retrieving the XML data:\n" + reqObj.statusText);
      }
    }
  } catch( e ) {
    alert( "Error while displaying dams. Reason:" + e.message );
  }
}

function duplicate( marker )
{
  for( i = 0; i < nearbydamsPoints.length; i++ )
  {
    var em = nearbydamsPoints[ i ];
    if( em.title == marker.title ) 
      return true;
  }
  nearbydamsPoints.push( marker );
  return false;
}

function showMarkers( show ) {
  for( i = 0; i < nearbydamsPoints.length; i++ )
  {
    marker = nearbydamsPoints[ i ];
    if( show ) 
    {
      marker.show();
    } else {
      marker.hide();
    }
  }   
}


/**
 * XML Calls on server using JavaSscript XML-HTTP request
 * @param url URL to request from server (must return a valid XML, non-cached)
 * @param handler Callback handler since request is asynchronous
 */
function serverRequest( url, handler ) {
  if( !reqObj )
  { 
    // branch for native XMLHttpRequest object
    if( window.XMLHttpRequest ) {
      try {
        reqObj = new XMLHttpRequest();
      } catch(e) {
        reqObj = false;
      }
      // branch for IE/Windows ActiveX version
    } else if(window.ActiveXObject) {
      try {
        reqObj = new ActiveXObject( "Msxml2.XMLHTTP" );
      } catch( e ) {
        try {
          reqObj = new ActiveXObject( "Microsoft.XMLHTTP" );
        } catch( e ) {
          alert ( "Functionality not available with this browser." );
          reqObj = false;
        }
      }
    }
  }
  // Check if the request object is in a state which allows new request
  if( reqObj ) {
    reqObj.open( "GET", url, true );
    reqObj.onreadystatechange = handler ;
    reqObj.send( "" );
  } else {
    alert( "Nearby dams cannot be displayed on this browser" );
  }
  return reqObj;
}

// This method builds the copyright string, by appending to the existing one build by Google, 
// thus without causing any copyright infringement.    
function buildCopyright( bounds, zoom ) {
  arr = [];
  try {
    var myarr = this.getTileLayers();
    for( lcnt = 0; lcnt < myarr.length; lcnt++ ) {
      var obj = myarr[ lcnt ];
      if( obj != null ) {
        arr.push( obj.getCopyright( map.getBounds(), map.getZoom() ) );
      }
    }
    if( !eea_overlay.isHidden() )
      arr.push( copyrightStringEEA );
    if( !i2k_overlay.isHidden() )
      arr.push( copyrightStringI2K ); 
  } catch (e) {
  }
  return arr;
}