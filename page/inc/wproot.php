<?php
	/**
	 * Standalone file in WP with access to WP functions & settings
	 *************************************************************************/
	$wpRoot = $_SERVER['DOCUMENT_ROOT'];
	if (file_exists($wpRoot.'/wp-load.php'))
		require_once($wpRoot.'/wp-load.php');
	else 
		require_once($wpRoot.'/wp-config.php');
	
	if( isset($_GET['pollid']) ) {
		$pollid = $_GET['pollid'];
	}