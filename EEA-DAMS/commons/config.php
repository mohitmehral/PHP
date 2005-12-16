<?php
	/**
     * EEA-DAMS Config.php
     *
     * Dam exact position (expressed as geographical coordinates) is generally unavailable. 
     * A special process has been developed by the EEA (AIR3) to locate as 
     * accurately as possible the dams listed in the Icold register on large 
     * dams. This pre-location task is carried out by ETC/TE. The current 
     * number of large dams is ~6000 in the EEA area.
	 * Following agreement with Icold, the national focal points of Icold 
	 * will be requested to accept / correct the proposed location. These 
	 * organisations are based in countries and know accurately where dams are, 
	 * even though they do not systematically have the coordinates at their 
	 * disposal. To check / correct the position, it has been considered that 
	 * an Internet tool, providing an image of the most likely position 
	 * and a facility to fix a new position by drag-and-drop a marker on the 
	 * image would be the best solution from the point of view of minimizing 
	 * the burden, avoiding copyright issues nevertheless ensuring security in 
	 * transactions.
	 * This arrangement follows the methodology of pre-positioning and 
	 * positioning dams developed by AIR3 and delivered to the ETC/TE that 
	 * has to carry out this work.
	 * 
     * In addition, in version 1.2+ one can link to extended documentation like this
     * documentation using {@tutorial phpDocumentor/phpDocumentor.howto.pkg}
     * In a method/class var, {@inheritdoc may be used to copy documentation from}
     * the parent method
     *
     * @abstract	 Configuration file. Initialization of DB, Lang, Log.
     * @author       François-Xavier Prunayre <fx.prunayre@oieau.fr>
     * @copyright    2005
     * @version    	 1.0
     *
     * 
     */
 
error_reporting (E_ALL & ~E_NOTICE);
//error_reporting (E_ALL );

require_once 'inc-config.php';

require_once 'PEAR.php';
require_once 'libs/Smarty.class.php';
require_once 'Translation2.php';
require_once 'Translation2/Admin.php';
require_once 'Auth.php';
require_once 'Log.php';
require_once 'DB/DataObject.php';
require_once 'DataObjects/Public_i18n.php';

// set the parameters to connect to your db
$dbinfo = array(
    'hostspec' => DB_SERVER,
    'database' => 'translation2',
    'phptype'  => DB_TYPE,
    'username' => DB_USER,
    'password' => DB_PASS
);

/**
 * 
 *  DB init 
 * 
 */
define ('DB',DB_TYPE.'://'.DB_USER.':'.DB_PASS.'@'.DB_SERVER.'/'.DB_NAME);

$options = &PEAR::getStaticProperty('DB_DataObject','options');
$options = array(
    'database'         => DB,
    'schema_location'  => SCHEMADIR,
    'class_location'   => SCHEMADIR,
    'require_prefix'   => 'DataObjects/',
    'class_prefix'     => 'DataObjects_'		// could be DataObjects_Public depending on DBObj version
);



/**
 * 
 *  Country init 
 * 
 */


/**
 * Load all term for a page
 * 
 * @param        string [$i18nPage] the name of the page
 * @param        Smarty [$smarty] the smarty object managing templates
 * @param        i18n [$i18n] the Translation2 object managing i18n
 * @return       smarty object
 * @name         iniI18n
 * @todo		 return false on error         
 * 
 */
function iniI18n ($i18nPage, $smarty, $i18n){	
	$do = new DataObjects_Public_i18n();
	$do->whereAdd("PAGE_ID = '".$i18nPage."'");
	$do->find();
	
	while ($do->fetch())
		$smarty->assign($do->id, 	$i18n->get($do->id, $i18nPage));

	$do->free();	
	return $smarty;
}


/**
 * 
 *  Login init 
 * 
 */
$paramsLogin = array (            
	"dsn" => DB,            
	"table" => "users",            
	"usernamecol" => "login",            
	"passwordcol" => "password"            
	);
	
/**
 * 
 *  i18n init 
 * 
 */
define('TABLE_PREFIX', '');
$params = array(
    'langs_avail_table' => TABLE_PREFIX.'langs_avail',
    'lang_id_col'     => 'ID',
    'lang_name_col'   => 'name',
    'lang_meta_col'   => 'meta',
    'lang_errmsg_col' => 'error_text',
    'strings_tables'  => array(
                            'en' => TABLE_PREFIX.'i18n',
                            'de'   => TABLE_PREFIX.'i18n',  
							'fr'   => TABLE_PREFIX.'i18n',
							'it' => TABLE_PREFIX.'i18n',
							'cs'   => TABLE_PREFIX.'i18n',  	
							'da'   => TABLE_PREFIX.'i18n',  	
							'de'   => TABLE_PREFIX.'i18n',  	
							'et'   => TABLE_PREFIX.'i18n',  	
							'el'   => TABLE_PREFIX.'i18n',  	
							'en'   => TABLE_PREFIX.'i18n',  	
							'it'   => TABLE_PREFIX.'i18n',  	
							'lv'   => TABLE_PREFIX.'i18n',  	
							'lt'   => TABLE_PREFIX.'i18n',  	
							'hu'   => TABLE_PREFIX.'i18n',  	
							'pl'   => TABLE_PREFIX.'i18n',  	
							'sk'   => TABLE_PREFIX.'i18n',
							'sl'   => TABLE_PREFIX.'i18n',  	
							'fi'   => TABLE_PREFIX.'i18n',  	
							'sv'   => TABLE_PREFIX.'i18n',  	
							'mt'   => TABLE_PREFIX.'i18n',  	
							'nl'   => TABLE_PREFIX.'i18n',
							 'bg'   => TABLE_PREFIX.'i18n',
                                                       'no'   => TABLE_PREFIX.'i18n',
                                                       'ro'   => TABLE_PREFIX.'i18n',
                                                       'tr'   => TABLE_PREFIX.'i18n',
                                                        'ss'   => TABLE_PREFIX.'i18n'
                         ),
    'string_id_col'      => 'ID',
    'string_page_id_col' => 'page_ID',
    'string_text_col'    => '%s'  			//'%s' will be replaced by the lang code
);

