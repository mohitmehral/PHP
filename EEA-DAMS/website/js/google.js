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
  if( mkType == 1 || mkType == 3 ) // mkType == 2 is not draggable
  {
    marker = new GMarker(point, {icon: icon, draggable: true });
    marker.enableDragging();
    GEvent.addListener( marker, "dragend", damDragEndListener );
  } else {
    marker = new GMarker(point, {icon: icon, draggable: false });
  }
  marker.markerType = mkType;
  var html = "" + desc + " <br/>Longitude :"+ point.x+"<br/>Latitude :"+ point.y;
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
      case 1: // Seed position - Red cross
        var xIniCtrl = document.getElementById( "xini" );
        var yIniCtrl = document.getElementById( "yini" );
        if( xIniCtrl != null ) xIniCtrl.value = position.x;
        if( yIniCtrl != null ) yIniCtrl.value = position.y;       
      break;
    }
  } catch( e ) {
    alert( "Dragging exception. Reason: " + e.message );
  } 
}


