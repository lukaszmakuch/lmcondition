# PHP Condition library

This library allows to build complex conditions connected with AND/OR statements with subconditions support and then check them in some context.

## Usage

### Getting objects

#### Building conditions
Let's say we want to match books that are hard to read or those ones with titles longer than 50 characters:
```php
$wiseBookCondition = (new ConditionComposite())
    ->addOR(new Difficulty(Difficulty::HARD))
    ->addOR(new TitleOfMoreCharsThan(50));
```
Or easy books with short titles for children:
```php
$booksForChildrenCondition = (new ConditionComposite())
    ->addAND(new Difficulty(Difficulty::EASY))
    ->addAND((new TitleOfMoreCharsThan(15))->negate());
```
Or those with really long titles:
```php
$titleLogerThan99Condition = new TitleOfMoreCharsThan(99);
```

#### Building context
An easy to read book with a long title:
```php
$easyBookWithLongTitle = (new Book())
    ->setTitle("A really long title of some book that must be wise.")
    ->markAsEasy();
```
A hard to read book with short title:
```php
$hardBookWithShortTitle = (new Book())
    ->markAsHard()
    ->setTitle("How?");
```
An easy to read book with a short title:
```php
$easyBookWithShortTitle = (new Book())
    ->markAsEasy()
    ->setTitle("Cats");
```
### Checking
#### Is a condition true?
Is this book wise?
```php
$wiseBookCondition->isTrueIn($easyBookWithLongTitle), //true
$wiseBookCondition->isTrueIn($hardBookWithShortTitle), //true
$wiseBookCondition->isTrueIn($easyBookWithShortTitle), //false
```
Is it for children?
```php
$booksForChildrenCondition->isTrueIn($easyBookWithLongTitle), //false
$booksForChildrenCondition->isTrueIn($hardBookWithShortTitle), //false
$booksForChildrenCondition->isTrueIn($easyBookWithShortTitle), //true
```
Is the title longer than 99 characters?
```php
$titleLogerThan99Condition->isTrueIn($easyBookWithLongTitle), //false
$titleLogerThan99Condition->isTrueIn($hardBookWithShortTitle), //false
$titleLogerThan99Condition->isTrueIn($easyBookWithShortTitle) //false
```

#### Does intersection exist between two conditions?
A title of a wise book may be longer than 99 characters.
```php
/* @var $intersectDetector IntersectDetector */
$intersectDetector->intersectExists(
    $wiseBookCondition,
    $titleLogerThan99Condition
), //true
```
But books for children have no titles that long.
```php
/* @var $intersectDetector IntersectDetector */
$intersectDetector->intersectExists(
    $booksForChildrenCondition,
    $titleLogerThan99Condition
) //false
```

#### Are two conditions equal?
Two condition objects may be equal.
```php
/* @var $comparator EqualityComparator */
$comparator->equal(
    $titleLogerThan99Condition,
    new TitleOfMoreCharsThan(99)
) //true
```
## Installation
Use [composer](https://getcomposer.org) to get the latest version:
```
$ composer require lukaszmakuch/lmcondition
```
## Example
The above code is taken from an example code located in ./examples. You can check there all files needed to provide this functionality.
## Documentation

For more information check the best documentation - unit tests in ./tests. There's also documentation generated in ./doc.

