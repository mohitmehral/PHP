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

class Select
{
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
                       self::_sqlFieldList($rgFields),
                       self::_sqlQuoteId($sTable),
                       self::_sqlWhereClause($mpWhere),
                       self::_sqlOrderClause($rgOrder));

        return $sql;
    }

    private static function _sqlFieldList($rg = null)
    {
        if (is_array($rg) && ($cMax = count($rg)) > 0) {
            for ($c = 0; $c < $cMax; $c++) {
                $rg[$c] = self::_sqlQuoteId($rg[$c]);
            }
            return join(', ', $rg);
        } else {
            return '*';
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
                $rg[] = self::_sqlQuoteId($sField).' = \''.mysql_real_escape_string($sVal).'\'';
            }
            return ' WHERE '.join(' AND ', $rg);
        } else {
            return '';
        }
    }

    private static function _sqlOrderClause($rg)
    {
        if (is_array($mp) && count($mp) > 0) {
            return ' ORDER BY '.self::_sqlFieldList($rg);
        } else {
            return '';
        }
    }

    private static function _sqlQuoteId($s)
    {
        return '`'.addcslashes($s, '`').'`';
    }
}

?>