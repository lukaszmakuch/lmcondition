<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover;

use lukaszmakuch\LmCondition\ConditionComposite;

/**
 * Composes a 2D array of pure Condition objects into a ConditionComposite.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class Composer
{
    
    /**
     * Rows of given array 2D represents OR conditions while columns represents
     * AND conditions.
     * 
     * Array of Condition objects like this:
     * <pre>
     * [
     *     [A, B],
     *     [C, D]
     * ]
     * </pre>
     * 
     * is composed into ConditionComposite like this:
     * 
     * ((A AND B) OR (C AND D))
     * 
     * @param array $decomposedComposite
     * 
     * @return ConditionComposite
     */
    public function compose(array $decomposedComposite)
    {
        $composite = new ConditionComposite();
        foreach ($decomposedComposite as $decomposedRowData) {
            $ANDChain = $this->getANDCompositeOfConditions($decomposedRowData);
            $composite->addOR($ANDChain);
        }
        
        return $composite;
    }
    
    /**
     * Takes an array of Condition objects and returns a ConditionComposite
     * containing all given conditions as AND nodes.
     * 
     * @param \lukaszmakuch\LmCondition\Condition[] $ANDConditions
     * @return ConditionComposite
     */
    protected function getANDCompositeOfConditions(array $ANDConditions)
    {
        $composite = new ConditionComposite();
        foreach ($ANDConditions as $condition) {
            $composite->addAND($condition);
        }
        
        return $composite;
    }
}
