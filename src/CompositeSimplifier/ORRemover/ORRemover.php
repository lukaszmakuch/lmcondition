<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover;

use lukaszmakuch\LmCondition\Condition;

/**
 * Description of ORRemover
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ORRemover
{
    protected $decomposer;
    protected $composer;
    
    public function __construct(Decomposer $decoposer, Composer $composer)
    {
        $this->decomposer = $decoposer;
        $this->composer = $composer;
    }
    
    public function leaveOnlyOneLevelAND(Condition $c)
    {
        return $this->composer->compose(
            $this->decomposer->decompose($c)
        );
    }
}
