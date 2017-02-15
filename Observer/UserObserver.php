<?php

namespace Observer;

/**
 * Class UserObserver
 */
class UserObserver extends Observer
{
    /**
     * @param \SplSubject $user
     * @return mixed
     */
    public function update(\SplSubject $user)
    {
        echo $user->getNewData();
    }

}