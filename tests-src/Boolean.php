<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

/**
 * May be true or not.
 * 
 * Doesn't take context into account.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Boolean extends \lukaszmakuch\LmCondition\ConditionAbstract
{
    protected $isTrue;
    
    /**
     * @param boolean $isTrue
     */
    public function __construct($isTrue)
    {
        $this->isTrue = $isTrue;
    }
    
    protected function isTrueInImpl(\lukaszmakuch\LmCondition\Context $context)
    {
        return $this->isTrue;
    }
}
