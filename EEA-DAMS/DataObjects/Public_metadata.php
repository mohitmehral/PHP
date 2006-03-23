<?php
/**
 * Table Definition for public.metadata
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_metadata extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'public.metadata';                  // table name
    var $code;                            // varchar(-1)  
    var $value;                           // varchar(-1)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_metadata',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
