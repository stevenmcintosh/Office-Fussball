1. Intro
2. Features
3. Instructions
4. MySql
5. Screenshots

****************************************************************
*************************** 1. Intro **************************
****************************************************************

# Fussball Website (for the office)

The office got a Fussball table, so I built a website for it :)

Leaderboards, Seasons, Promotions, Relegations, Stats, the full sha-bang!

It was a lot of fun and is still going strong. Feel free to take a clone!

Note: This is ideal for offices that have fusball tables that use Active Directory.

Disclaimer. The website was built in a few evenings over 2 weeks. It's not perfect!

****************************************************************
************************* 2. Features **************************
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
*********************** 3. Instructions ************************
****************************************************************

Tested with:
PHP Version 7.3.9
MySQL Server version: 5.7.26

1. Clone the Repo
2. Place the "application" and "public" folders into your web root
3. Add the .htaccess file into your web root too.
   *Note: If your not using the webroot, then make sure to change the redirect url in your .htaccess files located at:*
   *[webroot]/.htaccces*
   *[webroot]/public/.htaccces*
   Example if fussball will be in its own folder:
   `RewriteRule ^(.*) /public/$1 [L]` 
   Becomes
   `RewriteRule ^(.*) /fussball/public/$1 [L]`
4. Run the MySQL file to install the database. The MySQL file is in the download at: /sql/fussball.sql
5. Edit the config file with your database details
   *  "application/config/config_localhost.php"
6. Open the webroot and you should see the homepage with a login. YTou can log in with the below default admin user
  *Note: by default Activee Directory (LDAP) is turned off, you can turn on via the admin panel)*
   *  Username: adminusr
   *  Email: test@test.com
   *  Name: John Doe 
   *  Nickname: Johnny
7. Go to admin and create more users, then create teams, then create new season


****************************************************************
*********************** 4. MySQL Warnings **********************
****************************************************************

On localhost, all warnings are shown.

If you receive some warnings, it may be because of your settings or some upgrages to MySQL.

To rid these warnings, open my.cnf (probably in your conf dir) and find the [mysqld] group, then paste the line below:

[mysqld]
sql_mode="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"


****************************************************************
*********************** 5. Screenshots *************************
****************************************************************

League Tables

![](/public/img/screenshots/league_tables.png)

League Tables 2

![](/public/img/screenshots/league_tables2.png)

Divisions

![](/public/img/screenshots/divisions.png)

Fixtures

![](/public/img/screenshots/fixtures.png)

My Results

![](/public/img/screenshots/my_results2.png)

Confirm result

![](/public/img/screenshots/confirm_result.png)

Open Fixtures

![](/public/img/screenshots/open_fixtures.png)

Create Seasons

![](/public/img/screenshots/season_create.png)

End of Season

![](/public/img/screenshots/season_end.png)

Set up Singles or Doubles Team

![](/public/img/screenshots/singles_or_doubles.png)

Stats
![](/public/img/screenshots/stats.png)

Stats 2
![](/public/img/screenshots/stats2.png)

Season Preview
![](/public/img/screenshots/season_preview.png)

Admin Panel
![](/public/img/screenshots/admin_area.png)

Admin Configs

![](/public/img/screenshots/admin_config.png)

Rules

![](/public/img/screenshots/rules.png)

Gallery

![](/public/img/screenshots/gallery.png)

Hall of Fame

![](/public/img/screenshots/hall_of_fame.png)

Help

![](/public/img/screenshots/help.png)
