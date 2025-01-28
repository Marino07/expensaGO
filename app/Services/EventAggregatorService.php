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
        return array_map(function ($event) {
            return $this->transformTicketmasterEvent($event);
        }, $events);
    }

    private function transformTicketmasterEvent($event)
    {
        $isFree = $this->isTicketmasterEventFree($event);
        $category = $this->extractTicketmasterCategory($event);
        $prices = $this->extractTicketmasterPrices($event);

        return [
            'id' => $event['id'] ?? null,
            'title' => $event['name'] ?? '',
            'description' => $event['description'] ?? '',
            'start_date' => isset($event['dates']['start']['localDate'])
                ? $event['dates']['start']['localDate']
                : null,
            'location' => $event['_embedded']['venues'][0]['name'] ?? '',
            'image' => isset($event['images'][0]['url']) ? $event['images'][0]['url'] : null,
            'price' => $prices['price'],
            'price_min' => $prices['price_min'],
            'price_max' => $prices['price_max'],
            'event_url' => $event['url'] ?? '',
            'source' => 'ticketmaster',
            'category' => $category,
            'free' => $isFree,
            'raw_data' => $event
        ];
    }

    private function extractTicketmasterPrices($event): array
    {
        $prices = [
            'price' => null,
            'price_min' => null,
            'price_max' => null
        ];

        if (isset($event['priceRanges']) && is_array($event['priceRanges'])) {
            foreach ($event['priceRanges'] as $priceRange) {
                if (isset($priceRange['min']) && isset($priceRange['max'])) {
                    $prices['price_min'] = $priceRange['min'];
                    $prices['price_max'] = $priceRange['max'];
                    // Set average price as the main price
                    $prices['price'] = ($priceRange['min'] + $priceRange['max']) / 2;
                    break;
                } elseif (isset($priceRange['min'])) {
                    $prices['price'] = $priceRange['min'];
                    $prices['price_min'] = $priceRange['min'];
                } elseif (isset($priceRange['max'])) {
                    $prices['price'] = $priceRange['max'];
                    $prices['price_max'] = $priceRange['max'];
                }
            }
        }

        return $prices;
    }

    private function isTicketmasterEventFree($event): bool
    {
        if (!isset($event['priceRanges'])) {
            return false;
        }

        foreach ($event['priceRanges'] as $priceRange) {
            if (isset($priceRange['min']) && $priceRange['min'] > 0) {
                return false;
            }
        }
        return true;
    }

    private function extractTicketmasterCategory($event): string
    {
        if (!empty($event['classifications'])) {
            foreach ($event['classifications'] as $classification) {
                if (isset($classification['segment']['name'])) {
                    return $classification['segment']['name'];
                }
            }
        }
        return 'Other';
    }

    protected function formatEventbriteEvents($events)
    {
        return array_map(function ($event) {
            return [
                'id' => $event['id'] ?? '',
                'title' => $event['name']['text'] ?? '',
                'description' => $event['description']['text'] ?? '',
                'start_date' => \Carbon\Carbon::parse($event['start']['utc'] ?? '')->format('Y-m-d'),
                'location' => $event['venue']['name'] ?? '',
                'image' => $event['logo']['url'] ?? '',
                'price' => $event['ticket_availability']['minimum_ticket_price']['major_value'] ?? null,
                'event_url' => $event['url'] ?? '',
                'source' => 'eventbrite'
            ];
        }, $events);
    }
}
