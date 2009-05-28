<?php
/**
 * SideEffect.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-27
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Represents the table specifying the effect of a measure
 * on non-greenhouse gases.
 */

require_once 'BaseModel.php';

class SideEffect extends BaseModel
{
    const _ID = 'reduces_non_ghg';

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
