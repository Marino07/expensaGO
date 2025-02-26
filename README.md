# ExpensaGO

ExpensaGO is a comprehensive travel and expense management platform that offers event discovery, automatic transaction syncing, budget tracking, and insightful analytics — all in one place.

## Table of Contents

-   [Installation](#installation)
-   [Important](#important)
-   [Application Features & Demo](#application-features--demo)
-   [License](#license)

## Installation

1. Clone this repository into your local environment.
2. Install dependencies:
    ```
    composer install
    npm install
    ```
3. Generate an application key and configure your .env file:
    ```
    cp .env.example .env
    php artisan key:generate
    ```
4. Migrate the database:
    ```
    php artisan migrate
    ```
5. Serve the application:
    ```bash
    php artisan serve
    ```
6. Compile front-end assets:
    ```bash
    npm run dev
    ```

## Important

Update your `.env` file with the required service credentials:

## Application Features & Demo

### 1. Getting Started

#### User Registration

Register and verify your email to begin your ExpensaGO journey.
![Register and Verify](imagess/register.png)

#### Dashboard Access

Your central hub for managing all ExpensaGO features.
![application](imagess/app.png)

### 2. Trip Initialization

#### Create Your First Trip

Begin your travel planning journey and unlock additional features.
![strating_trip](approved/start_trip.png)

#### Personal Preferences

Customize your travel preferences for enhanced itinerary.
![preferences](imagess/preferences.png)

#### Location-Based Recommendations

Discover curated places and events for your destination.
![suggestions](approved/suggestionns.png)

### 3. Financial Integration

#### Bank Connection Setup

Link your credit card for seamless expense tracking.
![suggestions](imagess/more_actions.png)

#### Integration Process

Complete the secure banking initialization process.
![init](imagess/plaid_init.png)

#### Webhook Configuration

##### Project Exposure

Configure your project for external access.
![exposer](approved/expose_tokenn.png)

##### Plaid Webhook Setup

Enable automatic transaction synchronization.
![cards](approved/plaid_webhook.png)

### 4. Trip Planning & Management

#### Itinerary Generation

Create detailed daily plans with primary and related attractions.
![generator](approved/generating_plan.png)
![cards](imagess/plan_cards.png)

#### Place Discovery

Search, explore, and save locations with interactive map features.
![place_init](approved/places.png)
![place_d](imagess/place_d.png)

#### Event Management

Browse and bookmark upcoming events.
![place_init](approved/events.png)

### 5. AI Travel Assistant

Get personalized travel recommendations and advice.
![place_init](approved/ai_chat.png)

### 6. Financial Tracking

#### Transaction History

To unlock analytics we need some data use this route for previous card transactions or make test data in seeder
![transactions](imagess/plaid_transactions.png)

#### Expense Analytics

Monitor spending patterns and financial insights

![transactions](imagess/grafikon.png)

### 7. Saved Items Management

#### Saved Places

Access your bookmarked locations.
![places](approved/saved_items_places.png)

#### Saved Events

View your saved events collection.
![events](imagess/saved_items.png)

#### Event Booking

Purchase tickets  through service providers.
![events_ticketmaster](imagess/ticket.png)

### 8. Smart Notifications

#### Event Reminders

Stay updated on saved events and special offers.
![rem_events](imagess/reminder_events.png)

#### Budget Alerts

Receive notifications for budget threshold alerts.
![limit](approved/limit.png)

#### Expense Insights

Get mid-trip spending analysis,predictions and recommendations in pdf format.

![predictions](imagess/report.png)

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
