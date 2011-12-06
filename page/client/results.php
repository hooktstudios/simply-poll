<?php
	if( isset($_POST['pollid']) ) {
		$pollid = $_POST['pollid'];
		
	} else {
		wp_die(SP_DIRECT_ACCESS);
	}
	
	$simplypoll	= new SimplyPoll(false);
	$results	= $simplypoll->grabPoll($pollid);
	$answers	= $results['answers'];
	$totalvotes	= $results['totalvotes'];
?>

<dl class="results">
	<?php foreach($answers as $key => $answer) : ?>
		
		<?php $percentage = round((int)$answer['vote'] / (int)$totalvotes * 100); ?>
		
		<dt><?php echo $answer['answer']; ?></dt>
		<dd style="width:<?php echo $percentage; ?>%"><?php echo $percentage; ?>%</dd>
		
	<?php endforeach; ?>
</dl>

<p><?php _e('Total votes'); ?>: <?php echo $totalvotes; ?></p>