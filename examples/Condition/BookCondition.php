<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\Condition;

use lukaszmakuch\LmCondition\ConditionAbstract;
use lukaszmakuch\LmCondition\Context;
use lukaszmakuch\LmCondition\examples\Context\Book;
use lukaszmakuch\LmCondition\Exception\InvalidContext;

abstract class BookCondition extends ConditionAbstract
{
    protected function isTrueInImpl(Context $context)
    {
        if (!($context instanceof Book)) {
            throw new InvalidContext();
        }
        
        return $this->isTrueForBook($context);
    }
    
    abstract protected function isTrueForBook(Book $book);
}
