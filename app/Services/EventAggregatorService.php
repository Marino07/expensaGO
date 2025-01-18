<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class EventAggregatorService
{
    protected $ticketmasterService;
    protected $eventbriteService;

    public function __construct(
        TicketmasterService $ticketmasterService,
        EventbriteService $eventbriteService
    ) {
        $this->ticketmasterService = $ticketmasterService;
        $this->eventbriteService = $eventbriteService;
    }

    public function getEvents($location, $startDate = null, $endDate = null)
    {
        Log::info('Fetching events from multiple sources', [
            'location' => $location,
            'dates' => ['start' => $startDate, 'end' => $endDate]
        ]);

        $events = [];

        // Get Ticketmaster events
        $ticketmasterEvents = $this->ticketmasterService->searchEvents($location, $startDate, $endDate);
        Log::debug('Ticketmaster API Response', ['body' => $ticketmasterEvents]);
        $events = array_merge($events, $this->formatTicketmasterEvents($ticketmasterEvents));

        // Get Eventbrite events
        $eventbriteEvents = $this->eventbriteService->searchEvents($location, $startDate, $endDate);
        Log::debug('Eventbrite API Response', ['body' => $eventbriteEvents]);
        $events = array_merge($events, $this->formatEventbriteEvents($eventbriteEvents));

        Log::info('Total events found', ['count' => count($events)]);

        return $events;
    }

    protected function formatTicketmasterEvents($events)
    {
        return array_map(function($event) {
            return [
                'id' => $event['id'] ?? '',
                'title' => $event['name'] ?? '',
                'description' => $event['description'] ?? '',
                'start_date' => \Carbon\Carbon::parse($event['dates']['start']['dateTime'] ?? '')->format('Y-m-d'),
                'location' => $event['_embedded']['venues'][0]['name'] ?? '',
                'image' => $event['images'][0]['url'] ?? '',
                'price' => $event['priceRanges'][0]['min'] ?? null,
                'url' => $event['url'] ?? '',
                'source' => 'ticketmaster'
            ];
        }, $events);
    }

    protected function formatEventbriteEvents($events)
    {
        return array_map(function($event) {
            return [
                'id' => $event['id'] ?? '',
                'title' => $event['name']['text'] ?? '',
                'description' => $event['description']['text'] ?? '',
                'start_date' => \Carbon\Carbon::parse($event['start']['utc'] ?? '')->format('Y-m-d'),
                'location' => $event['venue']['name'] ?? '',
                'image' => $event['logo']['url'] ?? '',
                'price' => $event['ticket_availability']['minimum_ticket_price']['major_value'] ?? null,
                'url' => $event['url'] ?? '',
                'source' => 'eventbrite'
            ];
        }, $events);
    }
}
