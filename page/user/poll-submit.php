<?php
require_once('../inc/wproot.php');	
require('logger.php');
$logger = new logger();

$logger->log('file opened');

// Check if poll is set (also can be used to check for direct access)
if( isset($_POST['poll']) ) {

	// Set our poll variable
	$pollID = (int)$_POST['poll'];
	
	// Check if a vote has occured
	if( isset($_POST['answer']) ) {	
		
		$answer = $_POST['answer'];

		// Check if we have the 'sptaken' cookie before trying to get data
		if(isset($_COOKIE['sptaken']))
			$taken	= $_COOKIE['sptaken'];
		else
			$taken	= null;

		$taken		= unserialize($taken);	// Unsearlize $taken to get an array
		$taken[]	= $pollID;				// Add this poll's ID to the $taken array
		$taken		= serialize($taken);	// Serialize $taken array ready to be stored again

		setcookie('sptaken', $taken, time()+315569260, '/');

	} else {
		$answer = null;

	}

		
	$simplyPoll = new SimplyPoll();

	if( !isset($_POST['backurl']) ) {
		$return['load']		= $simplyPoll->submitPoll($pollID, $answer);
		$return['pollid']	= $pollID;
		
		echo json_encode($return);

	} else {
		$simplyPoll->submitPoll($pollID, $answer);
		
		$regex = '/(.[^\?]*)/';		
		$querystring = preg_replace($regex, '', $_POST['backurl']);

		if( $querystring ) {
			preg_match($regex, $_POST['backurl'], $matches);
			$url = $matches[0].$querystring.'&';
			
		} else {
			$url = $_POST['backurl'].'?';
		}

		header('Location: '.$url.'simply-poll-return='.$answer);

	}

} else {
	echo DIRECT_ACCESS;
}