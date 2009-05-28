<?php
/**
 * Scenario.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-27
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Represents the scenario a particular measure is associated with
 */

require_once 'BaseModel.php';

class Scenario extends BaseModel
{
    const _ID = 'with_or_with_additional_measure';

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
