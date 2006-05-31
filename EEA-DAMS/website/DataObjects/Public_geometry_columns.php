<?php
/**
 * Table Definition for public.geometry_columns
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_geometry_columns extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'public.geometry_columns';         // table name
    var $f_table_catalog;                 // varchar(-1)  
    var $f_table_schema;                  // varchar(-1)  
    var $f_table_name;                    // varchar(-1)  
    var $f_geometry_column;               // varchar(-1)  
    var $coord_dimension;                 // int4(4)  
    var $srid;                            // int4(4)  
    var $type;                            // varchar(-1)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_geometry_columns',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
