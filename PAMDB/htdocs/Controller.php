<?php
/**
 * Controller.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class is used to hold rules and methods for the business logic
 * that the scripts can use to moderate between database access and HTML
 * output.
 */

require_once 'Dimension.php';

class Controller
{
    public static function rgFilterFromRequest($sName)
    {
        if (!empty($_GET[$sName])) {
            if (!is_array($_GET[$sName])) {
                $rgFilter = array($_GET[$sName]);
            } else {
                $rgFilter = array_values($_GET[$sName]);
            }
            return $rgFilter;
        } else {
            return null;
        }
    }
}
?>
