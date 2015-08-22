<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\Composer;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\Decomposer;
use lukaszmakuch\LmCondition\CompositeSimplifier\ORRemover\ORRemover;
use lukaszmakuch\LmCondition\EqualityComparator\CompositeEqualityComparator;
use lukaszmakuch\LmCondition\EqualityComparator\EqualityComparatorProxy;
use lukaszmakuch\LmCondition\examples\Condition\Difficulty;
use lukaszmakuch\LmCondition\examples\Condition\TitleOfMoreCharsThan;
use lukaszmakuch\LmCondition\examples\EqualityComparator\DifficultyComparator;
use lukaszmakuch\LmCondition\examples\EqualityComparator\TitleOfMoreCharsThanComparator;
use lukaszmakuch\LmCondition\examples\IntersectDetector\AlwaysFoundIntersectDetector;
use lukaszmakuch\LmCondition\examples\IntersectDetector\DifficultyIntersectDetector;
use lukaszmakuch\LmCondition\examples\IntersectDetector\TitleOfMoreCharsThanIntersectDetector;
use lukaszmakuch\LmCondition\IntersectDetector\CompositeIntersectDetector;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetectorProxy;

require __DIR__ . "/../vendor/autoload.php";

//simplifies internal operations on composites
$ORRemover = new ORRemover(new Decomposer(), new Composer());

//allows to check whether two conditions are equal
$comparatorProxy = new EqualityComparatorProxy(new ClassBasedRegistry());
$comparatorProxy->register(
    new DifficultyComparator(),
    Difficulty::class,
    Difficulty::class
);
$comparatorProxy->register(
    new TitleOfMoreCharsThanComparator(),
    TitleOfMoreCharsThan::class,
    TitleOfMoreCharsThan::class
);
$comparator = new CompositeEqualityComparator($comparatorProxy, $ORRemover);

//allows to check whether there is intersection between two conditions
$intersectDetectorProxy = new IntersectDetectorProxy(new ClassBasedRegistry());
$intersectDetectorProxy->register(
    new DifficultyIntersectDetector(new DifficultyComparator()),
    Difficulty::class,
    Difficulty::class
);
$intersectDetectorProxy->register(
    new TitleOfMoreCharsThanIntersectDetector(),
    TitleOfMoreCharsThan::class,
    TitleOfMoreCharsThan::class
);
$intersectDetectorProxy->setDefault(new AlwaysFoundIntersectDetector());
$intersectDetector = new CompositeIntersectDetector(
    $intersectDetectorProxy,
    $ORRemover
);
