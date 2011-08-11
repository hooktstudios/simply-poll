<?php
	global $spAdmin;
	$response = null;
	
	if($_GET['page'] == 'sp-add'){
		$poll = $_POST;
		$btnSubmit['name']		= 'addPoll';
		$btnSubmit['display']	= 'Add Poll';
		
	} elseif($_GET['page'] == 'sp-edit') {
		$id		= (int)$_GET['id'];
		$poll	= $spAdmin->grabPoll($id);
		$btnSubmit['name']		= 'editPoll';
		$btnSubmit['display']	= 'Update Poll';
		$pollEdit = true;
		
	}

	if(isset($_POST['question'])){
		$spAdmin->setEdit($_POST);
		$return		= $spAdmin->getEdit();
		$poll 		= $spAdmin->grabPoll($return['poll']['id']);
		$response	= $return['response'];
	} else{
		$response = null;
	}

?><div class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div> 
	<h2>
		Add Poll
	</h2>
	
	<?php if(isset($pollEdit)) : ?>
		<p>
			Added: <?php echo date("F j, Y, g:i a", $poll['added']); ?><br />
			Updated: <?php echo date("F j, Y, g:i a", $poll['updated']); ?>
		</p>
	<?php endif; ?>
	

	<?php if($response == 'success') : ?>
		
		<p>Your new poll has been added!</p>
		<p><a href="<?php admin_url(); ?>admin.php?page=sp-poll">Go back</a></p>
	
	<?php else : ?>
	
		<p><?php echo $response; ?></p>
		
		<form method="post">
			
			<p>
				<h2><label for="question">Question</label></h2>
				<input type="text" name="question" size="50" tabindex="1" id="question" autocomplete="off" value="<?php
					if(isset($pollEdit))
						echo $poll['question'];
				?>"/>
			</p>
			
			<fieldset id="answers">
				
				<legend><h2>Answers</h2></legend>
				<ol>
					<input type="hidden" name="id" value="<?php echo $poll['id']; ?>" />
					<?php 
						if(isset($poll['answers'])) :
							
							foreach($poll['answers'] as $key => $aData) :
					?>
						<li>
							<input type="text" name="answers[<?php echo $key; ?>][answer]" value="<?php echo stripcslashes($aData['answer']); ?>" /> 
							<a href="#" class="sp-remove">remove</a><br />
							Votes: <strong><?php echo $aData['vote']; ?></strong>
							<?php if(isset($aData['vote'])) : ?>
								<input type="hidden" name="answers[<?php echo $key; ?>][vote]" value="<?php echo $aData['vote']; ?>" />
							<?php endif; ?>
						</li>
					<?php
							endforeach;
						else :
					?>
						<li><input type="text" name="answers[1][answer]" value="" /></li>
						<li><input type="text" name="answers[2][answer]" value="" /></li>
						<li><input type="text" name="answers[3][answer]" value="" /></li>
						<li><input type="text" name="answers[4][answer]" value="" /></li>
						<li><input type="text" name="answers[5][answer]" value="" /></li>
						<li><input type="text" name="answers[6][answer]" value="" /></li>
						<li><input type="text" name="answers[7][answer]" value="" /></li>
						<li><input type="text" name="answers[8][answer]" value="" /></li>
						<li><input type="text" name="answers[9][answer]" value="" /></li>
					<?php
						endif;
					?>
				</ol>
				
			</fieldset>
			
			<p><button type="submit" name="<?php echo $btnSubmit['name']; ?>" value="true" class="button-primary"><?php echo $btnSubmit['display']; ?></p>
			
		</form>
	<?php endif; ?>
	
</div>