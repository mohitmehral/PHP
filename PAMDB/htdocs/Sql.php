<?php
/**
 * Sql.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class provides some helper methods to centralize important functionality
 * like escaping and quoting. This helps security and portability between DBMSs.
 */

class Sql
{
    public static function sqlQuoteId($s)
    {
        return '`'.addcslashes($s, '`').'`';
    }

    public static function sqlQuoteValue($var)
    {
        if (is_null($var)) {
            return 'NULL';
        } else if (is_numeric($var)) {
            return $var;
        } else {
            return '\''.mysql_real_escape_string($var).'\'';
        }
    }

    public static function sqlQualifiedCol($rg)
    {
        if (!is_array($rg) || count($rg) != 2) {
            throw new Exception("Invalid column specification");
        } else {
            $rg = array_values($rg);
        }
        return self::sqlQuoteId($rg[0]).'.'.self::sqlQuoteId($rg[1]);
    }

    public static function sqlFieldList($rg = null)
    {
        if (is_array($rg) && ($cMax = count($rg)) > 0) {
            for ($c = 0; $c < $cMax; $c++) {
                $rg[$c] = Sql::sqlQuoteId($rg[$c]);
            }
            return join(', ', $rg);
        } else {
            return '*';
        }
    }
}
?>
