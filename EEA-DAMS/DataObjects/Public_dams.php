<?php
/**
 * Table Definition for public.dams
 */
require_once 'DB/DataObject.php';
require_once 'commons/inc-config.php';

class DataObjects_Public_dams extends DB_DataObject 
{

    var $outOfRange = -32768;
    var $countryCoord = array('MK'=>array('X'=>21.7425,'Y'=>41.60485,'Z'=>9),
'GR'=>array('X'=>21.569391,'Y'=>39.7007,'Z'=>10),
'AT'=>array('X'=>13.3377,'Y'=>47.69785,'Z'=>11),
'DE'=>array('X'=>10.4523,'Y'=>51.1638,'Z'=>11),
'EE'=>array('X'=>25.81285,'Y'=>58.5926,'Z'=>4),
'RU'=>array('X'=>46.69845,'Y'=>55.57085,'Z'=>11),
'AM'=>array('X'=>45.03985,'Y'=>40.0657,'Z'=>9),
'AZ'=>array('X'=>47.68705,'Y'=>40.15095,'Z'=>8),
'BE'=>array('X'=>4.47675,'Y'=>50.50075,'Z'=>10),
'BA'=>array('X'=>17.68275,'Y'=>43.91395,'Z'=>10),
'BG'=>array('X'=>25.4911,'Y'=>42.72845,'Z'=>11),
'CS'=>array('X'=>20.4911,'Y'=>43.72845,'Z'=>10),
'HR'=>array('X'=>15.40791,'Y'=>44.7304,'Z'=>11),
'CY'=>array('X'=>33.4282,'Y'=>35.1298,'Z'=>9),
'CZ'=>array('X'=>15.47875,'Y'=>49.80435,'Z'=>11),
'DK'=>array('X'=>9.51735,'Y'=>56.27545,'Z'=>10),
'FO'=>array('X'=>-6.99665,'Y'=>62.12325,'Z'=>8),
'FI'=>array('X'=>26.0659,'Y'=>64.95065,'Z'=>13),
'FR'=>array('X'=>1.7186,'Y'=>46.71055,'Z'=>12),
'GE'=>array('X'=>43.3657,'Y'=>42.31285,'Z'=>7),
'GI'=>array('X'=>-5.35155,'Y'=>36.13225,'Z'=>10),
'HU'=>array('X'=>19.50865,'Y'=>47.1572,'Z'=>11),
'IS'=>array('X'=>-19.0239,'Y'=>64.95995,'Z'=>11),
'IE'=>array('X'=>-8.23955,'Y'=>53.41435,'Z'=>11),
'IT'=>array('X'=>12.5729,'Y'=>42.5021,'Z'=>12),
'LV'=>array('X'=>24.6064,'Y'=>56.87325,'Z'=>6),
'LI'=>array('X'=>9.5541,'Y'=>47.15805,'Z'=>9),
'LT'=>array('X'=>23.94885,'Y'=>55.1699,'Z'=>6),
'LU'=>array('X'=>6.133,'Y'=>49.81535,'Z'=>9),
'MT'=>array('X'=>14.45,'Y'=>35.903,'Z'=>8),
'MD'=>array('X'=>28.3907,'Y'=>46.9792,'Z'=>8),
'MC'=>array('X'=>7.4259,'Y'=>43.7379,'Z'=>9),
'NL'=>array('X'=>5.33135,'Y'=>52.10905,'Z'=>10),
'NO'=>array('X'=>12.664777,'Y'=>64.5562,'Z'=>13),
'PL'=>array('X'=>19.1362,'Y'=>51.92095,'Z'=>11),
'PT'=>array('X'=>-7.84655,'Y'=>39.5609,'Z'=>11),
'SP'=>array('X'=>-3.84655,'Y'=>39.5609,'Z'=>12),
'RO'=>array('X'=>24.99465,'Y'=>45.94,'Z'=>11),
'SM'=>array('X'=>12.45965,'Y'=>43.9407,'Z'=>3),
'SK'=>array('X'=>19.6992,'Y'=>48.67195,'Z'=>10),
'SI'=>array('X'=>14.9863,'Y'=>46.1491,'Z'=>10),
'ES'=>array('X'=>-2.98855,'Y'=>39.89555,'Z'=>11),
'SE'=>array('X'=>17.560238,'Y'=>62.1987,'Z'=>13),
'CH'=>array('X'=>8.2248,'Y'=>46.81335,'Z'=>4),
'TR'=>array('X'=>35.4497,'Y'=>38.9565,'Z'=>13),
'UA'=>array('X'=>31.1781,'Y'=>48.3821,'Z'=>11),
'GB'=>array('X'=>-2.2309,'Y'=>54.3168,'Z'=>12),
'VT'=>array('X'=>12.45145,'Y'=>41.9014,'Z'=>3),
'BJ'=>array('X'=>20.7207,'Y'=>44.01085,'Z'=>11),
'AL'=>array('X'=>20.18325,'Y'=>41.15605,'Z'=>8)
	);
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.dams';                     // table name
    public $noeea;                           // varchar(-1)  
    public $name;                            // varchar(-1)  
    public $x_icold;                         // float8(8)  
    public $y_icold;                         // float8(8)  
    public $score;                           // int2(2)  
    public $x_prop;                          // float8(8)  
    public $y_prop;                          // float8(8)  
    public $x_val;                           // float8(8)  
    public $y_val;                           // float8(8)  
    public $area;                            // float8(8)  
    public $cap_total;                       // float8(8)  
    public $ic_city;                         // varchar(-1)  
    public $country;                         // varchar(-1)  
    public $lake_name;                       // varchar(-1)  
    public $river_id;                        // varchar(-1)  
    public $river_name;                      // varchar(-1)  
    public $year_opp;                        // varchar(-1)  
    public $year_dead;                       // varchar(-1)  
    public $comments;                        // text(-1)  
    public $valid;                           // bool(1)  
    public $ic_continent;                    // varchar(-1)  
    public $is_main;                         // bool(1)  
    public $noeea_m;                         // varchar(-1)  
    public $is_icold;                        // bool(1)  
    public $alias;                           // varchar(-1)  
    public $ic_year;                         // varchar(-1)  
    public $ic_state;                        // varchar(-1)  
    public $ic_high;                         // float8(8)  
    public $ic_high_guessed;                 // bool(1)  
    public $ic_length;                       // float8(8)  
    public $ic_length_guessed;               // bool(1)  
    public $ic_vol;                          // float8(8)  
    public $ic_purpose;                      // varchar(-1)  
    public $ic_owner;                        // varchar(-1)  
    public $ic_note;                         // text(-1)  
    public $ic_particular;                   // varchar(-1)  
    public $ic_international;                // varchar(-1)  
    public $ic_sealing;                      // varchar(-1)  
    public $ic_foundation;                   // varchar(-1)  
    public $ic_capacity;                     // float8(8)  
    public $ic_area;                         // float8(8)  
    public $ic_spill;                        // float8(8)  
    public $ic_type_spill;                   // varchar(-1)  
    public $ic_engineer;                     // varchar(-1)  
    public $ic_contractor;                   // varchar(-1)  
    public $ic_p_mw;                         // varchar(-1)  
    public $ic_e_whpyear;                    // varchar(-1)  
    public $ic_irrigation;                   // varchar(-1)  
    public $ic_floodstock;                   // varchar(-1)  
    public $ic_settlement;                   // varchar(-1)  
	var $is_oncanal;
    var $is_dyke;


    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_dams',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    /* displayGoogleMap */
    function displayGoogleMap (){
	$gMap = '<script language="javascript" type="text/javascript">
    //<![CDATA[
function createMarker(point, id, iconimg) {
	var icon = new GIcon();
	icon.image = iconimg;
	icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
	icon.iconSize = new GSize(37, 37);
	icon.shadowSize = new GSize(37, 34);
	icon.iconAnchor = new GPoint(19, 19);
	icon.infoWindowAnchor = new GPoint(5, 1);

	var marker = new GMarker(point, icon);

	var html = "" + id + " <br/>Longitude :"+ point.x+"<br/>Latitude :"+ point.y;
	GEvent.addListener(marker, "click", function() {
	    marker.openInfoWindowHtml(html);
	  });

  return marker;
}

	//if (GBrowserIsCompatible()) {
    	//var WMS_URL_EEA="http://dev.sandre.eaufrance.fr/eeadamsgeo?";
		var WMS_URL_EEA="http://dampos-demo.eea.eu.int/cgi-bin/wseea?";
		var WMS_URL_EEAGEONODE="http://geonode.eea.eu.int/wmsconnector/com.esri.wms.Esrimap?"
        var WMS_URL_JRC="http://wise.jrc.it/cgi-bin/mapserv?map=/home/www/utils-cgi-bin/map/wms.map&";
        var WMS_URL_I2K="http://mapserver.jrc.it/wmsconnector/com.esri.wms.Esrimap/image2000_pan?";

        var G_MAP_EEA = createWMSSpec(WMS_URL_EEA, "Admin.", "WMS", "SM,A7", "default", "image/png", "1.1.1", "(c) Teleatlas");
        var G_MAP_JRC_I2K = createWMSSpec(WMS_URL_I2K,"I2K.", "WMS", "0", "default", "image/png", "1.1.1", "(c) European Commission");

        var G_MAP_EEA_GEONODE = createWMSSpec(WMS_URL_EEAGEONODE, "EEA", "WMS", "100,200,300,Counties,Capitals,ContryBorder,RiverLarge,Riverlarge_label,RiverMedium,Coastline,Villages", "default", "image/png", "1.1.1", "(c) Teleatlas");

        var G_MAP_JRC = createWMSSpec(WMS_URL_JRC, "CCM", "WMS", "RIVERSEGMENTS", "default", "image/png", "1.1.1", "CCM River and Catchment Database JRC/IES (c) European Commission - JRC, 2003");


        // Create a transparent overlay on a Google MapSpec
        var G_MAP_EEA_OVER_SAT = createWMSOverlaySpec(G_SATELLITE_TYPE, G_MAP_EEA, "Admin", "Admin");
        var G_MAP_JRC_OVER_SAT = createWMSOverlaySpec(G_SATELLITE_TYPE, G_MAP_JRC, "CCM", "CCM");
        var G_MAP_EEA_OVER_I2K = createWMSOverlaySpec(G_MAP_JRC_I2K, G_MAP_EEA, "I2K", "I2K");

        var mapSpecs = [];
        //mapSpecs.push(G_MAP_TYPE);
        mapSpecs.push(G_SATELLITE_TYPE);
        mapSpecs.push(G_MAP_EEA_OVER_SAT);
        mapSpecs.push(G_MAP_JRC_OVER_SAT);
        mapSpecs.push(G_MAP_EEA_OVER_I2K);
        mapSpecs.push(G_MAP_EEA_GEONODE);
	
  	var map = new GMap(document.getElementById("map"), mapSpecs);
	GEvent.addListener(map, "click", function(overlay, point) {
		document.carto_form.x.value = point.x;
		document.carto_form.y.value = point.y;
	});
  	 		
	map.addControl(new GMapTypeControl());
  	map.addControl(new GSmallMapControl());
	map.setMapType(G_MAP_EEA_OVER_SAT) 
';


    if ($this->x_icold==null || $this->y_icold==null || 
    	$this->x_icold=='' || $this->y_icold=='' || 
    	$this->x_icold==0 || $this->y_icold==0 ||		
    	$this->x_icold==$this->outOfRange || $this->y_icold==$this->outOfRange ){  
		$gMap.= 'map.centerAndZoom(new GPoint('.
					$this->countryCoord[$this->country]["X"].', '.
					$this->countryCoord[$this->country]["Y"].'), '.
					$this->countryCoord[$this->country]["Z"].');';	// ICOLD or Country center
	
	}else{
		$gMap.= 'map.centerAndZoom(new GPoint('.$this->x_icold.', '.$this->y_icold.'), 4);';	// ICOLD or Country center

		$gMap.= '
		var pointICOLD = new GPoint('.$this->x_icold.', '.$this->y_icold.');
      	  	var markerICOLD = createMarker(pointICOLD, "ICOLD position", "'.ICOLDICON.'");
       	map.addOverlay(markerICOLD);';
	
	}
	
	
	if ($this->x_val==null || $this->y_val==null || 
		$this->y_val=='' ||	$this->x_val=='' || 
		$this->x_val==0 || $this->y_val==0 || 
		$this->x_val==$this->outOfRange || $this->y_val==$this->outOfRange)
	{}
	else {  
		$gMap.= '
		 var pointVAL = new GPoint('.$this->x_val.', '.$this->y_val.');
       	 var markerVAL = createMarker(pointVAL, "Validated position", "'.VALIDICON.'");
	        map.addOverlay(markerVAL);';
	}
	if (($this->x_prop!=null && $this->y_prop!=null) || 
	($this->x_prop!='' && $this->y_prop!='') || 
	($this->x_prop!=0 && $this->y_prop!=0) ||		
	($this->x_prop!=$this->outOfRange && $this->y_prop!=$this->outOfRange ) ){  
	$gMap.= '
		 var pointEEA = new GPoint('.$this->x_prop.', '.$this->y_prop.');
       	 var markerEEA = createMarker(pointEEA, "Proposed position", "'.EEAICON.'");
	        map.addOverlay(markerEEA);';
	}
	
	$gMap .='
		
	   /* } else {
	      alert("Sorry, the Google Maps API is not compatible with this browser");
	    }*/
	    //]]>
	    </script>
	';

	return $gMap;
    }

    function getCountryList  (){
    	$do = new DataObjects_Public_Dams();
		$do->query("Select distinct COUNTRY from ".$this->__table." order by 1");
		$i = 0;
		
		while ($do->fetch())
			$a[$i++]=$do->country;	
		
		return $a;
    }


}
