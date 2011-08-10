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
	 * Save poll data to DB when voting
	 *
	 * @param	$pollData
	 * @return	bool
	 */
	public function votePollDB(array $pollData){
		echo 'Voted on the poll, updating db now';
	}

	/**
	 * Save poll data to DB when updating a poll
	 *
	 * @param	$pollData
	 * @return	bool
	 */
	public function updatePollDB(array $pollData){
	
		// NEEDS EDITTING FOR THE ID
		global $wpdb;
		
		$wpdb->query("UPDATE `".SP_TABLE."` SET `question`='".$pollData['question']."', `answers`='".mysql_escape_string(serialize($pollData['answers']))."', `active`='".$pollData['active']."', `totalvotes`='".$pollData['totalvotes']."', `updated`='".$pollData['updated']."' WHERE `id`='2'");
	}
		
	/**
	 * Save poll data to DB for a new poll
	 * 
	 * @param	$pollData
	 * @return	bool
	 */
	public function newPollDB(array $pollData){
		global $wpdb;
		
		$wpdb->query("INSERT INTO `".SP_TABLE."` (`question`, `answers`, `added`, `active`, `totalvotes`, `updated`) VALUES ('".$pollData['question']."', '".mysql_escape_string(serialize($pollData['answers']))."', '".$pollData['added']."', '".$pollData['active']."', '".$pollData['totalvotes']."', '".$pollData['updated']."')");
	}
	
	/**
	 * Delete poll data from DB
	 * 
	 * @param	$pollData
	 * @return	bool
	 */
	public function deletePollDB(array $pollData){
		echo 'Deleted poll, updating db now';
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
				$pollData = array();
			}
			$this->pollData = $pollData;
			return $pollData;
		}
		
		global $wpdb;
		
		$wpdb->get_results("");
	}

}
