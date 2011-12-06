=== Simply Poll ===
Contributors: wolfiezero, olliea95, Fubra
Tags: poll, results, polls, polling, survey, simple, easy, quiz
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.4β

This plugin easily allows you to create polls.



== Description ==

Creating polls is now easy! With this plugin you can easily create a poll, add it to a page or post and users can instantly vote. Allows the creation of unlimited polls with up to 10 answers each.



== Installation ==

1. Download and unzip
2. Upload to your wp-content/plugins folder
3. Activate from the dashboard
4. Go to the new Polls menu page



== Screenshots ==

1. Simply Poll's question screen with the default theme
2. Results once question has been answered



== Changelog ==

= 1.4 =
* Improved AJAX request (no longer using `wp-load.php`)
* Improved PHP and JS docs
* Renamed the folder `user` (`/simply-poll/page/user/`) to `client` (`/simply-poll/page/client/`)

= 1.3.4 =
* Added tanslation support
* Polls now appear where short code is in content

= 1.3.3 =
* Fixed database creation issues and display SQL query to use if an error

= 1.3.2 =
* Fixed repo missing poll-submit.php

= 1.3.1 =

* Repo fixed
* Minor CSS changes

= 1.3 =

* Added plugin URI
* Added file 'poll-submit.php' to replace 'poll-results.php'
* Added wp_enqueue_script('jquery'); when shortcode is used
* Added [jQuery Validation Plugin](https://github.com/jzaefferer/jquery-validation) for admin
* Added [jqPlot](http://www.jqplot.com/) for admin results
* Added admin CSS
* Added default poll CSS
* Added page/user/poll-results.php to allow custom styling of poll results
* Added support for none JS clients
* Removed "ENGINE = MYISAM' from the database install
* Removed "CHARSET = UTF-8" from database install and now using get_bloginfo('charset')
* Updated file 'poll-submit.php' to a better structure
* Updated the SP_URL constant to combat x-domain issues
* Updated the admin interface
* Fixed issue where cookie is set but poll option not selected
* Fixed issue where poll results wouldn't display after submit
* Improved code layout
* Improved the poll add/edit script


= 1.2 = 
* Skipped, 1.3 update was more significant

= 1.1 =

* Fix the limiting


= 1.0 =

* Initial release



== To Do for v2.0 ==

* Add export/import options
* Make the admin more "WordPress", or at least better looking!
* Remove limitation on poll numbers and not have the default as 10
