jQuery(function(){

	var $ = jQuery;
	
	/*
	$('.sp-add').click(function(e){
		var lastAnswerID = 0;
		$('#answers input[type=text]').each(function(){
			++lastAnswerID;;
		});
		
		var newAnswerID = parseInt(lastAnswerID) + 1;
		
		$('#answers ol').append('<li><input type="text" name="answers['+newAnswerID+'][answer]" /> <a href="#" class="sp-remove">remove</a></li>');
		
		e.preventDefault();
	});
	*/
	$('button[name=addPoll]').click(function(e){
		
		var answer;
		answer = confirm('Are you sure you\'re happy with the poll (it cannot be edited once confirmed)');

		if(answer == false){
			e.preventDefault();
		}
	});
	
	$('.sp-remove').click(function(e){
		
		var parent = $(this).parent();
		$('input', parent).val('');
		$(parent).hide();
		
		e.preventDefault();
	});
	
});
