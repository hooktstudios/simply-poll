<div class="poll" id="poll-<?php echo $pollid; ?>">
	<p class="question"><?php echo $question; ?></p>
	
	<form method="post" action="<?php echo $postFile; ?>">
	
		<input type="hidden" name="poll" value="<?php echo $pollid; ?>" />
		<input type="hidden" name="backurl" value="<?php echo $thisPage; ?>" />
		
		<?php 
			if(
				(
					$limit == 'yes' && isset($_COOKIE['sptaken']) && 
					in_array($args['id'], unserialize($_COOKIE['sptaken']))
				) || 
				isset($_GET['simply-poll-return']) 
			) :
			
				require(SP_URI.'page/user/poll-results.php');
			
			else : 
		?>
			
			<fieldset>
				<ul>
				<?php foreach($answers as $key => $answers) : ?>

					<li>
						<input type="radio" name="answer" value="<?php echo $key; ?>" id="poll-<?php echo $pollid; ?>-<?php echo $key; ?>" />
						<label for="poll-<?php echo $pollid; ?>-<?php echo $key; ?>">
							<?php echo $answers['answer']; ?>
						</label>
					</li>
					
				<?php endforeach; ?>
				</ul>
			</fieldset>
		
			<p><button><?php _e('Vote'); ?></button></p>
			
		<?php endif; ?>
		
	</form>
</div>