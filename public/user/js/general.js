
$('#signin').on('click', function() {
	showOverlay('.signin-overlay');
});

$('#close_signin').on('click', function() {
	hideOverlay('.signin-container');
});

$('#signup').on('click', function() {
	showOverlay('.signup-overlay');
});

$('#close_signup').on('click', function() {
	hideOverlay('.signup-container');
});

$('.close-overlay').on('click', function() {
	$('#close_signin, #close_signup').trigger('click');
});

function showOverlay(elem) {
	$('.close-overlay').show();
	$(elem).addClass('show');
	$(elem).children().delay( 500 ).fadeIn();
}

function hideOverlay(elem) {
	$('.close-overlay').hide();
	$(elem).fadeOut(150, function() {
		$(elem).parent().removeClass('show');
	});
}

toastr.options = {
	"positionClass": "toast-bottom-right"
}

function getQueryStringParam(key) {
	var urlParams = new URLSearchParams(window.location.search);

	//console.log(urlParams.has('post')); // true
	return urlParams.get(key); // "edit"
	//console.log(urlParams.append('active', '1')); // "?post=1234&action=edit&active=1"
}
