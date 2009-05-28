<?php
/**
 * Select.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-25
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to centralize construction of 'SELECT' SQL queries.
 * The syntax will be MySQL specific.
 */

require_once 'Sql.php';
require_once 'FromClause.php';
require_once 'WhereClause.php';
require_once 'MapNtoM.php';

class Select
{
    const _LREF = 'a';
    const _MREF = 'm';
    const _RREF = 'b';

    private $_sTable = null;

    private $_mpFields = null;

    private $_frm = null;

    private $_whr = null;

    private $_rgOrder = array();

    private $_cMaps = 0;

    public function __construct($sTable, $rgFields = null)
    {
        $this->_frm = new FromClause(array($sTable, self::_LREF));
        $this->_whr = new WhereClause();
        $this->_sTable = $sTable;
        $this->_mpFields[$this->_sTable] = $rgFields;
    }

    public function vAddMappedJoin($sCol, MapNtoM $map, $rgTargetSpec, $rgTargetFields = null, $fAllowNulls = true)
    {
        $this->_cMaps++;
        list($sTargetTbl, $sTargetJoinCol) = $rgTargetSpec;

        $rgMapSpec = array($map->sGetTable(), self::_MREF.$this->_cMaps);
        $rgTargetSpec = array($sTargetTbl, self::_RREF.$this->_cMaps);

        $this->_vLeftJoin($rgMapSpec, $sCol, $map->sGetLftCol(), null, $fAllowNulls);
        $this->_vLeftJoin($rgTargetSpec, $map->sGetRgtCol(), $sTargetJoinCol, $rgMapSpec, $fAllowNulls);
        
        if ($map->fMapFieldsSelected()) {
            $this->_mpFields[$map->sGetTable()] = $map->rgGetFields();
        }
        $this->_mpFields[$sTargetTbl] = $rgTargetFields;
    }

    public function vSetFilter($mpFilter)
    {
        foreach ($mpFilter as $sCol=>$varVal) {
            $whr = new WhereClause();
            $whr->vEquals(array($this->_frm->sTableRef($this->_sTable), $sCol), $varVal);
            $this->_whr->vAnd($whr);
        }
    }

    public function vOrderBy($varCol, $fReset = false)
    {
        if ($fReset) {
            $this->_rgOrder = array();
        }
        if (is_array($varCol)) {
            $varCol[0] = $this->_frm->sTableRef($varCol[0]);
        }
        $this->_rgOrder[] = $varCol;
    }

    public function sqlRender()
    {
        $sql = 'SELECT %s%s%s%s';
        
        $rgFieldLists = array();

        foreach ($this->_mpFields as $sTable=>$rgFields) {
            $sRef = $this->_frm->sTableRef($sTable);
            $rgFieldLists[] = Sql::sqlFieldList($rgFields, $sRef);
        }

        $sql = sprintf($sql,
                       join(', ', $rgFieldLists),
                       $this->_frm->sqlRender(),
                       $this->_whr->sqlRender(),
                       self::_sqlOrderClause($this->_rgOrder));

        return $sql;
    }

    /**
     * Create a SELECT query, optionally specifying fields,
     * key-value pairs for a WHERE clause and field names to
     * order by (always ascending order).
     *
     * @param sTable the table to select from, will be enclosed in backticks, JOIN, AS etc. are not supported
     *
     * @param rgFields an array of field names, these will be enclosed in backticks, "AS" is not supported
     *
     * @param mpWhere an associative array with field names as key. Values will be escaped and quoted, comparison is always for equality
     *
     * @param rgOrder if present, a list of field names to order by
     */

    public static function sqlSimpleQuery($sTable, $rgFields = null, $mpWhere = null, $rgOrder = null)
    {
        // as a rule, the optional clauses have to include a leading blank space
        $sql = 'SELECT %s FROM %s%s%s';

        $sql = sprintf($sql,
                       Sql::sqlFieldList($rgFields),
                       Sql::sqlQuoteId($sTable),
                       self::_sqlWhereClause($mpWhere),
                       self::_sqlOrderClause($rgOrder));

        return $sql;
    }

    private function _vLeftJoin($varLftTblSpec, $sLftCol, $sRgtCol, $varRgtTblSpec = null, $fAllowNulls = true)
    {
        $this->_frm->vLeftJoinOnEqual($varLftTblSpec, $sLftCol, $sRgtCol, $varRgtTblSpec);
        if (empty($varRgtTblSpec)) {
            $varRgtTblSpec = $this->_sTable;
        }
        if (!$fAllowNulls) {
            $whr = new WhereClause();
            $whr->vNotNull(array($this->_frm->sTableRef($varRgtTblSpec), $sRgtCol));
            $this->_whr->vAnd($whr);
        }
    }

    private static function _sqlWhereClause($mp = null)
    {
        if (is_array($mp) && count($mp) > 0) {
            $rg = array();
            foreach ($mp as $sField=>$sVal) {
                // We will treat numbers the same as strings and quote them here.
                // This would not work with some DB servers (e. g. SQL Server 2005),
                // but should work OK with MySQL.
                $rg[] = Sql::sqlQuoteId($sField).' = '.Sql::sqlQuoteValue($sVal);
            }
            return ' WHERE '.join(' AND ', $rg);
        } else {
            return '';
        }
    }

    protected static function _sqlOrderClause($rg)
    {
        if (is_array($rg) && count($rg) > 0) {
            return ' ORDER BY '.Sql::sqlFieldList($rg);
        } else {
            return '';
        }
    }
}

?>
