<?php

namespace Observer;

/**
 * Class Observer
 */
abstract class Observer implements \SplObserver
{
    /**
     * @var \SplSubject
     */
    private $_observer;

    /**
     * Observer constructor.
     * @param \SplSubject $observer
     */
    function __construct(\SplSubject $observer)
    {
        $this->_observer = $observer;
        $this->_observer->attach($this);
    }
}