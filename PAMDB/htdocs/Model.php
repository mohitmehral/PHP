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

require_once 'Helper.php';
#require_once 'View.php';

require_once 'DB.php';
require_once 'Select.php';

require_once 'Ghg.php';
require_once 'Implementor.php';
require_once 'MemberState.php';
require_once 'SideEffect.php';
require_once 'Ccpm.php';
require_once 'Sector.php';
require_once 'Status.php';
require_once 'Type.php';
require_once 'Scenario.php';

class Model
{
    const PAM_UNCLUSTERED = 0;
    const PAM_IS_CLUSTER = 1;
    const PAM_BELONGS_TO_CLUSTER = 2;

    public static function rgGetMemberStates()
    {
        $sql = Select::sqlSimpleQuery('val_member_state', array('id_member_state', 'member_state'));
        return DB::rgSelectRows($sql);
    }

    public static function rgGetSectors()
    {
        $sql = Select::sqlSimpleQuery('val_sector', array('id_sector', 'sector'));
        return DB::rgSelectRows($sql);
    }

    public static function rgGetTypes()
    {
        $sql = Select::sqlSimpleQuery('val_type', array('id_type', 'type'));
        return DB::rgSelectRows($sql);
    }

    public static function rgGetGhgs()
    {
        $sql = Select::sqlSimpleQuery('val_ghg', array('id_ghg', 'ghg_output'));
        return DB::rgSelectRows($sql);
    }

    public static function rgGetStatusList()
    {
        $sql = Select::sqlSimpleQuery('val_status', array('id_status', 'status'));
        return DB::rgSelectRows($sql);
    }

    public static function rgGetScenarios()
    {
        $sql = Select::sqlSimpleQuery('val_with_or_with_additional_measure',
                                      array('id_with_or_with_additional_measure',
                                      'with_or_with_additional_measure'));
        return DB::rgSelectRows($sql);
    }

    public static function rgGetEu15StateIds()
    {
        return self::_rgGetStateIdsByGroup('eu_15');
    }

    public static function rgGetEu10StateIds()
    {
        return self::_rgGetStateIdsByGroup('eu_10');
    }

    private static function _rgGetStateIdsByGroup($sGroup)
    {
        $sql = Select::sqlSimpleQuery('val_member_state', array('id_member_state'), array($sGroup=>'1'));
        return DB::rgSelectCol($sql);
    }

    public static function mpGetPamById($ix, $rgFields = null)
    {
        $sql = Select::sqlSimpleQuery('pam', $rgFields, array('id'=>$ix));
        $mpPam = DB::mpSelectRow($sql);
        if (null == $mpPam && is_array($rgFields)) {
            foreach ($rgFields as $sT) {
                $mpPam[$sT] = 'N/A';
            }
        }

        return $mpPam;
    }

    public static function mpGetPamSummaryById($ix)
    {
        $mpPam = self::_mpGetPamInfoById($ix, self::rgGetPamListFields(), array('Ghg',
                                                                                'MemberState',
                                                                                'Ccpm',
                                                                                'Sector',
                                                                                'Status',
                                                                                'Type',
                                                                                'Scenario'));
        
        if ($mpPam['coCluster'] == self::PAM_BELONGS_TO_CLUSTER) {
            self::_vInsertClusterEstimates($mpPam);
        }
        
        return $mpPam;
    }

    public static function mpGetPamDetailsById($ix)
    {
        $mpPam = self::_mpGetPamInfoById($ix, self::rgGetPamDetailFields(), array('Ghg',
                                                                                  'Implementor',
                                                                                  'MemberState',
                                                                                  'SideEffect',
                                                                                  'Ccpm',
                                                                                  'Sector',
                                                                                  'Status',
                                                                                  'Type',
                                                                                  'Scenario'));
        
        switch ($mpPam['coCluster']) {
            case self::PAM_UNCLUSTERED:
                break;
            case self::PAM_IS_CLUSTER:
                $mpPam['rgCluster'] = self::_rgGetPamsInCluster($mpPam);
                break;
            case self::PAM_BELONGS_TO_CLUSTER:
                $mpPam['rgCluster'] = self::_rgGetPamsInCluster($mpPam);
                self::_vInsertClusterEstimates($mpPam);
                break;
        }
        
        return $mpPam;
    }

