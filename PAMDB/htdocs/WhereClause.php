<?php
/**
 * WhereClause.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class represents a WHERE clause in an SQL query. It is
 * intended for more complex clauses that can't be easily
 * created from key-value pairs.
 *
 * WhereClause objects can be used as argumets to some of the
 * methods in order to create nested expressions.
 */

class WhereClause
{
    public function __construct()
    {
    }

    public function vEquals($varFieldSpec, $varFieldValue)
    {
    }

    public function vAnd(WhereClause $whr)
    {
    }

    public function sqlRender()
    {
    }
}
