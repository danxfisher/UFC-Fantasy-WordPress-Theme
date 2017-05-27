# UFC Fantasy WordPress Theme

## Next top milestones

[ ] Auto generate post on "Add Event"
[ ] Include ACF in theme
[ ] Store fighter/fight/event JSON in it's own databases (or the necessary key/value pairs) to limit API calls.
  [ ] Need to check for when events/fights/fighters change
  [ ] This may not be possible if there is no date modified object
[ ] Only constant API call should be for news on main page.  Everything else should call from a database. Mmm ... probably not, actually
[ ] Placeholder images/text while loading data from the API...
[x] ~~Admin panel - Add event only for the current days event or future instead of all pas, present, and future fights~~

## To do

[ ] If fight is pulled, remove it from picks page, picks db, bets db, etc
[ ] Break events page out to custom post type ?
[ ] Refactor code
  [ ] Break out API calls and Table calls to different classes
[ ] Improve page speed and performance
[ ] Lots of other stuff
[ ] Prevent unwanted access to /wp-includes
[ ] eggtactular: HTML injection here - http://www.danfisher.io/ufc/event-leaderboard/?title=test21%22%3E%3Cu%3E (couldn't escalate to XSS due to WAF  :( )

## Installation

1. Install WordPress
2. Settings > Permalinks > Post name
3. Pages > Add Page > Title = Leaderboard > Template = Leaderboard Page
4. Pages > Add Page > Title = Event Leaderboard > Template = Event Leaderboard Page
5. Pages > Add Page > Title = Betting > Template = Betting Page
6. Download zip of repo and install as a WordPress theme (upload it within WordPress)

## Documentation

* Coming soon
