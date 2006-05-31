<?php
/**
 * Table Definition for public.meta
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_meta extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'public.meta';                     // table name
    var $NAME;                            // varchar(-1)  
    var $VALUE;                           // varchar(-1)  

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_meta',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
