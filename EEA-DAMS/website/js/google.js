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

