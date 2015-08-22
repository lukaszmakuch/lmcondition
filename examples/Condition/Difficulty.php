<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\Condition;

use lukaszmakuch\LmCondition\examples\Context\Book;

class Difficulty extends BookCondition
{
    const EASY = 1;
    const NORMAL = 2;
    const HARD = 3;
    const EASY_MAX_TITLE_LENGTH = 10;
    const HARD_MIN_TITLE_LENGTH = 40;
    
    protected $difficultyLevel;
    
    public function __construct($difficultyLevel)
    {
        $this->difficultyLevel = $difficultyLevel;
    }
    
    public function isEasy()
    {
        return $this->difficultyLevel === self::EASY;
    }
    
    public function isNormal()
    {
        return $this->difficultyLevel === self::NORMAL;
    }
    
    public function isHard()
    {
        return $this->difficultyLevel === self::HARD;
    }
    
    protected function isTrueForBook(Book $book)
    {
        if ($this->isEasy()) {
            return $book->isEasy();
        }
        
        if ($this->isNormal()) {
            return $book->isNormal();
        }
        
        if ($this->isHard()) {
            return $book->isHard();
        }
    }
}