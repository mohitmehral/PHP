<?php
/**
 * FulltextMatch.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class generates a MATCH() AGAINST() clause.
 */

require_once 'SqlExpression.php';

class FulltextMatch extends SqlExpression
{
    const MOD_BOOL = 'BOOLEAN';
    const MOD_NATLANG = 'NATURAL LANGUAGE';

    private $_sExpr = '*';

    private $_rgFields = array();

    private $_coMode = self::MOD_BOOL;

    public function __construct($sExpr, $rgFields, $coMode = self::MOD_BOOL)
    {
        if (is_array($rgFields)) {
            $this->_rgFields = $rgFields;
        }
        $this->_sExpr = (string)$sExpr;
        switch ($coMode) {
            case self::MOD_BOOL:
            case self::MOD_NATLANG:
                $this->_coMode = $coMode;
                break;
            default:
                throw new Exception("Unsupported mode for fulltext search");
        }
    }

    public function sqlRender()
    {
        return 'MATCH ('.Sql::sqlFieldList($this->_rgFields).') AGAINST ('.Sql::sqlQuoteValue($this->_sExpr).' IN '.$this->_coMode.' MODE)';
    }
}

?>
