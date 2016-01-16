<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover;

use \lukaszmakuch\LmCondition\ConditionComposite;
use PHPUnit_Framework_TestCase;

class ORRemoverTest extends PHPUnit_Framework_TestCase
{
    public function testUsingDecomposerAndComposer()
    {
        $composite = new ConditionComposite();
        
        $decomposedCondition = [];
        $simplifiedComposite = new ConditionComposite();
        
        $decomposer = $this->getMock(Decomposer::class);
        $decomposer->method("decompose")->will($this->returnValueMap([
            [$composite, $decomposedCondition]
        ]));
        $decomposer->expects($this->atLeastOnce())
            ->method("decompose")
            ->with($composite);
        
        $composer = $this->getMock(Composer::class);
        $composer->method("compose")->will($this->returnValueMap([
            [$decomposedCondition, $simplifiedComposite]
        ]));
        
        $simplifier = new ORRemover($decomposer, $composer);
        
        $this->assertTrue(
            $simplifiedComposite === $simplifier->leaveOnlyOneLevelAND($composite)
        );
    }
}
