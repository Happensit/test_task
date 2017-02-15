<?php

namespace User;

/**
 * Class User
 */
class User extends UserAbstract
{
    /**
     * @return mixed
     */
    public function test()
    {
        return "Test" . PHP_EOL;
    }

    /**
     * @return string
     */
    public function test2($text)
    {
        return $text . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getNewData()
    {
        return "Text for Observer" . PHP_EOL;
    }

}