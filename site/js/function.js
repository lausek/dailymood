(function () {
	
	var SERVICE_SET_DAY = "/src/background/set_day.php";
	
	function call_service(url, callback, body) {
		var request = new XMLHttpRequest();
		request.open("POST", url);
		
		if(body !== undefined) {
			for(var key in body) {
				request.setReguestHeader(key, body[key]);
			}
		}
		
		request.addEventListener('load', function() {
			callback(request);
		});
		request.send();
	}
	
	function choose_callback(event) {
		call_service(SERVICE_SET_DAY, function(request) {
			if(request.status === 200) {
					
			}
		});
	}
	
	document.addEventListener('DOMContentLoaded', function() {
		
		var buttons = document.getElementsByClassName("choose-button");
		for(var i = 0; i < buttons.length; i++) {
			buttons[i].addEventListener('click', choose_callback);
		}
		
	});
	
}())
