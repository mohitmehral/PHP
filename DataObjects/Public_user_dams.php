<?php
/**
 * Table Definition for public.user_dams
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_user_dams extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.user_dams';                // table name
    public $cd_user;                         // int8(8)  
    public $cd_dam;                          // varchar(-1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_user_dams',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
}
