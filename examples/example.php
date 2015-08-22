<?php

/**
 * This file is part of the LmCondition library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

use lukaszmakuch\LmCondition\ConditionComposite;
use lukaszmakuch\LmCondition\EqualityComparator\EqualityComparator;
use lukaszmakuch\LmCondition\examples\Condition\Difficulty;
use lukaszmakuch\LmCondition\examples\Condition\TitleOfMoreCharsThan;
use lukaszmakuch\LmCondition\examples\Context\Book;
use lukaszmakuch\LmCondition\IntersectDetector\IntersectDetector;

require __DIR__ . "/init.php";

//CONDITIONS

//hard to read books or just those with titles loger than 50 characters
$wiseBookCondition = (new ConditionComposite())
    ->addOR(new Difficulty(Difficulty::HARD))
    ->addOR(new TitleOfMoreCharsThan(50));

//easy to read books with titles no longer than 15 characters
$booksForChildrenCondition = (new ConditionComposite())
    ->addAND(new Difficulty(Difficulty::EASY))
    ->addAND((new TitleOfMoreCharsThan(15))->negate());

//books with titles longer than 99 characters
$titleLogerThan99Condition = new TitleOfMoreCharsThan(99);

//BOOKS

$easyBookWithLongTitle = (new Book())
    ->setTitle("A really long title of some book that must be wise.")
    ->markAsEasy();

$hardBookWithShortTitle = (new Book())
    ->markAsHard()
    ->setTitle("How?");

$easyBookWithShortTitle = (new Book())
    ->markAsEasy()
    ->setTitle("Cats");

//OPERATIONS

//checking books against conditions
var_dump(
    $wiseBookCondition->isTrueIn($easyBookWithLongTitle), //true
    $wiseBookCondition->isTrueIn($hardBookWithShortTitle), //true
    $wiseBookCondition->isTrueIn($easyBookWithShortTitle), //false

    $booksForChildrenCondition->isTrueIn($easyBookWithLongTitle), //false
    $booksForChildrenCondition->isTrueIn($hardBookWithShortTitle), //false
    $booksForChildrenCondition->isTrueIn($easyBookWithShortTitle), //true

    $titleLogerThan99Condition->isTrueIn($easyBookWithLongTitle), //false
    $titleLogerThan99Condition->isTrueIn($hardBookWithShortTitle), //false
    $titleLogerThan99Condition->isTrueIn($easyBookWithShortTitle) //false
);

//looking for intersection between conditions
var_dump(
    /* @var $intersectDetector IntersectDetector */
    $intersectDetector->intersectExists(
        $wiseBookCondition,
        $titleLogerThan99Condition
    ), //true

    $intersectDetector->intersectExists(
        $booksForChildrenCondition,
        $titleLogerThan99Condition
    ) //false
);

//comparing conditions
var_dump(
    /* @var $comparator EqualityComparator */
    $comparator->equal(
        $titleLogerThan99Condition,
        new TitleOfMoreCharsThan(99)
    ) //true
);