$driver = 'DB';

$file = &Log::singleton('file', 'tmp/out.log', 'DAMS');




$i18n = Translation2::factory($driver,  DB, $params);

if (isset($_REQUEST["lang"]))
	$i18nlang = $_REQUEST["lang"];
else
	$i18nlang = 'en';						// Default to english	

	
$i18n->setLang($i18nlang);			
$i18n =& $i18n->getDecorator('Lang');
$i18n->setOption('fallbackLang', 'en');		// Fallback into english if none


/**
 *  
 *  Smarty templates init
 *  
 */
$smarty = new Smarty;
$smarty->template_dir 	= TPLDIR.'templates/';
$smarty->compile_dir 	= TPLDIR.'templates_c/';
$smarty->config_dir 	= TPLDIR.'configs/';
$smarty->cache_dir 		= TPLDIR.'cache/';

$smarty->compile_check 	= true;
$smarty->debugging 		= false;					

/* General reference for templates */
$smarty->assign("langId", 	$i18nlang);
$smarty->assign("langIds", 	$i18n->getLangs('ids'));
$smarty->assign("langNames",$i18n->getLangs('names'));
$smarty->assign("URL",		$_SERVER['PHP_SELF']);
$smarty->assign("VALIDICON",VALIDICON);
$smarty->assign("ICOLDICON",ICOLDICON);
$smarty->assign("EEAICON",	EEAICON);
$smarty->assign("GOOGLEMAPKEY",	GOOGLEMAPKEY);

$_SESSION["urlFilter"] = '&amp;';				// URL filter in session

$smarty->assign("urlFilter",$_SESSION["urlFilter"] );
$smarty->assign("urlNext",	'');
$smarty->assign("Name",		TITLE);

$googleOn = true;
$RSSOn = true;									// Not used 

$i18nPage = 'all';
$smarty = iniI18n ($i18nPage, $smarty, $i18n);


/** 
 * 
 * Login init 
 * 
 */
require_once "login.php";



/**
 * Return Javascript header for google map interface
 * 
 * The createMarker function init all markers using point, id and 
 * icon url.
 * 
 * @return       string
 * @name         displayGoogleMapHead        
 * 
 */
function displayGoogleMapHead ($x = 4, $y = 52, $z = 13){
return '<script type="text/javascript">
    //<![CDATA[
function createMarker(point, id, iconimg) {
	var icon = new GIcon();
	icon.image = iconimg;
	icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
	icon.iconSize = new GSize(20, 34);
	icon.shadowSize = new GSize(37, 34);
	icon.iconAnchor = new GPoint(6, 20);
	icon.infoWindowAnchor = new GPoint(5, 1);
	
	var marker = new GMarker(point, icon);
	GEvent.addListener(marker, "click", function() {
    		location.replace ("dams.php?cd="+id);
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
    	
	// Setup the map
    	var map = new GMap(document.getElementById("map"), mapSpecs);
	//var map = new GMap(document.getElementById("map"));
	GEvent.addListener(map, "click", function(overlay, point) {
		document.carto_form.x.value = point.x;
		document.carto_form.y.value = point.y;
	});


	 map.addControl(new GMapTypeControl());
  	 map.addControl(new GSmallMapControl());
	 map.setMapType(G_MAP_EEA_OVER_SAT) 
	 map.centerAndZoom(new GPoint('.$x.', '.$y.'), '.$z.');
    ';}
    
/**
 * Return Javascript footer for google map interface
 * 
 *  
 * @return       string
 * @name         displayGoogleMapFoot        
 * @todo 		 multilingual for alert
 * 
 */
function displayGoogleMapFoot (){
return '/* } else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }*/  //]]>
    </script>';
}
   
/**
 * Return Javascript for google map point
 * 
 * Create a point on given location. Icon could be defined using an 
 * URL (ie. http://my.site.net/image.png)
 * 
 * 
 * @param        double [$x] X coordinate of the point to be drawn
 * @param        double [$y] Y coordinate of the point to be drawn
 * @param		 string [$id] text to be display in the popup info
 * @param        string [$name] not used
 * @param		 string [$iconurl] URL for the icon
 * @return       string
 * @name         googleMarker        
 * 
 */ 
function googleMarker ($x, $y, $id, $name = "", $iconurl = ICOLDICON){
    return '
        var point'.$id.' = new GPoint('.$x.', '.$y.');
        var marker'.$id.' = createMarker(point'.$id.', "'.$id.'", "'.$iconurl.'");
        map.addOverlay(marker'.$id.');';
}


?>

