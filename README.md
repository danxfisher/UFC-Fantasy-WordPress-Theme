# UFC Fantasy WordPress Theme

The majority of this theme is completed and it is fully functioning.  I will do some clean up soon so the code isn't as shitty.  I need to finish the documentation as well.  I will likely only update this sparingly if necessary.  Enjoy.

## Installation & Documentation

[See the wiki](https://github.com/danxfisher/UFC-Fantasy-WordPress-Theme/wiki) - Work in progress

## Future features

- [ ] If fight is pulled (aka no longer exists via API call), remove it from picks page, picks db, bets db, etc
  - [ ] Backend update fights button?
- [ ] Refactor (html/css):
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
