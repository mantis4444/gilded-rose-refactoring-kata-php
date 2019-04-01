# Gilded Rose Refactoring Kata PHP
This is the the refactored version of the original [Gilded Rose Refactoring Kata](https://duckduckgo.com "Gilded Rose Refactoring Kata") in PHP. Package comes with created PHP unit tests, refactoring done and new feature added.

The Gilded Rose Requirements can be found [here](https://github.com/emilybache/GildedRose-Refactoring-Kata/blob/master/GildedRoseRequirements.txt "Gilded Rose Refactoring Kata Requirements Specification").

## Requirements
- [PHP 7.2](https://www.php.net/manual/en/install.php "PHP installation") or greater.
- You might need some extra PHP packages like `php-xml`. Just follow on screen instructions.
- [Composer](https://getcomposer.org/download/ "Composer installation").

## Setup
To install the project you need:
1. Clone repository to desired directory `git clone git@github.com:mantis4444/gilded-rose-refactoring-kata-php.git /desired/directory`.
2. Enter project directory `cd /desired/directory`.
3. Install the project `composer install`.

**Note** that if you are doing it on _Windows OS_ you need to use `\` instead of  `/` for directory separation.

## Usage
- To execute defined instructions of the application you need to excute the `texttest_fixture.php` file with the command `php src/texttest_fixture.php`.
- Instructions of the applications can be edited in `src/texttest_fixture.php` file.
- The results are returned on the terminal screen.
- You can execute PHPUnit tests with `./vendor/bin/phpunit`.

**Note** that these instructions assumes that you are in the project directory.

## Dev notes
I kept the original coding style to adapt to current situation. Though I would have liked to use PSR standard and namespaces. As a result application supports original input, thus the `texttest_fixture.php` file was not touched.

First of all I made tests to make sure that original code is working properly and suprisngly it was, despite its messy algorythm structure. Then refactoring process started. In the beginning I shrinked the `update_quality()` function as much as possible. Code became much more readable, but there were still too many conditional `if` and I did not see a way how I could minimize it more.

After doing some analysis I realised that _Polymorphism_ is the solution. First of all I created enhanced item class called `ItemEnhanced` which was extending original and untouchable `Item` class to improve its functionality. The first challange was to enhance _Items_ and support original instruction code. While original code is setting up items with `Item` class I had to find a way to enhance items without changing it. I made it on the _GildedRose_ app initialization constructor, enhancing _Items_ while setting them. To make the original _item_ object enhance too, I used reference links.


Now class `ItemEnhanced` has the funcionality that all type of items share. On the next step I created class for every type of item that they would have their own special funcionality. They are extending the `ItemEnhanced` class thus the original `Item` also. Functions of the `ItemEnhanced` that are being used only in those item type classes are _protected_ for the accesibility in item type class only.

After refactoring is finished, tests are passing successfully. Now it was a time to add new funcionality. Having new structure of the application it was easy to do that. First I created new item type class for "Conjured" item with its specific rules. Then I created new tests for it.

_Voila_ everything works like a charm, code is much more readable and flexible. I would say that the tests could be improved a lot. They could be much more specific than they are now.