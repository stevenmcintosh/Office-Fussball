# fussball
The office got a Fussball table, so I built a website for it. Leaderboards, seasons, Promotions, relegations, Active Directory integration etc. 

Was a lot of fun!

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

If you receive some warnings, it may be because of your settings or upgrages to MySQL.To rid these warnings, find yout my.cnf file and the [mysqld] group, then paste the line below

[mysqld]
sql_mode="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"
