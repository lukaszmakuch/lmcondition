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
    
    public function isTrueIn(Context $context)
    {
    }
    
    public function isNegated()
    {
        return $this->isNegated;
    }

    public function negate()
    {
    }

    public function removeNegation()
    {
    }

    protected abstract function isTrueInImpl(Context $context);
}