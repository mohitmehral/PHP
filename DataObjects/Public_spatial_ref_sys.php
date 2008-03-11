<?php
/**
 * Table Definition for public.spatial_ref_sys
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_spatial_ref_sys extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'public.spatial_ref_sys';          // table name
    var $srid;                            // int4(4)  
    var $auth_name;                       // varchar(-1)  
    var $auth_srid;                       // int4(4)  
    var $srtext;                          // varchar(-1)  
    var $proj4text;                       // varchar(-1)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_spatial_ref_sys',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
