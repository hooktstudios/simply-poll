<?php

global $wpdb;

define('SP_VERSION',		'1.3');
define('SP_FILE',			__FILE__);
define('SP_DIR',			dirname(SP_FILE).'/');
define('SP_URL',			'http://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins/simply-poll/');
define('SP_URI',			$_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/simply-poll/');
define('SP_TABLE',			$wpdb->get_blog_prefix().'sp_polls');
define('SP_DIRECT_ACCESS',	'I don\'t think you should be here?');
define('SP_CSS_USER',		plugins_url('/simply-poll/css/default.css'));
define('SP_CSS_ADMIN',		plugins_url('/simply-poll/css/admin.css'));