jQuery(function(){

	var $ = jQuery;
	$('.poll form').submit(formProcess);

	/**
	 * Process through the form 
	 */
	function formProcess(e){
		
		e.preventDefault();
		
		var poll	= $('input[name=poll]').val(),
			vote	= $('input[name=answer]:checked').val(),
			div		= $(this).parent(),
			action	= $(this).attr('action');

		$('fieldset', this).fadeOut('slow', function(){
			$(this).empty();
			
			updatePoll(action, poll, vote);
		});
	}

	/**
	 * Update the results from our AJAX query
	 */
	function updatePoll(action, pollID, vote) {
		
		if(vote > 0) {
			var postData = { poll: pollID, vote: vote };

		} else {
			var postData = { poll: pollID };
		}
		
		$.ajax({
			type:		'POST',
			url:		action, 
			data:		postData,
			success:	displayResults, 
			dataType:	'json'
		});
	
	}

	function displayResults(data) {

		var percent;
		var html;
		var totalVotes	= 0;
		var pollID		= data['id'];
		var votedID		= data['voted'];
		var totalVotes	= data['totalvotes'];

		html = '<dl>';
		
		for (id in data['answers']) {
			percent	= Math.round( ( parseInt(data['answers'][id]['vote']) / parseInt( totalVotes ) ) * 100 );
			html 	= html + '<dt>' + data['answers'][id]['answer'] + '</dt><dd style="width:' + percent + '%">' + percent + '%</dd>';
		}
		
		html	= html + '</dl><p>Total Votes: ' + totalVotes + '</p>';
		pollID 	= '#poll-'+pollID;
		

		$(pollID).fadeIn('slow', function() { $(this).append(html); });
	}


});