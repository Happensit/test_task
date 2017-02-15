<?php


/**
 * Class Foo
 */
class Foo {

    public static function who() {
        echo __CLASS__ . PHP_EOL;
    }

    public static function test() {
        static::who(); // Здесь позднее статическое связывание
    }
}

/**
 * Class Bar
 */
class Bar extends Foo {

    public static function who() {
        echo __CLASS__ . PHP_EOL;
    }
}

Bar::test(); // Bar