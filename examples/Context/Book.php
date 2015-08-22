<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\examples\Context;

use lukaszmakuch\LmCondition\Context;

class Book implements Context
{
    const EASY = 1;
    const NORMAL = 2;
    const HARD = 3;
    
    protected $title;
    protected $difficulty;
 
    public function getTitle()
    {
        return $this->title;
    }
 
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function isEasy()
    {
        return $this->difficulty == self::EASY;
    }
    
    public function isNormal()
    {
        return $this->difficulty == self::NORMAL;
    }
    
    public function isHard()
    {
        return $this->difficulty == self::HARD;
    }
    
    public function markAsEasy()
    {
        $this->difficulty = self::EASY;
        return $this;
    }
    
    public function markAsNormal()
    {
        $this->difficulty = self::NORMAL;
        return $this;
    }
    
    public function markAsHard()
    {
        $this->difficulty = self::HARD;
        return $this;
    }
}
