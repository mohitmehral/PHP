<?php
/**
 * Helper.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-25
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to accomodate various auxiliary functions
 */

class Helper
{
    const MAIL_MAINTAINER = 'eea-pam@econemon.com';
    const SMTP_SERVER = "econemon.com";

    private static $_mpProtect = array('sub'=>'vvv', 'sup'=>'qqq');

    public static function vSendCrashReport(Exception $e)
    {
        ini_set("SMTP", self::SMTP_SERVER);
        ini_set("sendmail_path", "");
        ini_set('sendmail_from', '"PaM installation at '.$_SERVER['SERVER_NAME'].'" <noreply@example.com>');

        $sSubject = 'Automated crash report: '.$e->getMessage();

        @mail(self::MAIL_MAINTAINER, $sSubject, self::sFormatTrace($e));
    }

    public static function sFormatTrace(Exception $e)
    {
        $sMsg = "";
        foreach($e->getTrace() as $ix=>$mp) {
            $sMsg .= "#$ix - ".$mp['class'].$mp['type'].$mp['function'].'('.join(', ', $mp['args']).')'."\n";
        }

        return $sMsg;
    }

    public static function htmlSanitize($s)
    {
        $s = self::_sProtectTags($s);
        $html = self::_htmlEscape($s);
        $html = self::_htmlUnprotectTags($html);

        return $html;
    }

    public static function mpUniqCols($rg)
    {
        if (!is_array($rg) || count($rg) < 1) {
            return array();
        } else {
            $rgKeys = array_keys(reset($rg));
            $mp = array();
            foreach ($rg as $mpT) {
                foreach ($rgKeys as $sKey) {
                    $mp[$sKey][] = $mpT[$sKey];
                }
            }
            foreach ($rgKeys as $sKey) {
                $mp[$sKey] = array_unique($mp[$sKey]);
                if (count($mp[$sKey]) == 1) {
                    $mp[$sKey] = $mp[$sKey][0];
                }
            }
            return $mp;
        }
    }
    
    private static function _htmlEscape($s)
    {
        $s = str_replace('&', '&amp;', $s);
        $s = str_replace('<', '&lt;', $s);
        $s = str_replace('>', '&gt;', $s);
        $s = str_replace('"', '&quot;', $s);

        return $s;
    }

    private static function _sProtectTags($s)
    {
        foreach (self::$_mpProtect as $sTag=>$sReplace) {
            $s = str_replace('<'.$sTag.'>', self::_sOpenTok($sReplace), $s);
            $s = str_replace('</'.$sTag.'>', self::_sCloseTok($sReplace), $s);
        }

        return $s;
    }

    private static function _htmlUnprotectTags($html)
    {
        foreach (self::$_mpProtect as $sTag=>$sReplace) {
            $html = str_replace(self::_sOpenTok($sReplace), '<'.$sTag.'>', $html);
            $html = str_replace(self::_sCloseTok($sReplace), '</'.$sTag.'>', $html);
        }

        return $html;
    }

    private static function _sOpenTok($s)
    {
        return ucfirst(strtolower($s));
    }

    private static function _sCloseTok($s)
    {
        return strtolower(substr($s, 0, -1)).strtoupper(substr($s, -1));
    }
}
