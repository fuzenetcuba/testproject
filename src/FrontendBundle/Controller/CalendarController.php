<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * {@inheritDoc}
 */
class CalendarController extends Controller
{
    public function indexAction($key, Request $request)
    {
        $calendars = $this->get('calendar.google')
            ->listCalendars()
            ->toSimpleObject()
            ->items
        ;

        // pick the first matching calendar
        $calendar = array_filter($calendars, function ($calendar) use ($key) {
            return preg_match('/'. $key . '/', strtolower($calendar['summary']));
        });

        if (count($calendar) == 0) {
            throw $this->createNotFoundException('Calendar not found');
        }

        $calendarId = $calendar[0]['id'];
        $events = $this->get('calendar.google')->getEventsOnRange(
            $calendarId, 
            new \DateTime(),
            new \DateTime('now +1 month')
        )->toSimpleObject()->items;
// var_dump($events); die ;
        return $this->render('FrontendBundle:Calendar:timeline.html.twig', [
            'events' => $events,
        ]);
    }
}