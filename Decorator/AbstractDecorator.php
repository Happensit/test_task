<?php

namespace Decorator;

use Observer\Observable, User\UserInterface;

/**
 * Class AbstractDecorator
 */
abstract class AbstractDecorator extends Observable
{
    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * AbstractDecorator constructor.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    function __call($method, $arguments)
    {
        return call_user_func_array([$this->user, $method], $arguments);
    }

}