<?php

namespace User;

/**
 * Class UserAbstract
 */
abstract class UserAbstract implements UserInterface
{
    /**
     * @return mixed
     */
    abstract public function test();
}