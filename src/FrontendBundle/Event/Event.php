<?php

namespace FrontendBundle\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Generic event to use on custom event propagation
 *
 * @package \Dior\BackendBundle\Event
 */
class Event extends BaseEvent
{
    /**
     * Object related to the current event
     *
     * @var object
     */
    protected $subject;

    /**
     * Name of the event
     *
     * @var string
     */
    protected $name;

    /**
     * Additional parameters needed by the Event listener
     *
     * @var array
     */
    protected $params;

    public function __construct($subject, $name, array $params = array())
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->params = $params;
    }

    /**
     * @return object
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

}