<?php
/**
 * Standalone file in WP with access to WP functions & settings
 */
$wpRoot = '../../../../..';
if (file_exists($wpRoot.'/wp-load.php'))
	require_once($wpRoot.'/wp-load.php');
else 
	require_once($wpRoot.'/wp-config.php');


// Check if poll is set (also can be used to check for direct access)
if(isset($_POST['poll'])){

	// Set our poll variable
	$poll = $_POST['poll'];

	// Check if a vote has occured
	if(isset($_POST['vote'])){	

		$vote = $_POST['vote'];

		// Check if we have the 'sptaken' cookie before trying to get data
		if(isset($_COOKIE['sptaken']))
			$taken	= $_COOKIE['sptaken'];
		else
			$taken	= null;

		$taken		= unserialize($taken);	// Unsearlize $taken to get an array
		$taken[]	= (int)$_POST['poll'];	// Add this poll's ID to the $taken array
		$taken		= serialize($taken);	// Serialize $taken array ready to be stored again

		setcookie('sptaken', $taken, time()+315569260, '/');

	} else {
		$vote = null;

	}

		
	$simplyPoll = new SimplyPoll();

	if(!isset($_POST['backurl'])) {
		echo $simplyPoll->submitPoll($poll, $vote);

	} else {
		$simplyPoll->submitPoll($poll, $vote);

		$querystring = preg_replace('/(https?://)?(www\.)?([a-zA-Z0-9_%\-+!\(\)]*)\b\.[a-z]{2,4}(\.[a-z]{2})?((/[a-zA-Z0-9_%\-+!\(\)]*)+)?(\.[a-z]*)?/', '', $_POST['backurl']);

		if($querystring){
			$url = $_POST['backurl'].$querystring.'&';
		} else {
			$url = $_POST['backurl'].'?';
		}

		header('Location: '.$url.'simply-poll-return='.$vote);

	}

} else {
	echo DIRECT_ACCESS;
}