<?php
/**
 * @package Simply Poll
 * @version 0.8
 */
/*
Plugin Name: Simply Poll
Plugin URI: 
Description: A very simple poll plugin for WordPress
Author: Neil Sweeney
Version: 0.8
Author URI: http://wolfiezero.com/
*/

define('SP_VERSION',	'0.8');
define('SP_DIR',		dirname(__FILE__).'/');
define('SP_FILE',		__FILE__);
define('SP_URL',		get_bloginfo('url').'/wp-content/plugins/simply-poll/');

if(!function_exists('add_action' )){
	echo 'I don\'t think you should be here?';
	exit;
}

require('lib/simplypoll.php');
require('lib/admin.php');

add_shortcode('poll', 'simplyPoll');

if(is_admin()){
	global $spAdmin;
	$spAdmin = new SimplyPollAdmin();
}


function simplyPoll($args){	
	
	global $simplyPoll;
	
	$simplyPoll = new SimplyPoll();
	return $simplyPoll->displayPoll($args);
	
}
