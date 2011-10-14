<?php

	global $spAdmin;
	$poll = $spAdmin->grabPoll();

?>

<div class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div> 
	<h2>
		Simply Poll
		<a href="admin.php?page=sp-add" class="add-new-h2">Add New Poll</a>
	</h2>
	
	<?php if($poll['polls']) : ?>
		
		<ul class="polls">
			<?php foreach($poll['polls'] as $key => $poll) : ?>
				<?php if($poll !== 'deleted') : ?>
					<?php $id = $poll['id']; ?>
					<li>
						<strong><?php echo $poll['question']; ?></strong>
						<p>Shortcode: <code>[poll id='<?php echo $id; ?>']</code></p>
						<p class="center">
							<a href="admin.php?page=sp-view&amp;id=<?php echo $id; ?>" class="button">view</a>
							<a href="admin.php?page=sp-update&amp;id=<?php echo $id; ?>" class="button">update</a>
							<a href="admin.php?page=sp-resete&amp;id=<?php echo $id; ?>" class="button">reset</a>
							<a href="admin.php?page=sp-delete&amp;id=<?php echo $id; ?>" class="button">delete</a>
						</p>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		
	<?php else : ?>
		
		<p>No Polls have been made yet. <a href="admin.php?page=sp-add">Add a poll now</a>.</p>	
	
	<?php endif; ?>

</div>