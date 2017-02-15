<?php

namespace Observer;

/**
 * Class Observable
 */
abstract class Observable implements \SplSubject
{
    /**
     * @var
     */
    protected $observer;

    /**
     * @param \SplObserver $observer
     */
    public function attach(\SplObserver $observer)
    {
        $this->observer = $observer;
    }

    /**
     * @param \SplObserver $observer
     */
    public function detach(\SplObserver $observer)
    {
        if (isset($this->observer)) {
            unset($this->observer);
        }
    }

    public function notify()
    {
        $this->observer->update($this);
    }
}