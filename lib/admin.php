<?php

class SimplyPollAdmin extends SimplyPoll{
	
	private $pollEdit;
	
	public function __construct(){
		
		parent::__construct(false);
		add_action('admin_menu',	array($this, 'addSimplyPollMenu'));
		add_action('admin_init',	array($this, 'enqueueFiles'));
		
	}
	
	public function enqueueFiles(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jSimplyPollAdmin', plugins_url('/js/simplypoll-admin.js', SP_FILE));
	}

	/**
	 * Add menu items to admin
	 */
	public function addSimplyPollMenu() {
		$capability = 'manage_options';
		$parentPage = 'sp-poll';
		
		add_menu_page('Simply Poll', 'Polls', $capability, $parentPage, array($this, 'getAdminPageMain'),'', 6);
		
		add_submenu_page($parentPage, 'Settings', 'Settings', $capability, 'sp-settings', array($this, 'getAdminPageSettings'));
		
		add_submenu_page($parentPage, 'Add New Poll', 'Add New', $capability, 'sp-add', array($this, 'getAdminPageAdd'));
		add_submenu_page('', 'View Poll', 'View Poll', $capability, 'sp-view', array($this, 'getAdminPageView'));
		add_submenu_page('', 'Edit Poll', 'Edit Poll', $capability, 'sp-edit', array($this, 'getAdminPageEdit'));
		add_submenu_page('', 'Delete Poll', 'Delete Poll', $capability, 'sp-delete', array($this, 'getAdminPageDelete'));
	}	
	
	
	public function getAdminPageMain(){
		require(SP_DIR.'/page/admin/main.php');
	}
	public function getAdminPageSettings() {
		require(SP_DIR.'/page/admin/settings.php');
	}
	public function getAdminPageView(){
		require(SP_DIR.'/page/admin/view.php');
	}
	public function getAdminPageAdd(){
		require(SP_DIR.'/page/admin/edit.php');
	}
	public function getAdminPageEdit(){
		require(SP_DIR.'/page/admin/edit.php');
	}
	public function getAdminPageDelete(){
		require(SP_DIR.'/page/admin/delete.php');
	}
	
	
	/**************************************************************************
	 * Add or Edit a Poll
	 * 
	 * @param	array	$pollData
	 * @return	array
	 */
	public function setEdit($pollData){
		
		$question		= $pollData['question'];
		$answers		= $pollData['answers'];
		$posted			= $pollData;
		$countAnswers	= 0;
		$error			= array();
		$newPoll		= false;
		$editPoll		= false;
		
		// Find out what we are doing: adding or editing
		if(isset($pollData['addPoll'])) {
			$addPoll	= true;
			unset($pollData['addPoll']);
			
		} elseif(isset($pollData['editPoll'])) {
			$editPoll	= true;
			unset($pollData['editPoll']);
		}
		
		
		// Check to see if all required fields are entered
		
		
		// Does question have a value?
		if( $question ) {
			
			$pollForDB['question'] = $question;
			$pollForDS['question'] = stripcslashes($question);
			unset($pollData['question']);
			
		} else {
			$error[] = 'No question given';
		}
		
		
		// Do we have answers
		if( $answers ) {
			
			$cntAnswers = 0;
			
			// Sort the data out
			foreach($answers as $key => $answer) {
				
				// Unset either way to clean the array
				unset($pollData['answers'][$key]); 
					
				if($answer['answer']){
					// We have an answer so build that back into the array with new values
					++$cntAnswers;
					
					// Add vote node if not there already
					if(isset($answer['vote'])){
						$vote = $answer['vote'];
					} else {
						$vote = 0;
					}
					
					$pollForDB['answers'][$cntAnswers]['answer']	= $answer['answer'];
					$pollForDB['answers'][$cntAnswers]['vote']		= $vote;
					$pollForDS['answers'][$cntAnswers]['answer']	= stripcslashes($answer['answer']);
					$pollForDS['answers'][$cntAnswers]['vote']		= $vote;
				}
					
			}
			
			// Quick clean of the array node
			unset($pollData['answers']);
			
			// Do we have enough answers
			if( $cntAnswers <= 1 ) {
				$error[] = 'Need at least 2 answers';
			}
			
		} else {
			$error[] = 'No answers given';
		}
		
			
		// If we have no error then all good to go
		if( count($error) == 0 ) {
			
			if( isset($addPoll) ) {
				
				$pollID = $this->addPollToDB($pollForDB, false);
				if($pollID) {
					header('/admin.php?page=sp-edit&id='.$pollID);
				} else {
					$error	= 'adding to the DB failed';
				}
				
			} else {
				unset($poll['editPoll']);
			}
			
			$poll['updated'] = time();
			
			
			return $pollForDS;
			
		} else {
			
			$pollForDS['error'] = $error;
			return $pollForDS;
			
		}
			
		
	}

	
	/**************************************************************************
	 * Delete Poll
	 * 
	 * $param	int	$id
	 */
	public function deletePoll($id){
		global $wpdb;
		$wpdb->query('
			DELETE FROM 
				`'.SP_TABLE.'` 
			WHERE `id`="'.$id.'"
		');
		return true;
	}	
	
	
	/**************************************************************************
	 * Add New Poll to DB
	 * 
	 * @param	$poll
	 * @return	bool or int
	 */
	private function addPollToDB($poll, $editPoll){
		
		$pollData	= parent::getPollDB();
		
		$poll['added']		= time();
		$poll['active']		= true;
		$poll['totalvotes']	= 0;
		
		$pollData['polls'][] = $poll;
		
		if ($editPoll == true) {
			// New database edit
			return parent::updatePollDB($poll);
		} else {
			// New database add
			return parent::newPollDB($poll);
		}
	}
}
