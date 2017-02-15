<?php

spl_autoload_register(function ($className) {
        include sprintf("%s.php", str_replace('\\', "/", $className));
    }
);

$user = new Decorator\UserDecorator(new User\User());
(new Observer\UserObserver($user));

echo $user->test();
echo $user->test2();
echo $user->notify();