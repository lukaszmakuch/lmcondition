<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition;

/**
 * Represents any condition that may be true in some context 
 * and it is possible to negate it.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface Condition
{
    /**
     * Checks whether this condition is true in this context.
     * 
     * @param \lukaszmakuch\LmCondition\Context $context in which the condition 
     * is checked
     * the passed context against this condition
     * 
     * @throws \InvalidArgumentException when it's not possible to check
     * this condition in this context
     * @throws \RuntimeException
     * @return boolean
     */
    public function isTrueIn(Context $context);
    
    /**
     * Returns true if this condition is negated.
     * 
     * If this condition is already negated, 
     * then calling this method takes no effect.
     * It doesn't change result of any method desribing the condition, 
     * so if there's a "value greater than X" condition, 
     * then it becomes "negated (value greater than X)",
     * NOT "value lower or equal X".
     * 
     * @return boolean
     */
    public function isNegated();
    
    /**
     * Negates this condition. 
     * 
     * Calling this method two times doesn't restore the previous state.
     * It means that if it is a "value greater than X" condition, 
     * than calling this method makes it "value NOT greater than X" 
     * and calling it again takes no effect as this stays "value NOT greater than X".
     * 
     * @return Condition self
     */
    public function negate();
    
    /**
     * Removes the negation.
     * 
     * If the condition is not negated, then calling this method takes no effect.
     * 
     * @return Condition self
     */
    public function removeNegation();
}
