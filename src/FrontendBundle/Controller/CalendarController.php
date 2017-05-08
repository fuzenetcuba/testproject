<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class CalendarController extends Controller
{
    public function indexAction()
    {
        $calendars = $this->get('calendar.google')->listCalendars();

        $calendarId = $calendars->toSimpleObject()->items[0]['id'];
        var_dump($calendarId);

        $events = $this->get('calendar.google')->getEventsOnRange(
            $calendarId, 
            new \DateTime(),
            new \DateTime('now +1 month')
        )->toSimpleObject()->items;

        // var_dump($events);

        return $this->render('FrontendBundle:Calendar:timeline.html.twig', [
            'events' => $events,
        ]);
    }
}