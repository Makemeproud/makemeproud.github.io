
$(document).ready(function () {

	// Tabs
	$("#mainTab, #sideTab").tab();

	// Sidebar
	$('.c-toggle').click(function(){
	   $('.sidebar').toggleClass('active');
	});

});