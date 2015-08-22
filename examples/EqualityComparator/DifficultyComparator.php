<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\EqualityComparator;

use lukaszmakuch\LmCondition\Condition;
use lukaszmakuch\LmCondition\EqualityComparator\EqualityComparator;
use lukaszmakuch\LmCondition\examples\Condition\Difficulty;
use InvalidArgumentException;

class DifficultyComparator implements EqualityComparator
{
    public function equal(Condition $c1, Condition $c2)
    {
        if (
            !($c1 instanceof Difficulty) 
            || !($c2 instanceof Difficulty)
        ) {
            throw new InvalidArgumentException("unsupported conditions");
        }
        
        return (
            ($c1->isEasy() === $c2->isEasy())
            && ($c1->isNormal() === $c2->isNormal())
            && ($c1->isHard() === $c2->isHard())
        );
    }
}
