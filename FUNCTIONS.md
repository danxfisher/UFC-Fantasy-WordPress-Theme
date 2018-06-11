# Function Design Spec

## /PHP

### ufc-api.php
* **static** getNewsArticles
* **static** getAllEvents
* **static** getEventByID
  * *parameters:* $event_id
* **static** getEventByTitleAndDate
* **static** getFightsForEvent
  * *parameters:* $event_id

### ufc-tables.php (probably combine ufc-betting.php, ufc-event-leaderboard.php, ufc-leaderboard.php)
**ufc-betting.php**
* getEventByEventId
  * *parameters:* $event_id
* getFightsByEventId
  * *parameters:* $event_id
* doesBetExist
  * *parameters:* $username, $fight_id
* getUserBets
  * *parameters:* $username, $event_id
* addNewBet
  * *parameters:* $bet
* updateBet
  * *parameters:* $bet_update, $fight_id, $username

**ufc-event-leaderboard.php**
* getFightsByEventId
  * *parameters:* $event_id
* getBetsByEventId
  * *parameters:* $event_id
