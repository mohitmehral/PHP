<?php
/**
 * Table Definition for public.log
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_log extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.log';                      // table name
    public $user_id;                         // int8(8)  
    public $dam_id;                          // varchar(-1)  
    public $log_type;                        // int8(8)  
    public $date;                            // date(4)  
    public $comment;                         // varchar(-1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_log',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
