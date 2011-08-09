<?php

class SimplyPoll{
	
	public $pollData;
	
	public function __construct(){
		global $wp_scripts;
		wp_enqueue_script('jquery');
		if (!in_array('jquery', $wp_scripts->done) && !in_array('jquery', $wp_scripts->in_footer)) {
			$wp_scripts->in_footer[] = 'jquery';
		}
		wp_enqueue_script('jSimplyPoll', plugins_url('/js/simplypoll.js', SP_FILE));
		if (!in_array('jSimplyPoll', $wp_scripts->done) && !in_array('jSimplyPoll', $wp_scripts->in_footer)) {
			$wp_scripts->in_footer[] = 'jSimplyPoll';
		}
	}
	
	
	public function displayPoll($args){
		
		if(isset($args['id'])){
			$id			= $args['id'];
			$poll		= $this->grabPoll($id);
			$question	= $poll['question'];
			$answers	= $poll['answers'];
			
			$data = include(SP_DIR.'page/user/poll-display.php');
			
			return $data;
		}
	}	
	
	public function submitPoll($pollID, $vote=null){
		$polls = $this->grabPoll();
				
		if($vote){
			$current = (int)$polls['polls'][$pollID]['answers'][$vote]['vote'];
			++$current;
			$polls['polls'][$pollID]['answers'][$vote]['vote'] = $current;
			
			$totalVotes = 0;
			
			foreach($polls['polls'][$pollID]['answers'] as $key => $aData){
				$totalVotes = $totalVotes + $aData['vote'];
			}
			
			$polls['polls'][$pollID]['totalvotes'] = $totalVotes;
			
			$success = $this->setPollDB($polls);
			$polls['voted'] = $vote;
		}
		
		return json_encode($polls['polls'][$pollID]);

	}
	
	
	/**
	 * Grab Poll Info
	 * 
	 * @param	$id
	 * @return	array
	 */
	public function grabPoll($id=null){
		$poll = $this->getPollDB();
		
		if($id !== null){
			return $poll['polls'][$id];
		} else {
			return $poll;
		}
	}
	
	
	/**
	 * Save poll data to DB
	 * 
	 * @param	$pollData
	 * @return	bool
	 */
	public function setPollDB(array $pollData){
		
		$serialized = serialize($pollData);
		return update_option('simplyPoll', $serialized);
	}
	
	
	/**
	 * Grab poll data from DB
	 * 
	 * @return	array
	 */
	public function getPollDB(){
		
		if($this->pollData){
			return $this->pollData;
			
		} else {
			$seralized = get_option('simplyPoll', false);
			if($seralized){
				$pollData = unserialize($seralized);
			} else {
				$PollData = array();
			}
			$this->pollData = $pollData;
			return $pollData;
		}
	}
	
}
