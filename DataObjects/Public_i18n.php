<?php
/**
 * Table Definition for public.i18n
 */
require_once 'DB/DataObject.php';

class DataObjects_Public_i18n extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.i18n';                     // table name
    public $id;                              // text(-1)  
    public $page_id;                         // varchar(-1)  
    public $en;                              // text(-1)  
    public $fr;                              // text(-1)  
    public $it;                              // text(-1)  
    public $et;                              // text(-1)  
    public $da;                              // text(-1)  
    public $cz;                              // text(-1)  
    public $de;                              // text(-1)  
    public $pl;                              // text(-1)  
    public $es;                              // text(-1)  
    public $el;                              // text(-1)  
    public $lv;                              // text(-1)  
    public $cs;                              // text(-1)  
    public $lt;                              // text(-1)  
    public $hu;                              // text(-1)  
    public $mt;                              // text(-1)  
    public $nl;                              // text(-1)  
    public $pt;                              // text(-1)  
    public $sk;                              // text(-1)  
    public $sl;                              // text(-1)  
    public $fi;                              // text(-1)  
    public $sv;                              // text(-1)  
    public $bg;                              // text(-1)  
    public $no;                              // text(-1)  
    public $ro;                              // text(-1)  
    public $tr;                              // text(-1)  
    public $ss;                              // text(-1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_i18n',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
