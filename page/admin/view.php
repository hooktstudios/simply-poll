<?php

	global $spAdmin;
	
	$id			= (int)$_GET['id'];
	$poll		= $spAdmin->grabPoll($id);
	$question	= $poll['question'];
	$answers	= $poll['answers'];

?>
<div class="wrap">
	<div id="icon-edit-comments" class="icon32"><br /></div> 
	<h2><?php echo $question; ?></h2>
	
	<dl>
		<?php foreach($answers as $key => $aData) : ?>
			<dt><?php echo $aData['answer']; ?></dt>
			<dd><?php echo $aData['vote']; ?></dd>
		<?php endforeach; ?>
	</dl>
</div>