<?php
/*
Plugin Name: Simply Poll
Version: 1.3
Plugin URI: http://wolfiezero.com/wordpress/simply-poll/
Description: This plugin easily allows you to create polls
Author: WolfieZero
Author URI: http://wolfiezero.com/
*/

global $wpdb;

define('SP_VERSION',	'1.3');
define('SP_DIR',		dirname(__FILE__).'/');
define('SP_FILE',		__FILE__);
define('SP_URL',		'http://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/simply-poll-passport/');
define('SP_TABLE',		$wpdb->get_blog_prefix().'sp_polls');
define('DIRECT_ACCESS',	'I don\'t think you should be here?');

if(!function_exists('add_action' )){
	echo DIRECT_ACCESS;
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
	
	wp_enqueue_script('jquery');

	$simplyPoll = new SimplyPoll();
	return $simplyPoll->displayPoll($args);
	
}

function sp_main_install() {
	global $wpdb;
	
	$wpdb->query('CREATE TABLE IF NOT EXISTS `'.SP_TABLE.'` (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`question` VARCHAR( 512 ) NOT NULL ,
					`answers` TEXT NOT NULL ,
					`added` INT NOT NULL ,
					`active` INT NOT NULL ,
					`totalvotes` INT NOT NULL ,
					`updated` INT NOT NULL ,
					PRIMARY KEY (  `id` )
				) ENGINE = MYISAM DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;');
}