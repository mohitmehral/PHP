<?php
/**
 * Type.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-27
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Represents the type of a particular measure
 */

require_once 'BaseModel.php';

class Type extends BaseModel
{
    const _ID = 'type';

    public static function rgIdColSpec()
    {
        return parent::rgIdColSpec(self::_ID);
    }

    public static function map()
    {
        return parent::map(self::_ID);
    }
}
?>
