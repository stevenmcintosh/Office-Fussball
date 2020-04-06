# fussball
The office got a Fussball table, so I built a website for it :)

Leaderboards, Seasons, Promotions, Relegations, Stats, the full she-bang. Even Active Directory integration!

It was a lot of fun and is still going strong.

Feel free to take a clone!

****************************************************************
***************************** Pages ****************************
****************************************************************

Full working Fussball League website.

User Pages:
- Homepage (league tables aand latest results)
- Fixtures
- League Tables
- My Results (page to enter your results for approval by opponent)
- Stats Page (top scorers, biggest wins, averages etc)
- Player Lists (teams)
- Hall of Fame (Best players of seasons gone by)
- Rules*
- Sportsbook on teams to win*
- Help Page (FAQ etc)*
- Gallery*
- Admin

Admin Pages
- Settings
  - Nav Items to allow
  - Number of promoted / relegated teams
  - Login method (Active Directory on/off)
  - Site Name (your office :))
  - Number of results to show on homepage
  - Scoring Systems (first to X goals)

*Manual administrated pages via HTML.

****************************************************************
************************ MySQL Warnings ************************
****************************************************************

On localhost, all warnings are shown.

If you receive some warnings, it may be because of your settings or some upgrages to MySQL.

To rid these warnings, open my.cnf (probably in your conf dir) and find the [mysqld] group, then paste the line below:

[mysqld]
sql_mode="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
