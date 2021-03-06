<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition;

/**
 * Implements all logic that responsible for negating conditions.
 * 
 * All what's needed to be done in order to implement a condition that 
 * may be negated is overriding the {@see ConditionAbstract::isTrueInImpl()} method.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class ConditionAbstract implements Condition
{
    /**
     * @var boolean
     */
    protected $isNegated = false;
    
    /**
     * @see ConditionAbstract::isTrueInImpl() actual checking strategy
     */
    public function isTrueIn(Context $context)
    {
        $notNegatedResult = $this->isTrueInImpl($context);
        if ($this->isNegated) {
            return !$notNegatedResult;
        } else {
            return $notNegatedResult;
        }
    }
    
    public function isNegated()
    {
        return $this->isNegated;
    }

    public function negate()
    {
        $this->isNegated = true;
        return $this;
    }

    public function removeNegation()
    {
        $this->isNegated = false;
        return $this;
    }

    /**
     * Actual strategy of checking whether this condition is true or not.
     * 
     * This method must always return result like this condition were NOT negated.
     * Negation is supported by {@see \lukaszmakuch\LmCondition\ConditionAbstract::isTrueIn()} method.
     */
    protected abstract function isTrueInImpl(Context $context);
}
