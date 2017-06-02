# UFC Fantasy WordPress Theme

## Mission critical: complete by **Friday June 2nd**

- [x] fix date/time field on "Add Event" (or at least use a placeholder)
- [x] index - show only present day or future events
- [x] page-eventleaderboard.php
  - [x] fix 'back' button
- [ ] page-leaderboard.php
  - [ ] need to complete this yet
- [x] page-betting.php
  - [x] fix when no fights available for betting.

## Next milestones

- [ ] Refactor (html/css):
  - [ ] custom.css
  - [x] index.php
  - [ ] page-betting.php
    - [ ] why the hell did i use tables?
  - [ ] page-eventleaderboard.php
    - [ ] fix 'back' button
  - [ ] page-leaderboard.php
    - [ ] need to complete this yet
  - [x] single.php
- [ ] Update ufc-bets/page-events.php
  - [x] Adds a duplicate post ("-2" at the end of the post name)
- [ ] Fix date/time field on "Add Event"
- [ ] If fight is pulled (aka no longer exists via API call), remove it from picks page, picks db, bets db, etc
  - [ ] Backend update fights button?
- [ ] eggtactular: HTML injection here - http://www.danfisher.io/ufc/event-leaderboard/?title=test21%22%3E%3Cu%3E (couldn't escalate to XSS due to WAF  :( )
  - If parameter is incorrect (aka title != a post), show error page (different view of event-leaderboard or whatever page)
- [x] Auto generate post on "Add Event"
- [x] ACTUALLY complete the homepage
- [x] Mobile fixes

## Future milestones

- [ ] Include ACF in theme
- [ ] Placeholder images/text while loading data from the API... (or loading spinner of some sort)
- [ ] Break out all bet table calls to own class
- [ ] Refactor (improve inline php):
  - [x] index.php
  - [ ] page-betting.php
    - [ ] why the hell did i use tables?
  - [ ] page-eventleaderboard.php
  - [ ] page-leaderboard.php
  - [ ] single.php
- [x] Admin panel - Add event only for the current days event or future instead of all pas, present, and future fights
- [x] Complete front page


## Icebox

- [ ] Profile pages with stupid achievements
- [ ] Break events page out to custom post type ?
- [ ] Improve page speed and performance
- [ ] Lots of other stuff
- [ ] Prevent unwanted access to /wp-includes

## Canceled

- [ ] ~~Store fighter/fight/event JSON in it's own databases (or the necessary key/value pairs) to limit API calls.~~
  - [ ] ~~Need to check for when events/fights/fighters change~~
  - [ ] ~~This may not be possible if there is no date modified object~~
- [ ] ~~Only constant API call should be for news on main page.  Everything else should call from a database. Mmm ... probably not, actually~~
- [ ] ~~Socket.io for live, full screen event board ?~~

## Installation

1. Install WordPress
2. Download zip of repo and install as a WordPress theme (upload it within WordPress)
3. Settings > Permalinks > Post name
4. Pages > Add Page > Title = Leaderboard
  - Template = Leaderboard Page
5. Pages > Add Page > Title = Event Leaderboard
  - Template = Event Leaderboard Page
6. Pages > Add Page > Title = Betting
  - Template = Betting Page

## Documentation

* Coming soon
