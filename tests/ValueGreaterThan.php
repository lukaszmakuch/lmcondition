<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

use lukaszmakuch\LmCondition\ConditionAbstract;
use lukaszmakuch\LmCondition\Context;

/**
 * Created for testing purposes.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ValueGreaterThan extends ConditionAbstract
{
    protected $mustBeGreaterThan;
    
    public function __construct($mustBeGreaterThan)
    {
        $this->mustBeGreaterThan = $mustBeGreaterThan;
    }
    
    protected function isTrueInImpl(Context $context)
    {
        return $this->isTrueInImplActual($context);
    }
    
    protected function isTrueInImplActual(KeyValueContext $context)
    {
        return $context->get("value") > $this->mustBeGreaterThan;
    }
}
