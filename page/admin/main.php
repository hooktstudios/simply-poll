<?php

	global $spAdmin;
	$poll = $spAdmin->grabPoll();

?>

<div class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div> 
	<h2>
		Simply Poll
		<a href="admin.php?page=poll-add" class="add-new-h2">Add New Poll</a>
	</h2>
	
	<?php if($poll['polls']) : ?>
		
		<p>&nbsp;</p>
		
		<ol>
			<?php foreach($poll['polls'] as $key => $poll) : ?>
				<?php if($poll !== 'deleted') : ?>
					<li>
						<strong><?php echo $poll['question']; ?></strong> -
						<a href="admin.php?page=poll-view&amp;id=<?php echo $poll['id']; ?>">view</a> | <a href="admin.php?page=poll-edit&amp;id=<?php echo $poll['id']; ?>">edit</a> | <a href="admin.php?page=poll-delete&amp;id=<?php echo $poll['id']; ?>">delete</a><br />
						Shortcode: <code>[poll id="<?php echo $poll['id']; ?>"]</code>
						<p>&nbsp;</p>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ol>
		
	<?php else : ?>
		
		<p>No Polls have been made yet. <a href="admin.php?page=poll-add">Add a poll now</a>.</p>	
	
	<?php endif; ?>

</div>