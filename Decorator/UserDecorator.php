<?php

namespace Decorator;

use User\UserInterface;


/**
 * Class UserDecorator
 */
class UserDecorator extends AbstractDecorator
{
    /**
     * UserDecorator constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        parent::__construct($user);
    }

    /**
     * @return string
     */
    public function test()
    {
        return "Decorator text" . PHP_EOL;
    }

    /**
     * @return string
     */
    public function test2()
    {
//        $this->notify();
        return " New text from Decorator" . PHP_EOL;
    }

}