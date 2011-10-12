<?php
	global $spAdmin;
	$response	= null;
	$formData	= array();
	
	if( $_GET['page'] == 'sp-add' ) {
		
		$pollAdd				= true;
		
		$poll					= $_POST;
		$formData['name']		= 'addPoll';
		$formData['display']	= 'Add Poll';
		
		
	} elseif( $_GET['page'] == 'sp-edit' ) {
		
		$pollEdit				= true;
		
		$id						= (int)$_GET['id'];
		
		$poll					= $spAdmin->grabPoll($id);
		$formData['name']		= 'editPoll';
		$formData['display']	= 'Edit Poll';
		
	}

	echo '<pre>'; print_r($_POST); echo '</pre>';

	if( isset($_POST['pollsubmitted']) ) {
		
		$poll					= $spAdmin->setEdit($_POST);
		
	echo '<pre>'; print_r($poll); echo '</pre>';
		
	}
	
?><div class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div> 
	<h2><?php echo $formData['display']; ?></h2>
	
	<?php 
//		Messages that are returned from the add/update script 
	
		if( isset($poll['error']) ) {
			foreach( $poll['error'] as $error ) {
				echo '<p class="error">'.$error.'</p>';
			}
			
		} elseif( isset($poll['response']) ) {
			foreach( $poll['response'] as $response ) {
				echo '<p class="response">'.$response.'</p>';
			}
			
		}
	?>
	
	<?php if( isset($pollEdit) ) : ?>
		<p>
			Added: <?php echo date("F j, Y, g:i a", $poll['added']); ?><br />
			Updated: <?php echo date("F j, Y, g:i a", $poll['updated']); ?>
		</p>
	<?php endif; ?>
	

	<?php if( $response == 'success' ) : ?>
		
		<p>Your new poll has been added!</p>
		<p><a href="<?php admin_url(); ?>admin.php?page=sp-poll">Go back</a></p>
	
	<?php else : ?>
	
		<p><?php echo $response; ?></p>
		
		<form method="post">
			
			<p>
				<h2><label for="question">Question</label></h2>
				<input type="text" name="question" size="50" id="question" value="<?php
					if( isset($poll['question']) )
						echo stripcslashes($poll['question']);
				?>"/>
			</p>
			
			<fieldset id="answers">
				
				<?php
					if( isset($poll['id']) ){
						echo '<input type="hidden" name="id" value="'.$poll['id'].'" />';
					}
				?>
				
				<legend><h2>Answers</h2></legend>
				<ol>
					<?php 
						if( isset($poll['answers']) && isset($pollEdit) ) :
							foreach($poll['answers'] as $key => $aData) :
					?>
						<li>
							<input type="text" name="answers[<?php echo $key; ?>][answer]" value="<?php echo stripcslashes($aData['answer']); ?>" /> 
							Votes: <strong><?php echo $aData['vote']; ?></strong>
							<?php if(isset($aData['vote'])) : ?>
								<input type="hidden" name="answers[<?php echo $key; ?>][vote]" value="<?php echo $aData['vote']; ?>" />
							<?php endif; ?>
						</li>
					<?php
							endforeach;
						else :
					?>
					<?php
						for( $i=1; $i<10; ++$i ) {
							
							if( isset($poll['answers'][$i]['answer']) ){
								$answer = $poll['answers'][$i]['answer'];
							} else {
								$answer = null;
							}
							
							echo '<li><input type="text" name="answers['.$i.'][answer]" value="'.$answer.'" /></li>';
						}
					?>
					<?php
						endif;
					?>
				</ol>
				
			</fieldset>
			
			<input type="hidden" name="pollsubmitted" value="true" />
			
			<p><button type="submit" name="<?php echo $formData['name']; ?>" value="true" class="button-primary"><?php echo $formData['display']; ?></p>
			
		</form>
	<?php endif; ?>
	
</div>