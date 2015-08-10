<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\tests;

use lukaszmakuch\LmCondition\Context;

/**
 * Key to value pairs context created for testing purposes.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class KeyValueContext implements Context
{
    protected $keyToValueMap;
    
    public function __construct(array $keyToValueMap = [])
    {
        $this->keyToValueMap = $keyToValueMap;
    }
    
    /**
     * Holds this value under this key.
     * 
     * @param String|int $key
     * @param mixed $val anything
     */
    public function set($key, $val)
    {
        $this->keyToValueMap[$key] = $val;
    }

    /**
     * Gets value held under this key.
     * 
     * @param String|int $key
     * 
     * @return mixed anything that has been previously set under this key
     * @throws \InvalidArgumentException when the key does not exist
     */
    public function get($key)
    {
        if (false === array_key_exists($key, $this->keyToValueMap)) {
            throw new \InvalidArgumentException(
                "No such key like {$key} found within this context!"
            );
        }
        
        return $this->keyToValueMap[$key];
    }
}
