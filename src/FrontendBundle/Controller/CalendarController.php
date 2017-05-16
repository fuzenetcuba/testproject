<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        usort($events, function($a, $b) {
            return new \DateTime($a['start']['dateTime']) >= new \DateTime($b['start']['dateTime']);
        });

        return $this->render('FrontendBundle:Calendar:calendar.html.twig', [
            'events' => $events,
        ]);
    }

    public function jsonAction($key, Request $request)
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

        usort($events, function($a, $b) {
            return new \DateTime($a['start']['dateTime']) >= new \DateTime($b['start']['dateTime']);
        });

        $events = array_map(function($event) {
            return [
                'title' => $event['summary'],
                'start' => $event['start']['dateTime'],
                'description' => $event['description'],
                'location' => $event['location'] ?? '',
            ];
        }, $events);

        return new JsonResponse($events);
    }
}