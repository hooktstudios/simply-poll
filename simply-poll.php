<?php
/**
 * @package Simply Poll
 * @version 1.1
 */
/*
Plugin Name: Simply Poll
Description: This plugin easily allows you to create polls
Author: Neil Sweeney
Version: 1.1
Author URI: http://wolfiezero.com/
*/

global $wpdb;

define('SP_VERSION',	'1.1');
define('SP_DIR',		dirname(__FILE__).'/');
define('SP_FILE',		__FILE__);
define('SP_URL',		get_bloginfo('url').'/wp-content/plugins/simply-poll/');

define('SP_TABLE',		$wpdb->get_blog_prefix().'sp_polls');

if(!function_exists('add_action' )){
	echo 'I don\'t think you should be here?';
	exit;
}

require('lib/simplypoll.php');
require('lib/admin.php');

add_shortcode('poll', 'simplyPoll');

// Registers the activation hook - runs the install function when the plugin is activated
register_activation_hook(__FILE__, 'sp_main_install');

if(is_admin()){
	global $spAdmin;
	$spAdmin = new SimplyPollAdmin();
}

function simplyPoll($args){	
	
	global $simplyPoll;
	
	$simplyPoll = new SimplyPoll();
	return $simplyPoll->displayPoll($args);
	
}

function sp_main_install() {
	global $wpdb;
	
	$wpdb->query("CREATE TABLE IF NOT EXISTS `".SP_TABLE."` (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`question` VARCHAR( 512 ) NOT NULL ,
					`answers` TEXT NOT NULL ,
					`added` INT NOT NULL ,
					`active` INT NOT NULL ,
					`totalvotes` INT NOT NULL ,
					`updated` INT NOT NULL ,
					PRIMARY KEY (  `id` )
				) ENGINE = MYISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;");
}