<?php
/**
 * MemberState.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-27
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Represents the table with member states.
 */

require_once 'BaseModel.php';

class MemberState extends BaseModel
{
    const _ID = 'member_state';

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
