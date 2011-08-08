<?php
	/**
	 * Standalone file in WP with access to WP functions & settings
	 */
	$wpRoot = '../../../../..';
	if (file_exists($wpRoot.'/wp-load.php')) {
		require_once($wpRoot.'/wp-load.php');
	} else {
		require_once($wpRoot.'/wp-config.php');
	}
	
	$poll = $_POST['poll'];

	if(isset($_POST['vote'])){
		$vote = $_POST['vote'];
	} else {
		$vote = null;
	}
		

	$simplyPoll = new SimplyPoll();
	echo $simplyPoll->submitPoll($poll, $vote);
?>