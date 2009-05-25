<?php
/**
 * Model.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-25
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to provide the dynamic data used on the pages.
 */

require_once 'DB.php';
require_once 'Select.php';

class Model
{
    public static function rgGetSectors()
    {
        $sql = Select::sqlSimpleQuery('val_sector', array('id_sector', 'sector'));
        return DB::rgSelectRows($sql);
    }

    public static function mpGetPamById($ix)
    {
        $rgFields = array(
                          'id',
                          'pam_identifier',
                          'cluster',
                          'name_pam',
                          'red_2005_val',
                          'red_2005_text',
                          'red_2010_val',
                          'red_2010_text',
                          'red_2020_val',
                          'red_2020_text',
                          'costs_per_tonne'
                         );
        $sql = Select::sqlSimpleQuery('pam', $rgFields, array('id'=>$ix));
        $mpPam = DB::mpSelectRow($sql);
        if (null == $mpPam) {
            foreach ($rgFields as $sT) {
                $mpPam[$sT] = 'N/A';
            }
        }

        return $mpPam;
    }
}
