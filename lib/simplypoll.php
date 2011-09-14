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
		$limit = get_option('sp_limit');

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
		$poll = $this->grabPoll($pollID);

		if(isset($vote)){
			$current = (int)$poll['answers'][$vote]['vote'];
			++$current;
			$poll['answers'][$vote]['vote'] = $current;

			$totalVotes = 0;

			foreach($poll['answers'] as $key => $aData){
				$totalVotes = $totalVotes + $aData['vote'];
			}

			$poll['totalvotes'] = $totalVotes;

			$success = $this->setPollDB($poll);
			$poll['voted'] = $vote;
		}

		return json_encode($poll);

	}


	/**
	 * Grab Poll Info
	 *
	 * @param	$id
	 * @return	array
	 */
	public function grabPoll($id=null){
		$poll = $this->getPollDB($id);
		if (isset($poll[0])) {
			$poll = $poll[0];
			$poll['answers'] = unserialize($poll['answers']);
		}
		return $poll;
	}


	/**
	 * Save poll data to DB
	 *
	 * @param	$pollData
	 * @return	bool
	 */
	public function setPollDB(array $pollData){
		global $wpdb;
		
		$answers = serialize($pollData['answers']);
		$j = $wpdb->query("UPDATE `".SP_TABLE."` SET `answers`='".$answers."', `totalvotes`='".$pollData['totalvotes']."' WHERE `id`='".$pollData['id']."'");
		
		if ($j == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Save poll data to DB when updating a poll
	 *
	 * @param	$pollData
	 * @return	bool
	 */
	public function updatePollDB(array $pollData){
		global $wpdb;
		
		$j = $wpdb->query("UPDATE `".SP_TABLE."` SET `question`='".$pollData['question']."', `answers`='".mysql_escape_string(serialize($pollData['answers']))."', `updated`='".$pollData['updated']."' WHERE `id`='".$pollData['id']."'");
		
		if ($j == 1) {
			return true;
		} else {
			return false;
		}
	}
		
	/**
	 * Save poll data to DB for a new poll
	 * 
	 * @param	$pollData
	 * @return	bool
	 */
	public function newPollDB(array $pollData){
		global $wpdb;
		
		$j = $wpdb->query("INSERT INTO `".SP_TABLE."` (`question`, `answers`, `added`, `active`, `totalvotes`, `updated`) VALUES ('".$pollData['question']."', '".mysql_escape_string(serialize($pollData['answers']))."', '".$pollData['added']."', '".$pollData['active']."', '".$pollData['totalvotes']."', '".$pollData['updated']."')");
		
		if ($j == 1) {
			return true;
		} else {
			return false;
		}
		
	}

	/**
	 * Grab poll data from DB
	 *
	 * @return	array
	 */
	public function getPollDB($id=null){
	
		global $wpdb;
		
		if (isset($id)) {
			$poll = $wpdb->get_results("SELECT * FROM `".SP_TABLE."` WHERE `id`='".$id."' LIMIT 1", ARRAY_A);
			return $poll;
		} else {

			if($this->pollData){
				return $this->pollData;
	
			} else {
				$polls['polls'] = $wpdb->get_results("SELECT `id`, `question` FROM `".SP_TABLE."` ORDER BY `id` ASC", ARRAY_A);
				
				if(!is_array($polls)){
					$polls = array();
				}
				$this->pollData = $polls;
				return $polls;
			}
		}
	}

}
