<?php
/**
 * View.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-25
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to produce recurring HTML snippets with parametrized picture functions.
 */

require_once 'Helper.php';
require_once 'HtmlPicture.php';
require_once 'DetailSection.php';

class View
{
    public static function vRenderSearchResults($rgData)
    {
        HtmlPicture::PicPageHeadline('Search Results');
        if (count($rgData) > 0) {
            HtmlPicture::vStartBuffer();
            foreach ($rgData as $ix=>$mpData) {
                self::_vReformatEstimates($mpData);
                foreach ($mpData as $s=>$var) {
                    $mpData[$s] = self::_htmlFormatDetailVal($var);
                }
                HtmlPicture::PicResultsRow($mpData, $ix);
            }
            $htmlRows = HtmlPicture::htmlFlushBuffer();
            HtmlPicture::PicResultsTable($htmlRows);
        } else {
            HtmlPicture::PicNoResultsMsg();
        }
    }

    public static function vRenderDetailView($mpData)
    {
        self::_vReformatEstimates($mpData);
        self::_vReformatImplementors($mpData);

        $sHeadline = 'Detailed Results';
        if (!empty($mpData['name_pam'])) {
            $sHeadline .= ' for '.$mpData['name_pam'];
        }
        HtmlPicture::PicPageHeadline(Helper::htmlSanitize($sHeadline));

        $rgSections = array(
                            DetailSection::secMain(),
                            DetailSection::secEstimates(),
                            DetailSection::secDocumentation(),
                            DetailSection::secSideEffects(),
                            DetailSection::secCosts()
                           );

        foreach ($rgSections as $sec) {
            $sSubHead = $sec->sGetTitle();
            if (!empty($sSubHead)) {
                $htmlHead = HtmlPicture::htmlCapture('PicDetailSectionHeader', array(Helper::htmlSanitize($sSubHead)));
            } else {
                $htmlHead = '';
            }
            HtmlPicture::vStartBuffer();
            foreach ($sec->rgGetTranslatableFields() as $sField) {
                $sLabel = $sec->sGetLabel($sField);
                HtmlPicture::PicDetailRow(Helper::htmlSanitize($sLabel), self::_htmlFormatDetailVal($mpData[$sField]));
            }
            $htmlRows = HtmlPicture::htmlFlushBuffer();
            HtmlPicture::PicDetailSection($htmlHead, $htmlRows);
        }
    }

    public static function vRenderCheckboxList($rgData, $sTitle, $sValueField,
                                               $fEmptyOption = false, $sIdField = null)
    {
        if (empty($sIdField)) {
            $sIdField = 'id_'.$sValueField;
        }
        $htmlName = Helper::htmlSanitize($sIdField).'[]';
        try {
            HtmlPicture::vStartBuffer();
            HtmlPicture::PicFilterWidgetItem($htmlName, 'select_all', 'Select all');
            if ($fEmptyOption) {
                HtmlPicture::PicFilterWidgetItem($htmlName, 'no_value', 'Include empty values');
            }
            $htmlWidget = HtmlPicture::htmlFlushBuffer();
            HtmlPicture::vStartBuffer();
            foreach ($rgData as $mp) {
                $htmlId = Helper::htmlSanitize($sIdField.$mp[$sIdField]);
                $htmlValue = Helper::htmlSanitize($mp[$sIdField]);
                $htmlLabel = Helper::htmlSanitize($mp[$sValueField]);
                HtmlPicture::PicFilterListItem($htmlId, $htmlName, $htmlValue, $htmlLabel);
            }
            $htmlList = HtmlPicture::htmlFlushBuffer();
            HtmlPicture::PicFilterConfig(Helper::htmlSanitize($sTitle), $htmlWidget, $htmlList);
        } catch (Exception $e) {
            Helper::vSendCrashReport($e);
            HtmlPicture::PicErrorBox(Helper::htmlSanitize($e->getMessage()));
        }
    }

    public static function vRenderErrorMsg(Exception $e)
    {
        HtmlPicture::PicErrorBox(Helper::htmlSanitize($e->getMessage()));
    }

    public static function vRenderInfoBox($s)
    {
        HtmlPicture::PicInfoBox(Helper::htmlSanitize($s));
    }

    private static function _vReformatEstimates(&$mpData)
    {
        foreach (array('2005', '2010', '2015', '2020') as $y) {
            $sValField = 'red_'.$y.'_val';
            $sTextField = 'red_'.$y.'_text';
            if (!array_key_exists($sValField, $mpData)) {
                continue;
            }
            if ($mpData['fClustered']) {
                $mpData[$sTextField] = $mpData[$sValField] = 'see cluster '.Helper::htmlSanitize($mpData['cluster']);
                return;
            }
            $sVal = trim($mpData[$sValField]);
            $sText = trim($mpData[$sTextField]);
            if (empty($sText) && empty($sVal)) {
                $mpData[$sTextField] = 'no estimate provided';
            } else if (empty($sVal)) {
                $mpData[$sTextField] = $sText;
            } else {
                $mpData[$sTextField] = number_format((double)$sVal, 0, '.', ',');
                if (!empty($sText)) {
                    $mpData[$sTextField] .= '<br>'.$sText;
                }
            }
            $mpData[$sValField] = $mpData[$sTextField];
        }
    }

    private static function _vReformatImplementors(&$mpData)
    {
        $sKey = 'implementing_entity';
        if (!empty($mpData[$sKey])) {
            if (is_array($mpData[$sKey])) {
                foreach ($mpData[$sKey] as $ix=>$s) {
                    $mpData[$sKey][$ix] .= ' ('.$mpData['specification'][$ix].')';
                }
            } else {
                $mpData[$sKey] .= ' ('.$mpData['specification'].')';
            }
        }
    }

    private static function _htmlFormatDetailVal($var)
    {
        if (is_array($var)) {
            foreach ($var as $ix=>$s) {
                $var[$ix] = Helper::htmlSanitize($s);
            }
            return join('<br>', $var);
        } else {
            return Helper::htmlSanitize($var);
        }
    }
}
