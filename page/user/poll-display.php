<?php

	echo '<pre>'; print_r($_SERVER); echo '</pre>';

	$html = '<div class="poll" id="poll-'.$id.'">';

	$html .= '<form method="post" action="'.WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'poll-submit.php">';
	$html .= '<input type="hidden" name="poll" value="'.$id.'" />';
	$html .= '<input type="hidden" name="backurl" value="'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'" />';
	$html .= '<legend><span>'.$question.'</span></legend>';
			
	if (($limit == 'yes' && isset($_COOKIE['sptaken']) && in_array($args['id'], unserialize($_COOKIE['sptaken']))) || isset($_GET['simply-poll-return'])) {
	
		$html .=  'You have already taken this poll.';


	} else {

		$html .= '<fieldset>';

		foreach($answers as $key => $aData)
			$html .= '<label><input type="radio" name="answer" value="'.$key.'">'.$aData['answer'].'</label><br />';
		
		
		$html .= '<p><button>Vote</button></p>';
		$html .= '</fieldset>';

	}

	$html .= '</form>';
	$html .= '</div>';

	return $html;