<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\IntersectDetector;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\LmCondition\Condition;


class IntersectDetectorProxy implements IntersectDetector
{
    protected $proxyToCondClassesReg;

    public function __construct(ClassBasedRegistry $r)
    {
        $this->proxyToCondClassesReg = $r;
    }
    
    public function register(IntersectDetector $detector, $condClass1, $condClass2)
    {
        return $this->proxyToCondClassesReg->associateValueWithClasses($detector, [$condClass1, $condClass2]);
    }
    
    public function intersectExists(Condition $c1, Condition $c2)
    {
        
    }
    
    
}
