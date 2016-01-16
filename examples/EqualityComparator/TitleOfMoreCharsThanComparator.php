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
use lukaszmakuch\LmCondition\EqualityComparator\Exception\IncomparableConditions;
use lukaszmakuch\LmCondition\examples\Condition\TitleOfMoreCharsThan;

class TitleOfMoreCharsThanComparator implements EqualityComparator
{
    public function equal(Condition $c1, Condition $c2)
    {
        if (
            !($c1 instanceof TitleOfMoreCharsThan) 
            || !($c2 instanceof TitleOfMoreCharsThan)
        ) {
            throw new IncomparableConditions();
        }
        
        return (
            ($c1->isNegated() == $c2->isNegated())
            && ($c1->getThreshold() == $c2->getThreshold())
        );
    }
}
