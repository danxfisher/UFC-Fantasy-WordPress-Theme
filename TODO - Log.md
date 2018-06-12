# To Do

## In progress

- [ ] Organize php functions to make pages more human readable
- [ ] OOP functions for all pages

## Future features

- [ ] If fight is pulled (aka fightID from bet/fight db table no longer exists via API call), remove it from picks page, picks table, bets table, etc
  - [ ] Backend update fights button for now?
- [ ] Refactor (html/css/php):
  - [ ] custom.css
  - [ ] page-betting.php
  - [ ] page-eventleaderboard.php
  - [ ] page-leaderboard.php
- [ ] eggtactular: HTML injection here - http://www.danfisher.io/ufc/event-leaderboard/?title=test21%22%3E%3Cu%3E (couldn't escalate to XSS due to WAF  :( )
  - If parameter is incorrect (aka title != a post), show error page (different view of event-leaderboard or whatever page)
- [ ] Placeholder images/text while loading data from the API... (or loading spinner of some sort)
- [ ] Break out all bet table calls to own class
- [ ] Prevent unwanted access to /wp-includes

## Icebox features

- [ ] Profile pages with stupid achievements
- [ ] Ability for a user to create a "group" and invite people to it for mini fantasy leagues
- [ ] Auto check daily if fights changed and update DB tables accordingly
  - [ ] On day of fight, check every 2, 4, or 6 hours.

## Completed

- [x] Admin panel - Add event only for the current days event or future instead of all past, present, and future fights
- [x] Complete front page
- [x] Auto generate post on "Add Event"
- [x] ACTUALLY complete the homepage
- [x] Mobile fixes
- [x] index.php
  - [x] "Results" section with Live button if current time == fight time
- [x] fix date/time field on "Add Event" (or at least use a placeholder)
- [x] index - show only present day or future events
- [x] page-eventleaderboard.php
  - [x] fix 'back' button
- [x] page-leaderboard.php
  - [x] need to complete this yet
- [x] page-betting.php
  - [x] fix when no fights available for betting.
  - [x] Update ufc-bets/page-events.php
    - [x] Adds a duplicate post ("-2" at the end of the post name)
- [x] Include ACF in theme
- [x] Create pages on theme activation
- [x] Can `Settings > Permalinks > Post name` be done on theme activation?
- [x] Default the reCaptcha **Off**

## Canceled

- [ ] ~~Store fighter/fight/event JSON in it's own databases (or the necessary key/value pairs) to limit API calls.~~
  - [ ] ~~Need to check for when events/fights/fighters change~~
  - [ ] ~~This may not be possible if there is no date modified object~~
- [ ] ~~Only constant API call should be for news on main page.  Everything else should call from a database. Mmm ... probably not, actually~~
- [ ] ~~Socket.io for live, full screen event board ?~~
