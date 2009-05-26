<?php
/**
 * StarQuery.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to construct Star-schema queries on a central fact table.
 * The syntax will be MySQL specific.
 */

require_once 'Select.php';
require_once 'Dimension.php';
require_once 'WhereClause.php';
require_once 'FromClause.php';

class StarQuery extends Select
{
    private $_sFactTable = null;

    private $_sJoinField = null;

    private $_rgDimensions = array();

    private $_rgAdditionalWhere = array();

    public function __construct($sFactTable, $sJoinColumn)
    {
        $this->_sFactTable = $sFactTable;
        $this->_sJoinField = $sJoinColumn;
    }

    public function sqlRender($rgFieldList, $rgOrderFields = null)
    {
        $this->_vAssertInit();

        $sql = 'SELECT %s %s%s';

        $frm = new FromClause(array($this->_sFactTable, 'f'));
        $whr = new WhereClause();

        foreach ($this->_rgDimensions as $ix=>$dim) {
            $sDimAlias = 'd'.($ix + 1);
            $frm->vLeftJoinOnEqual(array($dim->sGetTable(), $sDimAlias), $this->_sJoinField, $dim->sGetJoinColumn());
            $whrT = new WhereClause();
            $whrT->vEquals(array($sDimAlias, $dim->sGetSearchField()), $dim->varGetSearchValue());
            $whr->vAnd($whrT);
        }

        foreach ($this->_rgAdditionalWhere as $whrT) {
            $whr->vAnd($whrT);
        }

        $sql = 'SELECT '
                 .Sql::sqlFieldList($rgFieldList, 'f')
                 .$frm->sqlRender()
                 .$whr->sqlRender()
                 .parent::_sqlOrderClause($rgOrderFields);

        return $sql;
    }

    public function vAddDimension(Dimension $dim)
    {
        $this->_rgDimensions[] = $dim;
    }

    public function vAddWhere(WhereClause $whr)
    {
        $this->_rgAdditionalWhere[] = $whr;
    }

    private function _vAssertInit()
    {
        if (empty($this->_sFactTable) || empty($this->_sJoinField)) {
            throw new Exception("StarQuery was not properly initialized");
        }
    }
}
?>
