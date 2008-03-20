<?php
/**
 * Table Definition for public.user_dams
 */
require_once 'DB/DataObject.php';

class DataObjects_stat_country_dams_valid_user extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.stat_country_dams_valid_user'; // view name
    public $country_code;  
    public $count;  
    public $valid;
    public $cd_user;

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_stat_country_dams_valid_user',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
   
}
