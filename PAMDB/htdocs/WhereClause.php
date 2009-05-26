<?php
/**
 * WhereClause.php
 *
 * Author: Hanno Fietz <hanno.fietz@econemon.com>
 *
 * Date: 2009-05-26
 *
 * (C) 2009 econemon UG - All rights reserved
 *
 * This class represents a WHERE clause in an SQL query. It is
 * intended for more complex clauses that can't be easily
 * created from key-value pairs.
 *
 * WhereClause objects can be used as argumets to some of the
 * methods in order to create nested expressions.
 */

require_once 'SqlExpression.php';

class WhereClause extends SqlExpression
{
    const _EQUAL = '=';
    const _NOT = '!';
    const _AND = '&';
    const _OR = '|';

    private $_coOp = null;

    private $_rgOps = array();

    public function __construct()
    {
    }

    public function vEquals($varFieldSpec, $varFieldValue)
    {
        $this->_vAssertOperator(null);
        $this->_coOp = self::_EQUAL;
        $this->_rgOps = array($varFieldSpec, $varFieldValue);
    }

    public function vNotNull($varFieldSpec)
    {
        $this->_vAssertOperator(null);
        $this->_coOp = self::_NOT;
        $this->_rgOps = array($varFieldSpec, null);
    }

    public function vAnd(SqlExpression $whr)
    {
        $this->_vAddOperand($whr, self::_AND);
    }

    public function vOr(SqlExpression $whr)
    {
        $this->_vAddOperand($whr, self::_OR);
    }

    private function _vAddOperand(SqlExpression $whr, $coOp)
    {
        $this->_vAssertOperator($coOp);
        $this->_coOp = $coOp;
        $this->_rgOps[] = $whr;
    }

    public function sqlRender($fStandalone = true)
    {
        switch ($this->_coOp) {
            case self::_EQUAL:
                $sql = $this->_sqlRenderEqual();
                break;
            case self::_NOT:
                $sql = $this->_sqlRenderEqual(true);
                break;
            case self::_AND:
                $sql = $this->_sqlRenderRecursive('AND');
                break;
            case self::_OR:
                $sql = $this->_sqlRenderRecursive('OR');
                break;
            default:
                return '';
        }
        
        if ($fStandalone) {
            return ' WHERE '.$sql;
        } else {
            return $sql;
        }
    }

    private function _sqlRenderEqual($fInvert = false)
    {
        if (count($this->_rgOps) != 2) {
            throw new Exception("Invalid number of operands");
        }

        if (is_scalar($this->_rgOps[0])) {
            $sql = Sql::sqlQuoteId($this->_rgOps[0]);
        } else if (is_array($this->_rgOps[0])) {
            $sql = Sql::sqlQualifiedCol($this->_rgOps[0]);
        } else {
            throw new Exception("Invalid operand type");
        }

        if (is_null($this->_rgOps[1])) {
            $sql .= ' IS '.($fInvert ? 'NOT ' : '').'NULL';
        } else if (is_array($this->_rgOps[1])) {
            $rg = array();
            foreach ($this->_rgOps[1] as $sOp) {
                $rg[] = Sql::sqlQuoteValue($sOp);
            }
            $sql .= ($fInvert ? 'NOT ' : '').' IN('.join(', ', $rg).')';
        } else {
            $sql .= ' '.($fInvert ? '!=' : '=').' ';
            $sql .= Sql::sqlQuoteValue($this->_rgOps[1]);
        }

        return $sql;
    }

    private function _sqlRenderRecursive($sGlue)
    {
        $rg = array();

        foreach ($this->_rgOps as $whr) {
            if (is_a($whr, 'SqlExpression')) {
                $rg[] = $whr->sqlRender(false);
            } else {
                throw new Exception("Encountered invalid operand");
            }
        }

        return '('.join(') '.$sGlue.' (', $rg).')';
    }

    private function _vAssertOperator($co, $fOrEmpty = true)
    {
        if ($co !== $this->_coOp && ($fOrEmpty == false || !is_null($this->_coOp))) {
            throw new Exception("Assertion failed: unexpected operator set");
        }
    }
}
