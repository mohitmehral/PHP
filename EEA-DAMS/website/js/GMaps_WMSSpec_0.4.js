//==============================================================
//Spatial Data Logic - 2005
//WMS Map Spec
function WMSSpec (baseUrl, name, shortName, layers, styles, format, copywrite, version, bgcolor, transparent, srs)
{
	var spec = new GGoogleMapMercSpec();
	this.BaseSpec = spec;
	this.baseUrl = baseUrl;
	this.Map = map;
    this.tileSize=spec.tileSize;
    this.backgroundColor=spec.backgroundColor;
    this.emptyTileURL=spec.emptyTileURL;
	this.waterTileUrl=spec.waterTileUrl;
    this.numZoomLevels=spec.numZoomLevels;
    this.pixelsPerDegree=spec.pixelsPerDegree;
    this.mapBounds=spec.mapBounds;
    this.ukBounds=spec.ukBounds;
    this.earthBounds=spec.earthBounds;
	this.Name = name;
	this.ShortName = shortName;
	this.Layers = layers;
	this.Styles = styles;
	this.Format = format;
	
	//Optional - added 8/1/05
	this.Copywrite = copywrite;
	this.Version = "1.1.1";
	if (version != null) {this.Version = version;}
	this.BGColor = "0xFFFFFF";
	if (bgcolor != null) {this.BGColor = bgcolor;}
	this.Transparent = true;
	if (transparent != null) {this.Transparent = transparent;}
	this.SRS = "4326";
	if (srs != null) {this.SRS = srs;}
	this.OverlaySpec = null;
}

WMSSpec.prototype.adjustBitmapX=function(a,b)
{
    return this.BaseSpec.adjustBitmapX(a,b);
}

WMSSpec.prototype.getBitmapCoordinate=function(a,b,c,d)
{
    return this.BaseSpec.getBitmapCoordinate(a,b,c,d);
}

WMSSpec.prototype.getLatLng=function(a,b,c,d)
{
    return this.BaseSpec.getLatLng(a,b,c,d);
}

WMSSpec.prototype.getTileCoordinate=function(a,b,c,d)
{
    return this.BaseSpec.getTileCoordinate(a,b,c,d);
}


WMSSpec.prototype.hasOverlay=function()
{
    return true;//this.OverlaySpec!=null;
}

WMSSpec.prototype.getOverlayURL=function(a,b,c)
{
	var url = this.OverlaySpec.getTileURL(a,b,c);
	if (this.OverlaySpec.hasOverlay) 
	{
		//take the overlay of our overlay spec (e.g. hybrid map)
		url = this.OverlaySpec.getOverlayURL(a,b,c);
	}
	return url;

}

WMSSpec.prototype.getTileURL=function(a,b,c)
{
	var ts = this.tileSize;
	var ul = this.getLatLng(a*ts,(b+1)*ts, c);
	var lr = this.getLatLng((a+1)*ts, b*ts, c);
	var bbox = "BBOX=" + ul.x + "," + ul.y + "," + lr.x + "," + lr.y;
	var t = "FALSE";
	if (this.Transparent){t = "TRUE";}
	var url = this.baseUrl + "REQUEST=GetMap&SERVICE=WMS&VERSION=" + this.Version + "&LAYERS=" + this.Layers + "&STYLES=" + this.Styles + "&FORMAT=" + this.Format + "&BGCOLOR=" + this.BGColor + "&TRANSPARENT=" + t + "&SRS=EPSG:" + this.SRS + "&" + bbox + "&WIDTH=" + ts + "&HEIGHT=" + ts;
	return url;
}

WMSSpec.prototype.getLowestZoomLevel=function(a,b,c)
{
    return this.BaseSpec.getLowestZoomLevel(a,b,c);
}

WMSSpec.prototype.getPixelsPerDegree=function(a)
{
   return this.BaseSpec.getPixelsPerDegree(a);
}

WMSSpec.prototype.getLinkText=function()
{
    return this.Name;
}

WMSSpec.prototype.getShortLinkText=function()
{
    return this.ShortName;
}

WMSSpec.prototype.getURLArg=function()
{
    return this.BaseSpec.getURLArg();
}

WMSSpec.prototype.getCopyright=function()
{
    return this.Copywrite;
}

WMSSpec.prototype.zoomBitmapCoord=function(a,b,c)
{
    return this.BaseSpec.zoomBitmapCoord(a,b,c);
}