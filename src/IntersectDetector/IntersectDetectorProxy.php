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
    protected $detectorToCondClassesReg;

    public function __construct(ClassBasedRegistry $r)
    {
        $this->detectorToCondClassesReg = $r;
    }
    
    public function register(IntersectDetector $detector, $condClass1, $condClass2)
    {
        return $this->detectorToCondClassesReg->associateValueWithClasses($detector, [$condClass1, $condClass2]);
    }
    
    public function intersectExists(Condition $c1, Condition $c2)
    {
        return $this->getDetectorBy($c1, $c2)->intersectExists($c1, $c2);
    }
    
    /**
     * Looks for a suitable detector.
     * 
     * @param Condition $c1
     * @param Condition $c2
     * 
     * @return IntersectDetector
     * @throws \InvalidArgumentException if it's not possible to obtain any detector
     */
    protected function getDetectorBy(Condition $c1, Condition $c2)
    {
        return $this->detectorToCondClassesReg->fetchValueByObjects([$c1, $c2]);
    }
    
}
