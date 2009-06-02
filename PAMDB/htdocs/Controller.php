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

    public static function ixPamFromRequest()
    {
        if (empty($_GET['id'])) {
            return 0;
        } else {
            return (int)sprintf('%d', $_GET['id']);
        }
    }

    public static function fnGetSortFunc()
    {
        if (empty($_GET['sort'])) {
            return null;
        } else {
            $php = 'foreach (array(1, 2) as $ix) {
                        $var = ${\'mp\'.$ix}[\'%s\'];
                        if (is_array($var)) {
                           sort($var, SORT_STRING);
                           ${\'s\'.$ix} = reset($var);
                        } else {
                           ${\'s\'.$ix} = (string)$var;
                        }
                    }
                    return strcmp($s1, $s2);';
            return create_function('$mp1, $mp2', sprintf($php, addcslashes($_GET['sort'], '\'')));
        }
    }
}
?>
