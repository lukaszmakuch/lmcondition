<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

/**
 * Fake condition with optional string id (used in _toString).
 * 
 * Doesn't take context into account.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FakeCondition implements \lukaszmakuch\LmCondition\Condition
{
    protected $stringId;
    
    public function __construct($stringId = '')
    {
        if (empty($stringId)) {
            $stringId = uniqid();
        }
        
        $this->stringId = $stringId;
    }
    
    public function isNegated()
    {
        return false;
    }

    public function isTrueIn(\lukaszmakuch\LmCondition\Context $context)
    {
        return false;
    }

    public function negate()
    {
        return;
    }

    public function removeNegation()
    {
        return;
    }
    
    public function __toString()
    {
        return $this->stringId;
    }

}
