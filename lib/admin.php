<?php

class SimplyPollAdmin extends SimplyPoll{
	
	private $pollEdit;
	
	public function __construct(){
		
		parent::__construct(false);
		
		// Establish our DB class
//		$this->pollDB = new SimplyPollDB();
		
		add_action('admin_init',	array($this, 'enqueueFiles'));
		add_action('admin_menu',	array($this, 'addSimplyPollMenu'));
		
	}
	
	public function enqueueFiles() {		
		wp_enqueue_script('validator',			plugins_url('/script/validator.min.js',			SP_FILE),	false,	SP_VERSION);
		wp_enqueue_script('jSimplyPollAdmin',	plugins_url('/script/simplypoll-admin.js',		SP_FILE),	false,	SP_VERSION);
		wp_enqueue_script('jqPlotMain',			plugins_url('/script/jqplot.min.js',			SP_FILE),	false,	SP_VERSION);
		wp_enqueue_script('jqPlotPie',			plugins_url('/script/jqplot.pieRenderer.js',	SP_FILE),	false,	SP_VERSION);
		
		wp_register_style('spAdminCSS',			plugins_url('/css/admin.css',					SP_FILE),	false,	SP_VERSION);
		
		wp_enqueue_style('jqplotcss');
		wp_enqueue_style('spAdminCSS');
	}

	/**
	 * Add menu items to admin
	 */
	public function addSimplyPollMenu() {
		
		$capability = 'manage_options';
		$parentPage = 'sp-poll';
		
		add_menu_page('Simply Poll', 'Polls', $capability, $parentPage, array($this, 'getAdminPageMain'),'', 6);
		
		add_submenu_page($parentPage,	'Settings',		'Settings',		$capability,	'sp-settings',	array($this, 'getAdminPageSettings'));
		add_submenu_page($parentPage,	'Add New Poll', 'Add New',		$capability,	'sp-add',		array($this, 'getAdminPageAdd'));
		add_submenu_page('',			'View Poll',	'View Poll',	$capability,	'sp-view',		array($this, 'getAdminPageView'));
		add_submenu_page('',			'Update Poll',	'Update Poll',	$capability,	'sp-update',	array($this, 'getAdminPageUpdate'));
		add_submenu_page('',			'Delete Poll',	'Delete Poll',	$capability,	'sp-delete',	array($this, 'getAdminPageDelete'));
		add_submenu_page('',			'Reset Poll',	'Reset Poll',	$capability,	'sp-reset',		array($this, 'getAdminPageReset'));
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
	public function getAdminPageUpdate(){
		require(SP_DIR.'/page/admin/edit.php');
	}
	public function getAdminPageDelete(){
		require(SP_DIR.'/page/admin/delete.php');
	}
	public function getAdminPageReset(){
		require(SP_DIR.'/page/admin/reset.php');
	}
	
	
	/**
	 * Add or Update a Poll
	 * 
	 * @param	array	$pollData
	 * @return	array
	 *************************************************************************/
	public function setEdit($pollData){
		
		$question		= $pollData['question'];
		$answers		= $pollData['answers'];
		$posted			= $pollData;
		$countAnswers	= 0;
		$error			= array();
		$newPoll		= false;
		$editPoll		= false;
		
		// Check to see if all required fields are entered
		
		
		// Does question have a value?
		if( $question ) {
			
			$pollForDB['question'] = $question;
			$pollForDS['question'] = htmlspecialchars( stripcslashes($question), ENT_QUOTES, get_bloginfo('charset') );
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
					
					$pollForDB['answers'][$cntAnswers]['answer']	= htmlspecialchars( stripcslashes($answer['answer']), ENT_QUOTES, get_bloginfo('charset') );
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
			
			$return = array();
			
			$pollID = $this->pushPollToDB($pollForDB, $pollData['polledit']);
			
			if( $pollID > 0 ){
				if ($pollData['polledit'] == 'new') {
					$return['success'] = 'New Poll Added';
				} elseif($pollData['polledit'] > 0) {
					$return['success'] = 'Poll Updated';
				}
				$return['pollid'] = $pollID;
			} else {
				$return['error'] = 'adding to the DB failed';
			}
			
			$pollForDS['return'] = $return;
			
			return $pollForDS;
			
		} else {
			
			$pollForDS['error'] = $error;
			return $pollForDS;
			
		}
			
		
	}

	
	
	/**
	 * Add New Poll to DB
	 * 
	 * @param	array	$poll
	 * @param	array	$editPoll
	 * @return	int
	 *************************************************************************/
	private function pushPollToDB($poll, $pollEdit){
		
		$pollData	= parent::pollDB()->getPollDB();
		
		$poll['active']		= true;
		$poll['totalvotes']	= 0;
		$poll['time']		= time();
		
		$pollData['polls'][] = $poll;
		
		if( $pollEdit == 'new' ) {
			// Add new poll
			return parent::pollDB()->newPollDB($poll);
			
		} elseif( $pollEdit > 0 ) {
			// Update poll
			$poll['id'] = $pollEdit;
			return parent::pollDB()->updatePollDB($poll);
		}
	}
}
