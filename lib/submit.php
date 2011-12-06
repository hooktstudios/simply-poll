<?php
global $wp;

// Check if poll is set (also can be used to check for direct access)
if( isset($_POST['poll']) ) {

	// Set our poll variables
	$pollID		= (int)$_POST['poll'];
	$simplyPoll	= new SimplyPoll();	
	$answer		= null;
	
	
	// A vote has been made
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

	}

	// No back url has been set so treat it as a Javascript call
	if( !isset($_POST['backurl']) ) {
		
		$return = array(
			'load'		=> $simplyPoll->submitPoll($pollID, $answer), // This function will add the results
			'pollid'	=> $pollID
		);
		$json = json_encode($return);
		
		echo $json;

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
		
		$location = $url.'simply-poll-return='.$answer;

		header('Location: '.$location);

	}

} else {
	echo SP_DIRECT_ACCESS;
}