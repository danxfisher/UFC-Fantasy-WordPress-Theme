# UFC Classes Design Spec

## ufc-api.php

* **static** getNewsArticles
  * *description:* gets all news articles from UFC api
  * *parameters:* null
  * *returns:*  array of news articles objects
* **static** getAllEvents
  * *description:*  gets all events from UFC api
  * *parameters:* null
  * *returns:*  array of events objects
* **static** getEventByID
  * *description:*  get a UFC event by event ID from UFC api
  * *parameters:* $event_id
  * *returns:*  event object
* **static** getEventByTitleAndDate
  * *description:*  get a UFC event by title and date from UFC api
  * *parameters:* null
  * *returns:* null
* **static** getFightsForEvent
  * *description:* gets all fights for a UFC event by ID from UFC api
  * *parameters:* $event_id
  * *returns:*  array of fights objects

## ufc-betting.php
* getEventByEventId
  * *description:* get event from event table by event ID
  * *parameters:* $event_id
  * *returns:* event object
* getFightsByEventId
  * *description:* get fights from fights table by event ID
  * *parameters:* $event_id
  * *returns:* array of fights objects
* doesBetExist
  * *description:* checks to see if the user has submitted a bet for the $fight_id previously
  * *parameters:* $username, $fight_id
  * *returns:* boolean
* getUserBetsForEvent
  * *description:* gets all bets from bet table for $username by event ID
  * *parameters:* $username, $event_id
  * *returns:* array of bets objects
* addNewBet
  * *description:* adds a new bet to the bets table
  * *parameters:* $bet
  * *returns:* null
* updateBet
  * *description:* updates an existing bet in the bets table
  * *parameters:* $bet_update, $fight_id, $username
  * *returns:* null

## ufc-event-leaderboard.php
* getFightsByEventId
  * *description:* get fights from fight table by event ID
  * *parameters:* $event_id
  * *returns:* array of fights objects
* getBetsByEventId
  * *description:* get bets from bets table by event ID
  * *parameters:* $event_id
  * *returns:* array of bets objects

## ufc-calculations.php
* calculateEventResults
  * *description:* calculates the results of an event
  * *parameters:* $event_id, $fights, $bets
  * *returns:* null
* calculateEventLeaderboard
  * *description:* does all calculations necessary for event leaderboard
  * *parameters:* $event_id
  * *returns:* null
* calculateOverallLeaderboard
  * *description:* does all calculations necessary for overall leaderboard
  * *parameters:* $event_id
  * *returns:* null
