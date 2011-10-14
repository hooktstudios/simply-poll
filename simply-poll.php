<?php
/*
Plugin Name: Simply Poll
Version: 1.3
Plugin URI: http://wolfiezero.com/wordpress/simply-poll/
Description: This plugin easily allows you to create polls
Author: WolfieZero
Author URI: http://wolfiezero.com/
*/

require('config.php');

if( !function_exists('add_action') ) {
	echo SP_DIRECT_ACCESS;
	exit;
}

// Registers the activation hook - runs the install function when the plugin is activated
register_activation_hook(SP_FILE, 'spInstall');

require('lib/simplypoll.php');
require('lib/db.php');

add_action('init', 'simplyPollFiles');
add_shortcode('poll', 'simplyPollClient');

if( is_admin() ){ simplyPollAdmin(); }

/**
 * Simply Poll Client View
 * Handles Simply Poll on the client side of the site
 * 
 * @param	array	$args
 * @return	string	HTML output of the poll
 ******************************************************************************/
function simplyPollClient($args){		
	$simplyPoll = new SimplyPoll();
	return $simplyPoll->displayPoll($args);
}


/**
 * Simply Poll Admin View
 * Handles Simply Poll for the admin
 ******************************************************************************/
function simplyPollAdmin() {
	require('lib/admin.php');
	global $spAdmin;
	$spAdmin = new SimplyPollAdmin();	
}


/**
 * Simply Poll Files
 ******************************************************************************/
function simplyPollFiles(){		
	wp_register_style('simplypollCSS', SP_CSS_USER, false, SP_VERSION);

	wp_enqueue_style('simplypollCSS');
	wp_enqueue_script('jquery');
}


/**
 * Simply Poll Install Script
 * Installs Simply Poll correctly
 ******************************************************************************/
function spInstall() {
	global $wpdb;
	
	$wpdb->query(
		'CREATE TABLE IF NOT EXISTS `'.SP_TABLE.'` (
			`id` INT NOT NULL AUTO_INCREMENT ,
			`question` VARCHAR( 512 ) NOT NULL ,
			`answers` TEXT NOT NULL ,
			`added` INT NOT NULL ,
			`active` INT NOT NULL ,
			`totalvotes` INT NOT NULL ,
			`updated` INT NOT NULL ,
			PRIMARY KEY ( `id` )
		) 
		DEFAULT 
			CHARSET = '.get_bloginfo('charset').' 
			AUTO_INCREMENT = 1;
	');
}