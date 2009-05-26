<?php
/**
 * FromClause.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class represents a FROM clause in an SQL query and is used 
 * to construct more complex FROMs involving JOINs.
 */

class FromClause
{
    public function __construct($varPrimaryTableSpec)
    {
    }

    public function vLeftJoinOnEqual($varRightTableSpec, $varLeftColSpec, $varRightColSpec)
    {
    }

    public function sqlRender()
    {
    }
}
?>
