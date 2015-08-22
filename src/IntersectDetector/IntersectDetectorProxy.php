<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\IntersectDetector;

use InvalidArgumentException;
use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\LmCondition\Condition;

/**
 * Hides many actual detector behind the common interface.
 * 
 * Allows to register a proper intersection detector for a pair of condition classes.
 * Inheritance is taken into account, so registering a comparator for the base
 * Condition interface will affect in using it to compare all derivatives
 * of this interface.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class IntersectDetectorProxy implements IntersectDetector
{
    protected $detectorToCondClassesReg;
    protected $defaultDetector;
    
    /**
     * Provides dependencies.
     * 
     * @param ClassBasedRegistry $r registry used to hold actual detectors
     */
    public function __construct(ClassBasedRegistry $r)
    {
        $this->detectorToCondClassesReg = $r;
        $this->defaultDetector = null;
    }
    
    /**
     * Registers actual detector associated with given condition classes.
     * 
     * Order of classes doesn't matter.
     * 
     * @param IntersectDetector $detector
     * @param String $condClass1
     * @param String $condClass2
     * 
     * @return null
     */
    public function register(IntersectDetector $detector, $condClass1, $condClass2)
    {
        $this->detectorToCondClassesReg->associateValueWithClasses(
            $detector, 
            [$condClass1, $condClass2]
        );
    }
    
    /**
     * Sets default detector used when no suitable detector for given conditions
     * may be found.
     * 
     * @param IntersectDetector $detector
     */
    public function setDefault(IntersectDetector $detector)
    {
        $this->defaultDetector = $detector;
    }
    
    public function intersectExists(Condition $c1, Condition $c2)
    {
        return $this->getDetectorBy($c1, $c2)
            ->intersectExists($c1, $c2);
    }
    
    /**
     * Looks for a suitable detector or returns the deafult detector if any 
     * has been set.
     * 
     * @param Condition $c1
     * @param Condition $c2
     * 
     * @return IntersectDetector
     * @throws InvalidArgumentException if it's not possible to obtain any detector
     */
    protected function getDetectorBy(Condition $c1, Condition $c2)
    {
        try {
            return $this->detectorToCondClassesReg->fetchValueByObjects([$c1, $c2]);
        } catch (InvalidArgumentException $e) {
            if (null === $this->defaultDetector) {
                throw $e;
            }
            
            return $this->defaultDetector;
        }
    }
}
