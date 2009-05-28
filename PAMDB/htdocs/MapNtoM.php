<?php
/**
 * MapNtoM.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-27
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class represents a mapping table as used in
 * relational DB schemes to create n to m relations.
 *
 * Intances of this class are used by other classes
 * to communicate about a mapping in a standardized,
 * explicit way, rather than using opaque arrays.
 *
 * In such a mapping, we define a 'left' and a 'right'
 * side of the mapping. This is relevant if the mapping
 * is used with LEFT JOIN or RIGHT JOIN clauses.
 */

class MapNtoM
{
    private $_sTable = null;

    private $_sLftCol = null;

    private $_sRgtCol = null;

    private $_rgFields = array();

    public function __construct($sTable, $sLftCol = null, $sRgtCol = null)
    {
        $this->_sTable = $sTable;
        $this->_sLftCol = $sLftCol;
        $this->_sRgtCol = $sRgtCol;
    }

    public function vSelectMapFields($rgFields)
    {
        if (is_scalar($rgFields)) {
            $rgFields = array($rgFields);
        }
        $this->_rgFields = $rgFields;
    }

    public function vSetLft($sCol)
    {
        $this->_sLftCol = $sCol;
    }

    public function vSetRgt($sCol)
    {
        $this->_sRgtCol = $sCol;
    }

    public function sGetTable()
    {
        return $this->_sTable;
    }

    public function sGetLftCol()
    {
        return $this->_sLftCol;
    }

    public function sGetRgtCol()
    {
        return $this->_sRgtCol;
    }

    public function fMapFieldsSelected()
    {
        return is_array($this->_rgFields) && count($this->_rgFields) > 0;
    }

    public function rgGetFields()
    {
        return $this->_rgFields;
    }
}

?>
