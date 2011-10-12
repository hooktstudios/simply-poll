<?php

class SimplyPollDB {
	
	
	private $pollData;
	
	public function __construct() {
		
	}
	
	
	/**
	 * Save poll data to DB for a new poll
	 * 
	 * @param	array	$pollData
	 * @return	bool
	 *************************************************************************/
	public function newPollDB(array $pollData) {
		global $wpdb;
		
		$sql = '
			INSERT INTO 
				`'.SP_TABLE.'` (
					`question`,
					`answers`,
					`added`,
					`active`,
					`totalvotes`,
					`updated`
				) 
				VALUES (
					"'.$pollData['question'].'",
					"'.mysql_escape_string(serialize($pollData['answers'])).'",
					"'.$pollData['added'].'",
					"'.$pollData['active'].'",
					"'.$pollData['totalvotes'].'",
					"'.$pollData['updated'].'"
				)
		';
			
		if( $wpdb->query($sql) ) {
			return $wpdb->insert_id;
		} else {
			return false;
		}
		
	}
	
	
	
	/**
	 * Grab poll data from DB
	 *
	 * @param	int		$id
	 * @return	array
	 *************************************************************************/
	public function getPollDB($id=null) {
		global $wpdb;
		
		if (isset($id)) {
			$sql = '
				SELECT
					*
				FROM
					`'.SP_TABLE.'`
				WHERE
					`id`	= '.$id.'
				LIMIT
					1
			';
			return $wpdb->get_results($sql, ARRAY_A);
			
		} else {

			if($this->pollData){
				return $this->pollData;
	
			} else {
				
				$sql = '
					SELECT 
						`id`, 
						`question` 
					FROM
						`'.SP_TABLE.'`
					ORDER BY 
						`id` ASC
				';
				
				$polls['polls'] = $wpdb->get_results($sql, ARRAY_A);
				
				if(!is_array($polls)){
					$polls = array();
				}
				$this->pollData = $polls;
				return $polls;
			}
		}
	}


	/**
	 * Save poll data to DB when updating a poll
	 *
	 * @param	$pollData
	 * @return	bool
	 *************************************************************************/
	public function updatePollDB(array $pollData) {
		global $wpdb;
		
		$sql = '
			UPDATE 
				`'.SP_TABLE.'` 
			SET 
				`question`	= \''.$pollData['question'].'\',
				`answers`	= \''.mysql_escape_string(serialize($pollData['answers'])).'\', 
				`updated`	= \''.$pollData['updated'].'\'
			WHERE 
				`id`		= '.$pollData['id'].'
		';
		
		return $wpdb->query($sql);
	}
	
	
	/**
	 * Save poll data to DB
	 *
	 * @param	$pollData
	 * @return	bool
	 *************************************************************************/
	public function setPollDB(array $pollData) {
		global $wpdb;
		
		$answers = serialize($pollData['answers']);
		
		$sql = '
			UPDATE 
				`'.SP_TABLE.'` 
			SET 
				`answers`		= \''.$answers.'\', 
				`totalvotes`	= \''.$pollData['totalvotes'].'\' 
			WHERE 
				`id`			= '.$pollData['id'].'
		';
		
		return $wpdb->query($sql);
	}
	
	
	/**
	 * Delete Poll
	 * 
	 * @param	int	$id
	 * @return	bool
	 *************************************************************************/
	public function deletePoll($id) {
		global $wpdb;
		
		$sql = '
			DELETE FROM 
				`'.SP_TABLE.'` 
			WHERE `id`			= '.$id.'
		';
		
		return $wpdb->query($sql);
	}	
	
}
