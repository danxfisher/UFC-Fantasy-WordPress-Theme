# Function Design Spec

## ufc-api.php

* **static** getNewsArticles
  * *description:*
  * *parameters:* null
  * *returns:*
* **static** getAllEvents
  * *description:*
  * *parameters:* null
  * *returns:*
* **static** getEventByID
  * *description:*
  * *parameters:* $event_id
  * *returns:*
* **static** getEventByTitleAndDate
  * *description:*
  * *parameters:* null
  * *returns:*
* **static** getFightsForEvent
  * *description:*
  * *parameters:* $event_id
  * *returns:*

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
