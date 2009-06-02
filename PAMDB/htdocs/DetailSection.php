<?php
/**
 * DetailSection.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-29
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class that represents a section in the detail view
 */

class DetailSection
{
    private $_sTitle = '';

    private $_mpRowLabels = array();

    private function __construct($sTitle, $mpLabels)
    {
        $this->_sTitle = $sTitle;
        $this->_mpRowLabels = $mpLabels;
    }

    public function sGetTitle()
    {
        return $this->_sTitle;
    }

    public function sGetLabel($sField)
    {
        if (array_key_exists($sField, $this->_mpRowLabels)) {
            return $this->_mpRowLabels[$sField];
        } else {
            return null;
        }
    }

    public function rgGetTranslatableFields()
    {
        return array_keys($this->_mpRowLabels);
    }

    public static function secMain()
    {
        $mp = array();

        $mp['name_pam'] = 'Name of policy or measure (or group)';
        $mp['pam_identifier'] = 'Internal PaM identifier';
        $mp['pam_no'] = 'PaM-No';
        $mp['member_state'] = 'Member State';
        $mp['with_or_with_additional_measure'] = 'With or with additional measure';
        $mp['objective_of_measure'] = 'Objective of measure(s)';
        $mp['description_pam'] = 'Description of policy or measure';
        $mp['sector'] = 'Sector(s) targeted';
        $mp['ghg_output'] = 'GHG affected';
        $mp['type'] = 'Type of instruments';
        $mp['status'] = 'Status of policy, measure or group';
        $mp['start'] = 'Start year of implementation';
        $mp['ende'] = 'End year of implementation';
        $mp['implementing_entity'] = 'Implementing entity or entities';

        return new self('', $mp);
    }

    public static function secEstimates()
    {
        $mp = array();

        $mp['red_2005_text'] = '2005';
        $mp['red_2010_text'] = '2010';
        $mp['red_2015_text'] = '2015';
        $mp['red_2020_text'] = '2020';
        $mp['cumulative_2008_2012'] = 'Cumulative emission reduction 2008- 2012 (Gg CO2eq)';

        return new self('Estimate of GHG emission reduction effect or sequestration effect in Gg CO<sub>2</sub> eq per year', $mp);
    }

    public static function secDocumentation()
    {
        $mp = array();

        $mp['explanation_basis_of_mitigation_estimates'] = 'Explanation of the basis for  the mitigation estimates';
        $mp['factors_resulting_in_emission_reduction'] = 'Factors resulting in emission reduction ';
        $mp['include_common_reduction'] = 'Does the estimate include reductions related to common and coordinated policies and measures?';
        $mp['documention_source'] = 'Documentation/ Source of estimation if available';
        $mp['indicator_monitor_implementation'] = 'Indicators used to monitor progress of implementation  ';
        $mp['related_ccpm'] = 'Common and coordinated policy and measure (CCPM)';
        $mp['general_comment'] = 'General Comment';
        $mp['reference'] = 'Reference';

        return new self('Explanation of emission reduction estimate', $mp);
    }

    public static function secSideEffects()
    {
        $mp = array();

        $mp['reduces_non_ghg'] = 'Reduces non Greenhouse Gas Emissions';
        $mp['description_impact_on_non_ghg'] = 'Description of Impact';

        return new self('Description of mitigation impact of measure on Non-Greenhouse Gases (e.g. Sox, NOx, NMVOC, Particulates)', $mp);
    }

    public static function secCosts()
    {
        $mp = array();

        $mp['costs_per_tonne'] = 'Costs in EURO per tonne CO2eq reduced/ sequestered';
        $mp['costs_per_year'] = 'Absolute costs per year in EURO';
        $mp['costs_description'] = 'Description of cost estimates';
        $mp['costs_documention_source'] = 'Documentation/ Source of cost estimation';			

        return new self('Information on costs', $mp);
    }
}

?>
