<?php

class SimplyPoll {

	private	$pollData;
	private	$pollDB;


	/**
	 * Simply Poll construct
	 * Access the Simply Poll's database
	 * 
	 * @param	bool	$enque	Set enqued files
	 *************************************************************************/
	public function __construct($enque=true) {
		global $wp_scripts;
		
		// Establish our DB class
		$this->pollDB = new SimplyPollDB();
		
		if( $enque ) {
			wp_enqueue_script('jquery');
			if (!in_array('jquery', $wp_scripts->done) && !in_array('jquery', $wp_scripts->in_footer)) {
				$wp_scripts->in_footer[] = 'jquery';
			}
			
			wp_enqueue_script('jSimplyPoll', plugins_url('/script/simplypoll.js', SP_FILE));
			if (!in_array('jSimplyPoll', $wp_scripts->done) && !in_array('jSimplyPoll', $wp_scripts->in_footer)) {
				$wp_scripts->in_footer[] = 'jSimplyPoll';
			}
		}
		
	}
	
	/**
	 * Poll Database
	 * Access the Simply Poll's database
	 * 
	 * @return	object
	 *************************************************************************/
	public function pollDB() {
		return $this->pollDB;
	}
	
	
	/**
	 * Display Poll
	 * Gives the HTML for the poll to display on the front-end
	 * 
	 * @param	array	$args
	 * @return	string
	 *************************************************************************/
	public function displayPoll(array $args) {
		$limit = get_option('sp_limit');

		if( isset($args['id']) ) {
			$id			= $args['id'];
			$poll		= $this->grabPoll($id);
			$question	= stripcslashes($poll['question']);
			$answers	= $poll['answers'];
			
			foreach( $answers as $key => $answer ) {
				$answers[$key]['answer'] = stripcslashes($answer['answer']);
			}

			$data = include(SP_DIR.'page/user/poll-display.php');

			return $data;
		}
		
	}
	
	
	/**
	 * Submit Poll
	 * Passes back the poll results to return a JSON feed of responses. Can
	 * also just pass back previous results without passing an answer.
	 * 
	 * @param	int		$pollID
	 * @param	int		$vote
	 * @return	JSON
	 *************************************************************************/
	public function submitPoll($pollID, $vote=null) {
		$poll = $this->grabPoll($pollID);

		if( isset($vote) ) {
			$current = (int)$poll['answers'][$vote]['vote'];
			++$current;
			$poll['answers'][$vote]['vote'] = $current;

			$totalVotes = 0;

			foreach($poll['answers'] as $key => $answer){
				$totalVotes = $totalVotes + $answer['vote'];
			}

			$poll['totalvotes'] = $totalVotes;

			$success = $this->pollDB->setPollDB($poll);
			$poll['voted'] = $vote;
		}

		return SP_URL.'page/user/poll-results.php';
	}


	/**
	 * Grab Poll Info
	 *
	 * @param	$id
	 * @return	array
	 *************************************************************************/
	public function grabPoll($id=null){
		$poll = $this->pollDB->getPollDB($id);
		if (isset($poll[0])) {
			$poll = $poll[0];
			$poll['answers'] = unserialize($poll['answers']);
		}
		return $poll;
	}
	

}