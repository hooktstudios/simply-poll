jQuery(function(){

	var $ = jQuery;
	$('.poll form').submit(formProcess);

	/**
	 * Process through the form 
	 */
	function formProcess(e){
		
		e.preventDefault();
		
		var poll	= $('input[name=poll]').val(),
			answer	= $('input[name=answer]:checked').val(),
			div		= $(this).parent(),
			action	= $(this).attr('action');

		$(this).fadeOut('slow', function(){
			$(this).empty();
			
			updatePoll(action, poll, answer);
		});
	}

	/**
	 * Update the results from our AJAX query
	 */
	function updatePoll(action, pollID, answer) {
		
		if(answer > 0) {
			var postData = { poll: pollID, answer: answer };

		} else {
			var postData = { poll: pollID };
		}
				
		$.ajax({
			type:		'POST',
			url:		action, 
			data:		postData,
			success:	displayResults, 
			dataType:	'JSON'
		});
	
	}

	function displayResults(data) {

		var html = $.ajax({
			type:		'POST',
			async:		false,
			url:		data.load, 
			data:		data,
			dataType:	'html',
		}).responseText;

		var pollID 	= '#poll-'+data.pollid;

		$(pollID).fadeIn('slow', function() { $(this).append(html); });
	}


});