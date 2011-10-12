<style>
	.poll label{
		display: block;
	}
	.poll dd{
		border-top:	2px solid #333;
		display: block;
	}
</style>

<div class="poll" id="poll-<?php echo $id; ?>">
	<p class="question"><?php echo $question; ?></p>
	
	<form method="post" action="<?php echo SP_URL; ?>page/user/poll-submit.php">
	
		<input type="hidden" name="poll" value="<?php echo $id; ?>" />
		<input type="hidden" name="backurl" value="<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
		
		<?php if( ($limit == 'yes' && isset($_COOKIE['sptaken']) && in_array($args['id'], unserialize($_COOKIE['sptaken']))) || isset($_GET['simply-poll-return']) ) : ?>
			
			<?php
				$pollid = $id;
				require(SP_URI.'page/user/poll-results.php');
			?>
			
		<?php else : ?>
			
			<fieldset>
				<?php foreach($answers as $key => $answers) : ?>

					<label>
						<input type="radio" name="answer" value="<?php echo $key; ?>" />
						<?php echo $answers['answer']; ?>
					</label>
					
				<?php endforeach; ?>
			</fieldset>
		
			<p><button><?php echo __('Vote'); ?></button></p>
			
		<?php endif; ?>
		
	</form>
</div>