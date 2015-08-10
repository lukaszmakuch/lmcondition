<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\LmCondition\EqualityComparator;

use lukaszmakuch\LmCondition\tests\BooleanCondition;
use PHPUnit_Framework_TestCase;
use Zend\Stdlib\Hydrator\Strategy\Exception\InvalidArgumentException;

class EqualityComparatorProxyTest extends PHPUnit_Framework_TestCase
{
    protected $proxy;
    
    protected function setUp()
    {
        $this->proxy = new EqualityComparatorProxy();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowingExceptionIfNoComparatorRegistered()
    {
        $c1 = new BooleanCondition(true);
        $c2 = new BooleanCondition(true);
        $this->proxy->equal($c1, $c2);
    }
}
