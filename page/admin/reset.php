<?php
	global $spAdmin;
	
	$pollDB = new SimplyPollDB();
	
	$id = (int)$_GET['id'];
	$poll = $spAdmin->grabPoll($id);
		
	if(isset($_POST['reset']) && $_POST['reset'] == 'Yes') {
		$pollDB->resetPoll($poll);
		$message = 'Poll reset';
		
	} elseif(isset($_POST['reset']) && $_POST['reset'] == 'No') {
		$message = 'All poll votes are still intact';
	}
	
?><div class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div> 
	<h2>
		Rest Poll
	</h2>
	
	<?php if(isset($message)) : ?>
		
		<script>
			setTimeout( "pageRedirect()", 3000 );
			
			function pageRedirect() {
				window.location.replace('<?php admin_url(); ?>admin.php?page=sp-view&id=<?php echo $id; ?>');
			}
		</script>
		
		<p><?php echo $message; ?></p>
		
		<p>Redirecting you back to "<?php echo $poll['question']; ?>" in 3 seconds...</p>
		
		<p><a href="<?php admin_url(); ?>admin.php?page=sp-poll" class="button">back</a> <a href="<?php admin_url(); ?>admin.php?page=sp-veiw&id=<?php echo $id; ?>" class="button">back</a></p>
		
	<?php else : ?>
	
		<?php if(!$poll) : ?>
			<p>There is no poll with the ID <strong><?php echo $id; ?></p>
			
			<p><a href="<?php admin_url(); ?>admin.php?page=sp-poll">Go back</a></p>
		<?php else : ?>
			
			<p>Are you sure you want to reset poll "<strong><?php echo $poll['question']; ?></strong>"?</p>
			
			<form method="post">
				
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<p><input type="submit" name="reset" class="button" value="Yes" /> <input type="submit" class="button" name="reset" value="No" /></p>
				
			</form>
		
		<?php endif; ?>
		
	<?php endif; ?>

</div>