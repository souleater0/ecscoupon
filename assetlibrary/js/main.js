$(document).ready(function () {
"use strict";

var fullHeight = function() {

	$('.js-fullheight').css('height', $(window).height());
	$(window).resize(function(){
		$('.js-fullheight').css('height', $(window).height());
	});

};
fullHeight();

$('#sidebarCollapse').on('click', function () {
	// alert("test");
  $('#sidebar').toggleClass('active');
	});
});