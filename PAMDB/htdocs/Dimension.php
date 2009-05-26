<?php
/**
 * Dimension.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class represents a dimension table and is intended to be used
 * for the construction of star queries.
 */

class Dimension
{
    private $_sTable = null;

    private $_sJoinCol = null;

    private $_sSearchCol = null;

    private $_varSearchVal = null;

    private $_fNullsAllowed = false;

    private $_fFiltered = false;

    public function __construct($sTable, $sCol)
    {
        $this->_sTable = $sTable;
        $this->_sJoinCol = $sCol;
    }

    public function vSetFilter($sCol, $varValue)
    {
        $this->_sSearchCol = $sCol;
        $this->_varSearchVal = $varValue;
        $this->_fFiltered = true;
    }

    public function sGetTable()
    {
        return $this->_sTable;
    }

    public function sGetJoinColumn()
    {
        return $this->_sJoinCol;
    }

    public function sGetSearchField()
    {
        return $this->_sSearchCol;
    }

    public function varGetSearchValue()
    {
        return $this->_varSearchVal;
    }

    public function fIsNullAllowed()
    {
        return $this->_fNullsAllowed;
    }

    public function fIsFiltered()
    {
        return $this->_fFiltered;
    }

    public function vAllowNull()
    {
        $this->_fNullsAllowed = true;
    }

    public function vDisallowNull()
    {
        $this->_fNullsAllowed = false;
    }
}

?>
