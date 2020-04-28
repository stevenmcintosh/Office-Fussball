![](/public/img/offfice_fussball_git.png)

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

Tested with and required for install:
PHP Version 7.3.9
MySQL Server version: 5.7.26
PHP Composer (latest version always best)

1. Clone the Repo
2. Place the "application" and "public" folders into your web root
3. Add the .htaccess file into your web root too.
   
   *Note: If your not using the webroot, then make sure to change the redirect url in your .htaccess files located at:*
   * *[webroot]/.htaccces*
   * *[webroot]/public/.htaccces*
  
   Example if fussball will be in its own folder:
   ```RewriteRule ^(.*) /public/$1 [L]``` 
   Becomes
   ```RewriteRule ^(.*) /fussball/public/$1 [L]```
4. Run PHP Composer insude the Fussball directory to download all dependacies 
5. Run the MySQL file [/sql/fsbl.sql] to install the database, users, teams, etc.
6. Create a file manually called [database_connection.php] and add your Database login details. See example file below. This step is manual as it ensures the file is not tracked by the repo and overwritten if you need to re-pull fro the repo. This needs to be stored in [/office-fussball/application/config/database_connection.php]
7. Open the webroot and you should see the homepage with a login. YTou can log in with the below default admin user
  *Note: by default Activee Directory (LDAP) is turned off, you can turn on via the admin panel)*
   *  Username: demouser
8. To being playing, first go to admin panel, remove all seasons. 
9. Then create new users, then create team names for the user, then create new season and off you go.


**EXAMPLE database_connection.php** 
You must create a separate file manually called [database_connection.php] as this file will be checked but never overwriiten. Example code below.
```
/**
 * Q. Do I need to ensure the file name is "database_connection.php"
 * A. Yes.
 *
 * Q. What is this? 
 * A. It is a file to hold your Prod Database connection details. 
 * 
 * Q. Why is this created manually and not part of the repo? 
 * A. Because when you pull from the repo, you will overwrite the your DB settings everytime, so we create a file that the repo doesnt know about.
 * 
 * Q. Where does the file go?
 * A. /office-fussball/application/config/database_connection.php
 * 
 */
$hostname = 'db23974932847.hosting.com';
$dbname = 'dbname123';
$dbuser = 'dbuser456';
$dbpass = 'dbpass789';
$dbcharset = 'utf8';
```


****************************************************************
*********************** 4. Screenshots *************************
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
