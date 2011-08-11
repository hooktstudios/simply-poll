<?php
	$var =	'<div class="poll" id="poll-'.$id.'">'.
				'<form method="post" action="'.WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'poll-results.php">'.
					'<input type="hidden" name="poll" value="'.$id.'" />'.
					'<fieldset>'.
						'<legend><span>'.$question.'</span></legend>';
			
					
	foreach($answers as $key => $aData)
		$var .=	'<label><input type="radio" name="answer" value="'.$key.'">'.$aData['answer'].'</label><br />';
	
	
	$var .=			'</fieldset>'.
					'<p><button>Vote</button></p>'.
				'</form>'.
			'</div>';

	return $var;