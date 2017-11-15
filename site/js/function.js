(function () {
	
	var SERVICE_SET_DAY = "/src/background/set_day.php"
	
	function call_service(url, body, callback) {
		
	}
	
	function choose_callback(event) {
		console.log(sender, "click");
	}
	
	document.addEventListener('DOMContentLoaded', function() {
		
		var buttons = document.getElementsByClassName("choose-button");
		for(var i = 0; i < buttons.length; i++) {
			buttons[i].addEventListener('click', choose_callback);
		}
		
	});
	
}())