    private static function _mpGetPamInfoById($ix, $rgPamFields, $rgDimensions)
    {
        $q = new Select('pam', $rgPamFields);

        $q->vSetFilter(array('id'=>$ix));
        
        foreach ($rgDimensions as $sDim) {
            self::_vStandardJoin($q, $sDim);
        }
        
        $q->vOrderBy(array(BaseModel::sTblName(Ccpm::_ID), 'id_sector'));
        $q->vOrderBy(array(BaseModel::sTblName(Ccpm::_ID), 'related_ccpm'));
        
        $sql = $q->sqlRender();
        #View::vRenderInfoBox($sql);
        $rgRows = DB::rgSelectRows($sql);
        $mpPam = Helper::mpUniqCols($rgRows);
        
        $mpPam['coCluster'] = self::_coGetClusterStatus($mpPam);
        $mpPam['fClustered'] = $mpPam['coCluster'] == self::PAM_BELONGS_TO_CLUSTER;

        return $mpPam;
    }

    public static function mpGetCluster($sId)
    {
        $sql = Select::sqlSimpleQuery('pam', null, array('pam_identifier'=>$sId));
        return DB::mpSelectRow($sql);
    }

    private static function _coGetClusterStatus($mpPam)
    {
        if (empty($mpPam['cluster'])) {
            return self::PAM_UNCLUSTERED;
        } else if ('CL-' == substr($mpPam['pam_identifier'], 0, 3)) {
            return self::PAM_IS_CLUSTER;
        } else {
            return self::PAM_BELONGS_TO_CLUSTER;
        }
    }

    private static function _rgGetPamsInCluster($mpPam)
    {
        $sId = $mpPam['cluster'];
        $sql = Select::sqlSimpleQuery('pam', array('id', 'pam_identifier'), array('cluster'=>$sId));
        $rg = DB::rgSelectRows($sql);
        for ($c = 0; $c < count($rg); $c++) {
            if ($rg[$c]['pam_identifier'] == $sId) {
                unset($rg[$c]);
            }
        }

        return array_values($rg);
    }

    private static function _vInsertClusterEstimates(&$mpPam)
    {
        $mpCluster = self::mpGetCluster($mpPam['cluster']);
        foreach (array('2005', '2010', '2015', '2020') as $y) {
            foreach (array('val', 'text') as $sSuffix) {
                $sField = 'red_'.$y.'_'.$sSuffix;
                $mpPam[$sField] = $mpCluster[$sField];
            }
        }
    }

    private static function _vStandardJoin(&$q, $sClass, $rgTargetFields = null)
    {
        if (!class_exists($sClass) || !is_subclass_of($sClass, 'BaseModel')) {
            throw new Exception("Undefined entity class referenced");
        }
        $q->vAddMappedJoin('id',
                           call_user_func(array($sClass, 'map')),
                           call_user_func(array($sClass, 'rgIdColSpec')),
                           $rgTargetFields);
    }

    /**
     * This functions returns the list of fields originally used in the list view.
     * Just retrieving all fields should work equally well and yields simpler code.
     *
     * TODO: Remove this function if no longer needed.
     */

    public static function rgGetPamListFields()
    {
        return array(
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
    }

    /**
     * This functions returns the list of fields originally used in the detail view.
     * Just retrieving all fields should work equally well and yields simpler code.
     *
     * TODO: Remove this function if no longer needed.
     */

    public static function rgGetPamDetailFields()
    {
        return array(
                     'id',
                     'pam_identifier',
                     'cluster',
                     'pam_no',
                     'name_pam',
                     'objective_of_measure',
                     'description_pam',
                     'start',
                     'ende',
                     'red_2005_val',
                     'red_2005_text',
                     'red_2010_val',
                     'red_2010_text',
                     'red_2015_val',
                     'red_2015_text',
                     'red_2020_val',
                     'red_2020_text',
                     'cumulative_2008_2012',
                     'explanation_basis_of_mitigation_estimates',
                     'factors_resulting_in_emission_reduction',
                     'include_common_reduction',
                     'documention_source',
                     'indicator_monitor_implementation',
                     'general_comment',
                     'reference',
                     'description_impact_on_non_ghg',
                     'costs_per_tonne',
                     'costs_per_year',
                     'costs_description',
                     'costs_documention_source',
                     'remarks'
                    );
    }

    public static function rgGetPamTextFields()
    {
        return array(
                     'name_pam',
                     'objective_of_measure',
                     'description_pam',
                     'explanation_basis_of_mitigation_estimates',
                     'factors_resulting_in_emission_reduction',
                     'documention_source',
                     'indicator_monitor_implementation',
                     'general_comment',
                     'reference',
                     'description_impact_on_non_ghg',
                     'costs_description',
                     'costs_documention_source'
                    );
    }
}
