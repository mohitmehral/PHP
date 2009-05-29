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

class View
{
    public static function vRenderCheckboxList($rgData, $sTitle, $sValueField,
                                               $fEmptyOption = false, $sIdField = null)
    {
        if (empty($sIdField)) {
            $sIdField = 'id_'.$sValueField;
        }
        $htmlName = htmlentities($sIdField).'[]';
        try {
            HtmlPicture::vStartBuffer();
            HtmlPicture::PicFilterWidgetItem($htmlName, 'select_all', 'Select all');
            if ($fEmptyOption) {
                HtmlPicture::PicFilterWidgetItem($htmlName, 'no_value', 'Include empty values');
            }
            $htmlWidget = HtmlPicture::htmlFlushBuffer();
            HtmlPicture::vStartBuffer();
            foreach ($rgData as $mp) {
                $htmlId = htmlentities($sIdField.$mp[$sIdField]);
                $htmlValue = htmlentities($mp[$sIdField]);
                $htmlLabel = htmlentities($mp[$sValueField]);
                HtmlPicture::PicFilterListItem($htmlId, $htmlName, $htmlValue, $htmlLabel);
            }
            $htmlList = HtmlPicture::htmlFlushBuffer();
            HtmlPicture::PicFilterConfig(htmlentities($sTitle), $htmlWidget, $htmlList);
        } catch (Exception $e) {
            Helper::vSendCrashReport($e);
            HtmlPicture::PicErrorBox(htmlentities($e->getMessage()));
        }
    }

    public static function vRenderErrorMsg(Exception $e)
    {
        HtmlPicture::PicErrorBox(htmlentities($e->getMessage()));
    }

    public static function vRenderInfoBox($s)
    {
        HtmlPicture::PicInfoBox(htmlentities($s));
    }
}
