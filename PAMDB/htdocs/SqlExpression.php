<?php
/**
 * SqlExpression.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class is an abstarct base type for other classes like WhereClause
 * and takes the role of an interface in Java.
 */

abstract class SqlExpression
{
    public abstract function sqlRender();
}
?>
