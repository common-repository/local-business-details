jQuery(document).ready(function($) {
	// Show the scheduler on button click
	$('#drive-scheduler-btn').click(function(){
        $(this).animate({bottom: '-200px'}, 300);
		$('#drive-scheduler').animate({bottom: 0}, 300);
    });
	
	// Hide scheduler on exit click
    $('#drive-scheduler .close-btn').click(function(){
        $('#drive-scheduler').animate({bottom: '-400px'}, 300);
		$('#drive-scheduler-btn').animate({bottom: 0}, 300);
    });

});