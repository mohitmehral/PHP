<?php
/**
 * Table Definition for public.user_dams
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_user_dams_assigned extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.user_dams_assigned'; // view name
    public $cd_user;                           
    public $cd_dam;                            
    public $name;     
    public $valid;
    public $country;                     

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_user_dams_assigned',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
}
