<?php
/**
 * Model.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-25
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to provide the dynamic data used on the pages.
 */

require_once 'DB.php';
require_once 'Select.php';

class Model
{
    public static function rgGetSectors()
    {
        $sql = Select::sqlSimpleQuery('val_sector', array('id_sector', 'sector'));
        return DB::rgSelectRows($sql);
    }
}
