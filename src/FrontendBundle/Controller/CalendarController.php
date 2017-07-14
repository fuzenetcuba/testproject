<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * {@inheritDoc}
 */
class CalendarController extends Controller
{
    const ALLOWED_STATES = ['booked'];

    private static function checkValue($row, $col, &$entries)
    {
        return isset($entries[sprintf('R%sC%s', $row, $col)]);
    }

    private static function getValue($row, $col, &$entries)
    {
        if (self::checkValue($row, $col, $entries)) {
            return $entries[sprintf('R%sC%s', $row, $col)]->getContent();
        }

        return null;
    }

    public function indexAction($view, $key, Request $request)
    {
        $service = $this->get('wk_google_spreadsheet');

        $spreadsheet = $service->getSpreadsheets()->getByTitle('SCHEDULE entertaiment');
        $worksheet   = $spreadsheet->getWorksheets()->getByTitle('Scheduling');
        $cellFeed = $worksheet->getCellFeed();
        $entries  = $cellFeed->getEntries();

        $events = [];

        $now = new \DateTime();
        for ($i = 0; $i < $worksheet->getRowCount(); $i++) {
            $startTime = \DateTime::createFromFormat('D, M d h:i a', sprintf('%s %s',
                self::getValue($i, 1, $entries), self::getValue($i, 2, $entries)));

            if (false === $startTime || $startTime < $now) {
                continue;
            }

            $status = self::getValue($i, 10, $entries);

            if (null === $status || !in_array(strtolower($status), self::ALLOWED_STATES)) {
                continue ;
            }

            $events[] = [
                'summary'     => self::getValue($i, 5, $entries),
                'description' => self::getValue($i, 7, $entries),
                'location'    => self::getValue($i, 8, $entries),
                'start'       => [
                    'dateTime' => $startTime,
                ],
            ];
        }

        if (0 === count($events) && 'calendar' !== $view) {
            return new RedirectResponse(
                $this->generateUrl('calendar', ['view' => 'calendar'])
            );
        }

        usort($events, function($a, $b) {
            return new $a['start']['dateTime'] >= $b['start']['dateTime'];
        });

        return $this->render(sprintf('FrontendBundle:Calendar:%s.html.twig', $view), [
            'events' => $events,
        ]);
    }

    public function jsonAction($key, Request $request)
    {
        $service = $this->get('wk_google_spreadsheet');

        $spreadsheet = $service->getSpreadsheets()->getByTitle('SCHEDULE entertaiment (version 1)');
        $worksheet   = $spreadsheet->getWorksheets()->getByTitle('Scheduling');
        $cellFeed = $worksheet->getCellFeed();
        $entries  = $cellFeed->getEntries();

        $events = [];

        $now = new \DateTime();
        for ($i = 0; $i < $worksheet->getRowCount(); $i++) {
            $startTime = \DateTime::createFromFormat('D, M d h:i a', sprintf('%s %s',
                self::getValue($i, 1, $entries), self::getValue($i, 2, $entries)));

            if (false === $startTime || $startTime < $now) {
                continue;
            }

            $status = self::getValue($i, 10, $entries);

            if (null === $status || !in_array(strtolower($status), self::ALLOWED_STATES)) {
                continue ;
            }

            $events[] = [
                'summary'     => self::getValue($i, 5, $entries),
                'description' => self::getValue($i, 7, $entries),
                'location'    => self::getValue($i, 8, $entries),
                'start'       => [
                    'dateTime' => $startTime,
                ],
            ];
        }

        usort($events, function($a, $b) {
            return new $a['start']['dateTime'] >= $b['start']['dateTime'];
        });

        $events = array_map(function ($event) {
            return [
                'title'       => $event['summary'],
                'start'       => $event['start']['dateTime']->format('Y-m-dTH:i:s'),
                'description' => $event['description'],
                'location'    => isset($event['location']) ? $event['location'] : '',
            ];
        }, $events);

        return new JsonResponse($events);
    }
}