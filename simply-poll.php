<?php
/*
Plugin Name: Simply Poll
Version: 1.4β
Plugin URI: http://wolfiezero.com/wordpress/simply-poll/
Description: This plugin easily allows you to create polls
Author: WolfieZero
Author URI: http://wolfiezero.com/
*/

require_once('config.php');
require_once('logger.php');

if( !function_exists('add_action') ) {
	echo SP_DIRECT_ACCESS;
	exit;
}

// Registers the activation hook - runs the install function when the plugin is activated
register_activation_hook(__FILE__, 'spInstall');

require('lib/simplypoll.php');
require('lib/db.php');

add_action('init', 'spFiles');
add_shortcode('poll', 'spClient');

if( is_admin() ){
	spAdmin(); 
}


/**
 * Simply Poll Client
 * Handles Simply Poll on the client side of the site
 * 
 * @param	array	$args
 * @return	string	HTML output of the poll
 */
function spClient($args){		
	$simplyPoll = new SimplyPoll();
	return $simplyPoll->displayPoll($args);
}


/**
 * Simply Poll Admin 
 * Handles Simply Poll for the admin
 */
function spAdmin() {
	require('lib/admin.php');
	global $spAdmin;
	$spAdmin = new SimplyPollAdmin();
}


/**
 * Simply Poll Files
 * Loads in the files used for Simply Poll
 */
function spFiles() {
	wp_register_style('sp-client', SP_CSS_CLIENT, false, SP_VERSION);
	wp_enqueue_style('sp-client');

	wp_enqueue_script('jquery');
	
	wp_enqueue_script('sp-client-ajax', plugins_url('script/simplypoll.js', __FILE__), array('jquery'), SP_VERSION, true);
	wp_localize_script('sp-client-ajax', 'spAjax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	

	// When Submit
	add_action('wp_ajax_spAjaxSubmit', 'spSubmit'); // ajax for logged in users
	add_action('wp_ajax_nopriv_spAjaxSubmit', 'spSubmit'); // ajax for not logged in users
	
	// When Results
	add_action('wp_ajax_spAjaxResults', 'spResults'); // ajax for logged in users
	add_action('wp_ajax_nopriv_spAjaxResults', 'spResults'); // ajax for not logged in users
}


function spSubmit() {
	require(SP_SUBMIT);
	exit;
}

function spResults() {
	require(SP_RESULTS);
	exit;
}


/**
 * Simply Poll Install Script
 * Installs Simply Poll correctly
 * 
 * @return bool
 */
function spInstall() {
	global $wpdb;
	
	$sql = '
		CREATE TABLE IF NOT EXISTS `'.SP_TABLE.'` (
			`id` INT NOT NULL AUTO_INCREMENT ,
			`question` VARCHAR( 512 ) NOT NULL ,
			`answers` TEXT NOT NULL ,
			`added` INT NOT NULL ,
			`active` INT NOT NULL ,
			`totalvotes` INT NOT NULL ,
			`updated` INT NOT NULL ,
			PRIMARY KEY ( `id` )
		)
	';
	
	$success = $wpdb->query($sql);

	return $success;
}