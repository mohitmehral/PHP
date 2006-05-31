<?php
/**
 * Table Definition for public.langs_avail
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_langs_avail extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.langs_avail';              // table name
    public $id;                              // varchar(-1)  
    public $name;                            // varchar(-1)  
    public $meta;                            // text(-1)  
    public $error_text;                      // varchar(-1)  
    public $encoding;                        // varchar(-1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_langs_avail',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
