<?php
/**
 * Table Definition for public.country
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_country extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.country';                  // table name
    public $code;                            // varchar(-1)  
    public $name;                            // varchar(-1)  
    public $type;                            // varchar(-1)  
    public $lang;                            // varchar(-1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_country',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
