=== Simply Poll ===
Contributors: wolfiezero, olliea95, fubra
Tags: poll, results, polls, polling, survey, simple, easy, quiz
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 1.3

This plugin easily allows you to create polls.



== Description ==

Creating polls is now easy! With this plugin you can easily create a poll, add it to a page or post and users can instantly vote. Allows the creation of unlimited polls with up to 9 answers each.



== Installation ==

1. Download and unzip
2. Upload to your wp-content/plugins folder
3. Activate from the dashboard
4. Go to the new Polls menu page



== Changelog ==

= 1.3 =

* Added plugin URI
* Added file 'poll-submit.php' to replace 'poll-results.php'
* Added wp_enqueue_script('jquery'); when shortcode is used
* Added [jQuery Validation Plugin](https://github.com/jzaefferer/jquery-validation) for admin
* Added admin CSS
* Added default poll CSS
* Added page/user/poll-results.php to allow custom styling of poll results
* Added support for none JS clients
* Removed "ENGINE = MYISAM' from the database install
* Removed "CHARSET = UTF-8" from database install and now using get_bloginfo('charset')
* Updated file 'poll-submit.php' to a better structure
* Updated the SP_URL constant to combat x-domain issues
* Fixed issue where cookie is set but poll option not selected
* Fixed issue where poll results wouldn't display after submit
* Improved code layout
* Improved the poll add/edit script


= 1.1 =

* Fix the limiting


= 1.0 =

* Initial release



== To Do ==

* Add results reset
* Add style options
* Add export/import options