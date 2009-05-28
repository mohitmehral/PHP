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
}
