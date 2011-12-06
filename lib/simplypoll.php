<?php

class SimplyPoll {

	private	$pollData;
	private	$pollDB;


	/**
	 * Simply Poll construct
	 * Access the Simply Poll's database
	 * 
	 * @param	bool	$enque	Set enqued files
	 */
	public function __construct($enque=true) {
		// Establish our DB class
		$this->pollDB = new SimplyPollDB();
	}
	
	
	/*************************************************************************/
	
	
	/**
	 * Poll Database
	 * Access the Simply Poll's database
	 * 
	 * @return	object
	 */
	public function pollDB() {
		return $this->pollDB;
	}
	
	
	/*************************************************************************/
	
	
	/**
	 * Display Poll
	 * Gives the HTML for the poll to display on the front-end
	 * 
	 * @param	array	$args
	 * @return	string
	 */
	public function displayPoll(array $args) {
		
		$limit = get_option('sp_limit');

		if( isset($args['id']) ) {
			$pollid		= $args['id'];
			$poll		= $this->grabPoll($pollid);
			
			if( isset($poll['question']) ) {
				$question	= stripcslashes($poll['question']);
				$answers	= $poll['answers'];
				
				foreach( $answers as $key => $answer ) {
					$answers[$key]['answer'] = stripcslashes($answer['answer']);
				}
				ob_start();

				$postFile = plugins_url(SP_SUBMIT, dirname(__FILE__));
				$thisPage = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

				include(SP_DIR.SP_DISPLAY);
				$content = ob_get_clean();
				return $content;
			}
			
		}
		
	}
	
	
	/*************************************************************************/
	
	
	/**
	 * Submit Poll
	 * Passes back the poll results to return a JSON feed of responses. Can
	 * also just pass back previous results without passing an answer.
	 * 
	 * @param	int		$pollID
	 * @param	int		$answer
	 * @return	JSON
	 */
	public function submitPoll($pollID, $answer=null) {
		
		$poll = $this->grabPoll($pollID); // Grab the current results
	
		// The user has provided an answer
		if( isset($answer) ) {
			
			$totalVotes = 0;
			
			// Update the count of the answer
			$current = $poll['answers'][$answer]['vote'];
			++$current;
			$poll['answers'][$answer]['vote'] = $current;


			// Count the total votes
			foreach($poll['answers'] as $key => $answer){
				$totalVotes = $totalVotes + $answer['vote'];
			}
			

			$poll['totalvotes'] = $totalVotes; // Update the total count

			$success = $this->pollDB->setPollDB($poll); // Push the results back to store
			
			$poll['voted'] = $answer; // User feedback on what they voted on
			
		}
	
		// the return value needs to be the location of the results file
		return SP_DIR.SP_RESULTS;
	}
	
	
	/*************************************************************************/


	/**
	 * Grab Poll
	 * Gets the current state of the the poll
	 *
	 * @param	int $id
	 * @return	array
	 */
	public function grabPoll($id=null) {
		
		$poll = $this->pollDB->getPollDB($id); // get the results from the the DB
		
		// If we set an ID then only return the single node
		if (isset($poll[0])) {
			$poll = $poll[0];
			$poll['answers'] = unserialize($poll['answers']);
		}
		
		return $poll;
	}
	

}