<?php

class SimplyPollAdmin extends SimplyPoll{
	
	private $pollEdit;
	
	public function __construct(){
		
		add_action('admin_menu',	array($this, 'addSimplyPollMenu'));
		add_action('admin_init',	array($this, 'enqueueFiles'));
		
	}

	/**
	 * Add menu items to admin
	 */
	public function addSimplyPollMenu() {
		$capability = 'manage_options';
		$parentPage = 'poll';
		
		add_menu_page('Simply Poll', 'Polls', $capability, $parentPage, array($this, 'getAdminPageMain'),'', 6);
		
		add_submenu_page($parentPage, 'Add New Poll', 'Add New', $capability, 'poll-add', array($this, 'getAdminPageAdd'));
		add_submenu_page('', 'View Poll', 'View Poll', $capability, 'poll-view', array($this, 'getAdminPageView'));
		add_submenu_page('', 'Edit Poll', 'Edit Poll', $capability, 'poll-edit', array($this, 'getAdminPageEdit'));
		add_submenu_page('', 'Delete Poll', 'Delete Poll', $capability, 'poll-delete', array($this, 'getAdminPageDelete'));
	}	
	
	
	public function getAdminPageMain(){
		require(SP_DIR.'/page/admin/main.php');
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
	
	
	/**
	 * Add New Poll
	 * 
	 * @param	$pollEdit
	 */
	public function setEdit($pollData){
			
		$question		= $pollData['question'];
		$answers		= $pollData['answers'];
		$posted			= $pollData;
		$countAnswers	= 0;
		$newPoll		= false;
		$editPoll		= false;
		
		// Find out what we are doing: adding or editing
		if($pollData['addPoll']) {
			$addPoll	= true;
			unset($pollData['addPoll']);
			
		} elseif($pollData['editPoll']) {
			$editPoll	= true;
			unset($pollData['editPoll']);
		}
		
		
		// Check to see if all required fields are entered
		
		// Does question have a value?
		if($question){
			
			foreach($answers as $key => $aData) {
				
				// Unset either way to clean the array
				unset($pollData['answers'][$key]); 
				
				if($aData['answer']){
					// We have an answer so build that back into the array with new values
					++$countAnswers;
					
					if(isset($aData['vote'])){
						$vote = $aData['vote'];
					} else {
						$vote = 0;
					}
					
					$pollData['answers'][$key]['answer']	= $aData['answer'];
					$pollData['answers'][$key]['vote']		= $vote;
				}
				
			}
			
			
			if($countAnswers > 1) {
				$poll = $pollData;
				
				if($addPoll){
					$poll['added']	= time();
					$poll['active']	= true;
					unset($poll['addPoll']);
					
				} else {
					unset($poll['editPoll']);
					
				}
				
				$poll['updated'] = time();
				
			} else {
				// I like humor, what of it?
				if($countAnswers == 1){
					$error = 'Not enough answers; a question isn\'t a question with one answer!';
				} else {
					$error = 'No answers; what the sound of one tree clapping?';
				}
			}
			
		} else {
			$error = 'No question given; how can somebody answer this question, this ain\'t Jepoardy!';
		}
		
		if(isset($error)) {
			$return = $error;
		
		} else {
			if($addPoll) {
				if($this->addPollToDB($poll)) {
					$return	= 'success';
				} else {
					$return = 'adding to the DB failed';
				}
				
			} elseif($editPoll) {
			
			}
		}
		
		$this->pollEdit['poll']		= $posted;
		$this->pollEdit['response']	= $return;
		
	}
	/**
	 * Add New Poll Return
	 * 
	 * @return	string
	 */
	public function getEdit(){
		return $this->pollEdit;
	}
	
	public function deletePoll($id){
		$pollData = parent::getPollDB();
		unset($pollData['polls'][$id]);
		$pollData['polls'][$id] = 'deleted';
		parent::setPollDB($pollData);
		
		return true;
	}	
	
	/**
	 * Add New Poll to DB
	 * 
	 * @param	$poll
	 * @return	bool
	 */
	private function addPollToDB($poll){
		
		$pollData	= parent::getPollDB();
		
		$pollData['polls'][] = $poll;
		
		return parent::setPollDB($pollData);
	}
	
	
	public function enqueueFiles(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jSimplyPollAdmin', plugins_url('/js/simplypoll-admin.js', SP_FILE));
	}
}
