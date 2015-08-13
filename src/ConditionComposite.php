<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition;


class ConditionComposite extends ConditionAbstract
{
    protected $ANDConditions;
    protected $ORConditions;
    
    public function __construct()
    {
        $this->ANDConditions = [];
        $this->ORConditions = [];
    }
    
    protected function isTrueInImpl(Context $context)
    {
    }

    public function addAND(Condition $c)
    {
        $this->ANDConditions[] = $c;
    }
    
    public function addOR(Condition $c)
    {
        $this->ORConditions[] = $c;
    }
    
    /**
     * @return Condition[]
     */
    public function getANDConditions()
    {
        return $this->ANDConditions;
    }

    /**
     * @return Condition[]
     */
    public function getORConditions()
    {
        return $this->ORConditions;
    }
}
