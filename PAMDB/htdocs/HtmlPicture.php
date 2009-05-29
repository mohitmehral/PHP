<?php
/**
 * HtmlPicture.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-29
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to produce recurring HTML snippets
 * with parametrized picture functions.
 */

if (!ini_get('short_open_tag')) {
    phpinfo();
    die;
    trigger_error("PHP needs to be configured with \'short_open_tag\' turned on", E_USER_ERROR);
}

class HtmlPicture
{
    private static $_fBuffering = false;

    public static function PicFilterConfig($htmlTitle, $htmlWidget, $htmlList)
    {
    ?>
    <td class="filter">
        <label class="question"><?=$htmlTitle?></label><br/>
        <?=$htmlWidget?>
        <?=$htmlList?>
    </td>
    <?php
    }

    public static function PicFilterWidgetItem($htmlName, $htmlValue, $htmlLabel)
    {
    ?>
    <input type="checkbox" name="<?=$htmlName?>" value="<?=$htmlValue?>"/>
    <label class="specialval"><?=$htmlLabel?></label><br/>
    <?php
    }

    public static function PicFilterListItem($htmlId, $htmlName, $htmlValue, $htmlLabel)
    {
    ?>
    <input type="checkbox" id="<?=$htmlId?>" name="<?=$htmlName?>" value="<?=$htmlValue?>" />
    <label for="<?=$htmlId?>"><?=$htmlLabel?></label><br/>
    <?php
    }

    public static function PicDetailSection($htmlHeader, $htmlRows)
    {
    ?>
    <?=$htmlHeader?>
    <table class="datatable" style="width:95%">
    <col style="width: 25%"/>
    <col style="width: 75%"/>
    <?=$htmlRows?>
    </table>
    <?php
    }

    public static function PicDetailHeadline($htmlTitle)
    {
    ?>
    <h1><?=$htmlTitle?></h1>
    <?php
    }

    public static function PicDetailSectionHeader($htmlTitle)
    {
    ?>
    <h2><?=$htmlTitle?></h2>
    <?php
    }

    public static function PicDetailRow($htmlHeader, $htmlData)
    {
    ?>
	<tr><th scope="row" class="scope-row"><?=$htmlHeader?></th><td class="details"><?=$htmlData?></td></tr>
    <?php
    }

    public static function PicErrorBox($htmlErrMsg)
    {
    ?>
    <div class="error">
        <h1>An error has occured</h1>
        <p>
            We are very sorry, but it seems that something has gone wrong.
            Technical information about the problem has been sent to the
            site's maintainer.
        </p>
        <p>
            Additionally, we would greatly appreciate if you could send a
            short summary of what you were attempting to do to eea-pam@econemon.com.
        </p>
        <p>
            On doing so, please refer to the following error message:
        </p>
        <p>
            <strong><em><?=$htmlErrMsg?></em></strong>
        </p>
    </div>
    <?php
    }

    public static function PicInfoBox($htmlContent)
    {
    ?>
        <div class="info"><?=$htmlContent?></div>
    <?php
    }

    public static function vStartBuffer()
    {
        if (!self::$_fBuffering) {
            self::$_fBuffering = true;
            ob_start();
        }
    }

    public static function htmlFlushBuffer()
    {
        if (self::$_fBuffering) {
            $html = ob_get_contents();
            ob_end_clean();
            self::$_fBuffering = false;
            return $html;
        } else {
            return '';
        }
    }

    public static function htmlCapture($sFunction, $rgArgs)
    {
        if (!method_exists(__CLASS__, $sFunction)) {
            throw new Exception("Undefined function");
        }
        self::vStartBuffer();
        call_user_func_array(array(__CLASS__, $sFunction), $rgArgs);
        return self::htmlFlushBuffer();
    }
}
?>
