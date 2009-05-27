<?php
/**
 * DB.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-25
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * Helper class to centralize database access
 * The low-level API used will be MySQL specific.
 */

class DB
{
    // Singleton
    private static $_INSTANCE = null;

    // connection parameters
    private $_sHost = null;
    private $_sUser = null;
    private $_sPass = null;

    // selected db
    private $_sDb = null;

    // connection handle
    private $_flCon = null;

    // Singleton, can't instantiate directly
    private function __construct($sUser, $sPass, $sHost = '', $sDb = '')
    {
        $this->_sUser = $sUser;
        $this->_sPass = $sPass;
        $this->_sHost = $sHost;
        $this->_sDb = $sDb;
    }

    public static function vInit()
    {
        if (is_null(self::$_INSTANCE)) {
            if (false == @include_once('config.inc.php')) {
                throw new Exception("Configuration file is missing");
            }
            $db = new DB(DB_USER, DB_PASSWD, DB_HOST, DB_DATABASE);
            $db->_vConnect();
            self::$_INSTANCE = $db;
        }
    }

    public static function rgSelectRows($sql)
    {
        $dbr = self::_dbrExecute($sql);
        $rg = array();
        for ($c = 0, $cMax = mysql_num_rows($dbr); $c < $cMax; $c++) {
            $rg[$c] = mysql_fetch_array($dbr, MYSQL_ASSOC);
        }
        @mysql_free_result($dbr);
        return $rg;
    }

    public static function rgSelectCol($sql, $sCol = null)
    {
        $dbr = self::_dbrExecute($sql);
        $rg = array();
        for ($c = 0, $cMax = mysql_num_rows($dbr); $c < $cMax; $c++) {
            $mp = mysql_fetch_array($dbr, MYSQL_ASSOC);
            if (empty($sCol)) {
                $rg[$c] = reset($mp);
            } else if (array_key_exists($sCol, $mp)) {
                $rg[$c] = $mp[$sCol];
            } else {
                throw new Exception("The specified column is not present in the result");
            }
        }
        @mysql_free_result($dbr);
        return $rg;
    }

    public static function mpSelectRow($sql)
    {
        $dbr = self::_dbrExecute($sql);
        $c = mysql_num_rows($dbr);
        if (1 < $c) {
            throw new Exception("The query should never return more than one row");
        } else if ($c == 0) {
            return null;
        } else {
            $mp = mysql_fetch_array($dbr, MYSQL_ASSOC);
            @mysql_free_result($dbr);
            return $mp;
        }
    }

    private static function _dbrExecute($sql)
    {
        self::_vAssertConnection();
        $dbr = @mysql_query($sql, self::$_INSTANCE->_flCon);
        if (false == $dbr) {
            throw new Exception("A database error has occurred, could not retrieve data.");
        }
        return $dbr;
    }

    private function _vConnect()
    {
        $sHost = empty($this->_sHost) ? 'localhost' : $this->_sHost;
        // use persistent connection for better performance in web scripts
        $fl = @mysql_pconnect($sHost, $this->_sUser, $this->_sPass);
        if (false == $fl) {
            throw new Exception("Could not connect to database server.");
        }
        $this->_flCon = $fl;
        if (!empty($this->_sDb)) {
            if (false == @mysql_select_db($this->_sDb, $this->_flCon)) {
                throw new Exception("Connection to database server successful, but the requested database could not be used.");
            }
        }
    }

    private static function _vAssertConnection()
    {
        if (is_null(self::$_INSTANCE)) {
            throw new Exception("Database connection not initialized");
        }
    }

    public function __destruct()
    {
        if (is_resource($this->_flCon)) {
            // this will do nothing if the connection is persistent (as it should normally be)
            @mysql_close($this->_flCon);
        }
    }
}

?>
