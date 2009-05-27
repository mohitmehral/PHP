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
require_once 'Model.php';

class View
{
    public static function vRenderCheckboxList($htmlTitle, $htmlVarName, $sIdField, $sValueField, $fnGetter, $fEmptyOption = false)
    {
    ?>
    <td class="filter">
        <label class="question"><?=$htmlTitle?></label><br/>
        <input type="checkbox" name="<?=$htmlVarName?>[]" value="select_all"/><label class="specialval">Select all</label><br/>
        <?php
        if ($fEmptyOption) {
        ?>
        <input type="checkbox" name="<?=$htmlVarName?>[]" value="no_value"/><label class="specialval">no value</label><br/>
        <?php
        }
        try {
            foreach (@call_user_func(array('Model', $fnGetter)) as $mp) {
            ?>
            <input type="checkbox" 
                   id="<?=$htmlVarName?><?=$mp[$sIdField]?>"
                   name="<?=$htmlVarName?>[]"
                   value="<?=$mp[$sIdField]?>" />
            <label for="<?=$htmlVarName?><?=$mp[$sIdField]?>"><?=$mp[$sValueField]?></label><br/>
            <?php
            }
        } catch (Exception $e) {
        Helper::vSendCrashReport($e);
        ?>
        <div class="error"><?=$e->getMessage()?></div>
        <?php
        }
        ?>
    </td>
    <?php
    }

    public static function vRenderErrorMsg(Exception $e)
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
            <strong><em><?=$e->getMessage()?></em></strong>
        </p>
    </div>
    <?php
    }

    public static function vRenderInfoBox($s)
    {
    ?>
        <div class="info"><?=htmlentities($s)?></div>
    <?php
    }
}
