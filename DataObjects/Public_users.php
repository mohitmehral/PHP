<?php
/**
 * Table Definition for public.users
 */
require_once 'DB/DataObject.php';
require_once 'Public_user_dams.php';

class DataObjects_Public_users extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'public.users';                    // table name
    public $id;                              // int4(4)  
    public $firstname;                       // varchar(-1)  
    public $surname;                         // varchar(-1)  
    public $login;                           // varchar(-1)  
    public $password;                        // varchar(-1)  
    public $email;                           // varchar(-1)  
    public $roleadm;                         // bool(1)  
    public $rolelang;                        // bool(1)  
    public $roledam;                         // bool(1)  
    public $address;                         // text(-1)  
    public $phone;                           // varchar(-1)  

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Public_users',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
    
    function getNextId  (){
    	$do = new DataObjects_Public_Users();
		$do->query("Select max(ID)+1 as seq from ".$this->__table);
		$do->fetch();
		return $do->seq;	
    }
	
	function getDamNumber () {
		$do = new DataObjects_Public_User_Dams();
		$do->whereAdd("CD_USER = ".$this->id.""); 	// Filter on user
		return $do->find();
	}    
}
