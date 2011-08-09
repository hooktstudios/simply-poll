jQuery(function(){

	var $ = jQuery;
	$('.poll form').submit(formProcess);  
	
	function formProcess(e){
		
		e.preventDefault();
		
		var poll	= $('input[name=poll]').val(),
			vote	= $('input[name=answer]:checked').val(),
			div		= $(this).parent(),
			action	= $(this).attr('action');
		
		$(this).fadeOut('slow', function(){
			$(this).empty();
			
			var postData = { poll: poll, vote: vote };
			console.log(postData);
		    $.ajax({
		    	type:		'POST',
		    	url:		action, 
		    	data:		postData,
		    	success:	grabResults, 
		    	dataType:	'json'
		    });
		 });
	}
	
	function grabResults(data) {
	
		console.log(data);
		
		var percent;
		var totalVotes	= 0;
		var votedID		= data['vote'];
		var totalVotes	= data['totalvotes'];
		/*
		for (id in data['answers']) {
			totalVotes = totalVotes+parseInt(data['answers'][id]['vote']);
		}
		*/
		
		var html = '<div class="poll"><h3>Poll Results</h3><dl>';
		
		for (id in data['answers']) {
			percent = Math.round((parseInt(data['answers'][id]['vote'])/parseInt(totalVotes))*100);
			
			if (id == votedID) {
				html = html+'<dt>'+data['answers'][id]['answer']+'</dt><dd>'+percent+'%</dd>\n';
			} else {
				html = html+'<dt>'+data['answers'][id]['answer']+'</dt><dd>'+percent+'%</dd>\n';
			}
		}
		
		html = html+"</dl><p>Total Votes: "+totalVotes+"</p></div>\n";
		
		$('#poll-0').fadeIn('slow',function(){
			$(this).append(html);
		});
	}


});