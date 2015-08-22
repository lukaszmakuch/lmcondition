<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\IntersectDetector;

use InvalidArgumentException;
use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\examples\Condition\Difficulty;
use lukaszmakuch\LmCondition\examples\EqualityComparator\DifficultyComparator;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetector;

class DifficultyIntersectDetector implements IntersectDetector
{
    protected $equalityComparator;
    
    public function __construct(DifficultyComparator $cmp)
    {
        $this->equalityComparator = $cmp;
    }
    
    public function intersectExists(Condition $c1, Condition $c2)
    {
        if (
            !($c1 instanceof Difficulty) 
            || !($c2 instanceof Difficulty)
        ) {
            throw new InvalidArgumentException("unsupported conditions");
        }
        
        return $this->equalityComparator->equal($c1, $c2);
    }
}
