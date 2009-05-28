<?php
/**
 * BaseModel.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-27
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Base class for all model classes that represent 
 * DB entities.
 */

require_once 'MapNtoM.php';

abstract class BaseModel
{
    const _VAL_TBL_PRFX = 'val_';
    const _MAP_TBL_PRFX = 'pam_';
    const _ID_COL_PRFX = 'id_';
    const _FK_COL_NAME = 'id';

    protected $_sId = '';

    protected function __construct($s)
    {
        $this->_sId = $s;
    }

    public static function rgIdColSpec($sId)
    {
        return array(self::sTblName($sId), self::_ID_COL_PRFX.$sId);
    }

    public static function map($sId)
    {
        return new MapNtoM(self::_MAP_TBL_PRFX.$sId, self::_FK_COL_NAME, self::_ID_COL_PRFX.$sId);
    }

    public static function sTblName($sId)
    {
        return self::_VAL_TBL_PRFX.$sId;
    }
}

?>
