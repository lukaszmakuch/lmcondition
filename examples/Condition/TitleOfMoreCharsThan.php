<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\Condition;

use lukaszmakuch\LmCondition\examples\Context\Book;

class TitleOfMoreCharsThan extends BookCondition
{
    protected $threshold;
    
    public function __construct($threshold)
    {
        $this->threshold = $threshold;
    }
    
    public function getThreshold()
    {
        return $this->threshold;
    }
    
    protected function isTrueForBook(Book $book)
    {
        return (mb_strlen($book->getTitle()) > $this->threshold);
    }
}
