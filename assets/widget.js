$(document).ready(function(){
	// This function is executed once the document is loaded
	
	// Caching the jQuery selectors:
	var count = $('.onlineWidget .count');
	var panel = $('.onlineWidget .panel');
	var timeout;
	
	// Loading the number of users online into the count div:
	//count.load('who-is-online/online.php');
	
	$('.onlineWidget').hover(
		function(){
			// Setting a custom 'open' event on the sliding panel:
			
			clearTimeout(timeout);
			timeout = setTimeout(function(){panel.trigger('open');},500);
		},
		function(){
			// Custom 'close' event:
			
			clearTimeout(timeout);
			timeout = setTimeout(function(){panel.trigger('close');},500);
		}
	);
	
	var loaded=false;	// A flag which prevents multiple ajax calls to geodata.php;
	
	// Binding functions to custom events:
	
	panel.bind('open',function(){
		panel.slideDown(function(){
			if(!loaded)
			{
				// Loading the countries and the flags once the sliding panel is shown:
				panel.load('who-is-online/geodata.php');
				loaded=true;
			}
		});
	}).bind('close',function(){
		panel.slideUp();
	});
	
});