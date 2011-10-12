<?php
	if( isset($_POST['pollid']) ) {
		require_once('../inc/wproot.php');	
		$pollid = $_POST['pollid'];
	}
	
	$simplypoll = new SimplyPoll(false);
	$results = $simplypoll->grabPoll($pollid);
	
	$answers = $results['answers'];
	$totalvotes = $results['totalvotes'];
?>

<dl class="poll-results">
	<?php foreach($answers as $key => $answer) : ?>
		
		<?php
			$percentage = round((int)$answer['vote'] / (int)$totalvotes * 100);
		?>		
		
		<dt><?php echo $answer['answer']; ?></dt>
		<dd style="width:<?php echo $percentage; ?>%"><?php echo $percentage; ?>%</dd>
		
	<?php endforeach; ?>
</dl>

<p>Total votes: <?php echo $totalvotes; ?></p>