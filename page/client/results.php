<dl class="results">
	<?php foreach($answers as $key => $answer) : ?>
		
		<?php $percentage = round((int)$answer['vote'] / (int)$totalvotes * 100); ?>
		
		<dt><?php echo $answer['answer']; ?></dt>
		<dd style="width:<?php echo $percentage; ?>%"><?php echo $percentage; ?>%</dd>
		
	<?php endforeach; ?>
</dl>

<p><?php _e('Total votes'); ?>: <?php echo $totalvotes; ?></p>