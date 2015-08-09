<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition;

/**
 * Description of ConditionAbstract
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
    }

    public function removeNegation()
    {
        $this->isNegated = false;
    }

    protected abstract function isTrueInImpl(Context $context);
}
