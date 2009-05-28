<?php
/**
 * Implementor.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-27
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Represents the table with implementing entities
 */

require_once 'BaseModel.php';

class Implementor extends BaseModel
{
    const _ID = 'implementing_entity';

    public static function rgIdColSpec()
    {
        return parent::rgIdColSpec(self::_ID);
    }

    public static function map()
    {
        $map = parent::map(self::_ID);
        $map->vSelectMapFields('specification');
        return $map;
    }
}
?>
