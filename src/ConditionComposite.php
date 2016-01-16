<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition;

use lukaszmakuch\LmCondition\Exception\ImpossibleToCheckCondition;

/**
 * Allows to group conditions with AND/OR operators.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * 
 */
class ConditionComposite extends ConditionAbstract
{
    /**
     * @var Condition[]
     */
    protected $ANDConditions;
    
    /**
     * @var Condition[]
     */
    protected $ORConditions;
    
    public function __construct()
    {
        $this->ANDConditions = [];
        $this->ORConditions = [];
    }

    /**
     * Adds AND condition.
     * 
     * @param Condition $c
     * @return ConditionComposite self
     */
    public function addAND(Condition $c)
    {
        $this->ANDConditions[] = $c;
        return $this;
    }
    
    /**
     * Adds OR condition.
     * 
     * @param Condition $c
     * @return ConditionComposite self
     */
    public function addOR(Condition $c)
    {
        $this->ORConditions[] = $c;
        return $this;
    }
    
    /**
     * Gets all previosuly added AND condtions.
     * 
     * @return Condition[]
     */
    public function getANDConditions()
    {
        return $this->ANDConditions;
    }

    /**
     * Gets all previously added OR conditions.
     * 
     * @return Condition[]
     */
    public function getORConditions()
    {
        return $this->ORConditions;
    }
    
    /**
     * Processes all previously added AND/OR conditions.
     * 
     * @return boolean
     */
    protected function isTrueInImpl(Context $context)
    {
        $this->throwExceptionIfEmpty();
        
        if ($this->anyORConditionIsTrueIn($context)) {
            return true;
        }
        
        if (empty($this->getANDConditions())) {
            return false;
        }
        
        return $this->allANDConditionsAreTrueIn($context);
    }
    
    /**
     * @throws ImpossibleToCheckCondition if no AND/OR conditions have been added.
     */
    protected function throwExceptionIfEmpty()
    {
        if ($this->isEmpty()) {
            throw new ImpossibleToCheckCondition(
                "Trying to check an empty condition composite"
            );
        }
    }
    
    /**
     * Checks whether no AND/OR conditions have been added.
     * 
     * @return boolean
     */
    protected function isEmpty()
    {
        return empty($this->getORConditions()) && empty($this->getANDConditions());
    }
    
    /**
     * Checks whether at least one OR condition is met in that context.
     * 
     * @param Context $context
     * 
     * @return boolean
     */
    protected function anyORConditionIsTrueIn(Context $context)
    {
        foreach ($this->getORConditions() as $ORCondition) {
            if (true === $ORCondition->isTrueIn($context)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Checks whether all AND conditions are met in this context.
     * 
     * @param Context $context
     * 
     * @return boolean
     */
    protected function allANDConditionsAreTrueIn(Context $context)
    {
        foreach ($this->getANDConditions() as $singleANDCondition) {
            if (false === $singleANDCondition->isTrueIn($context)) {
                return false;
            }
        }
        
        return true;
    }
}
