# Function Design Spec

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
  * *description:*
  * *parameters:* $event_id
  * *returns:*
* getFightsByEventId
  * *description:*
  * *parameters:* $event_id
  * *returns:*
* doesBetExist
  * *description:*
  * *parameters:* $username, $fight_id
  * *returns:*
* getUserBets
  * *description:*
  * *parameters:* $username, $event_id
  * *returns:*
* addNewBet
  * *description:*
  * *parameters:* $bet
  * *returns:*
* updateBet
  * *description:*
  * *parameters:* $bet_update, $fight_id, $username
  * *returns:*

## ufc-event-leaderboard.php
* getFightsByEventId
  * *description:*
  * *parameters:* $event_id
  * *returns:*
* getBetsByEventId
  * *description:*
  * *parameters:* $event_id
  * *returns:*

## ufc-leaderboard.php

## ufc-calculations.php
* calculateEventResults
  * *description:*
  * *parameters:* $event_id, $fights, $bets
  * *returns:*
* calculateEventLeaderboard
  * *description:*
  * *parameters:* $event_id
  * *returns:*
* calculateOverallLeaderboard
  * *description:*
  * *parameters:*
  * *returns:*
