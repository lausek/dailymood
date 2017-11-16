(function () {
	
	var SERVICE_SET_DAY = "/background/set_day.php";
	var chosen_date = get_date();
	
	function format_date(date) {
		return date.toISOString().split('T')[0];
	}

	function call_service(url, callback, body) {
		var request = new XMLHttpRequest();
		request.open("POST", url);
		
		if(body !== undefined) {
			for(var key in body) {
				request.setRequestHeader(key, body[key]);
			}
		}
		
		request.addEventListener('load', function() {
			callback(request);
		});
		request.send();
	}
	
	function choose_callback(event) {
		
		var params = {}; 
	
		params.date = chosen_date;

		call_service(SERVICE_SET_DAY, function(request) {
			if(request.status === 200) {
				alert("geändert");	
			}
		}, params);

	}
	
	function get_date(str) {
		var obj = str === undefined ? new Date() : new Date(str);
		obj.setHours(0, 0, 0, 0);
		return obj;	
	}

	function change_displayed_date() {
	
		var token = "today";

		// is selected date not today?
		if(chosen_date.valueOf() !== get_date().valueOf()) {
			token = "on "+format_date(chosen_date);
		}
		
		document.getElementById("choose-day").innerHTML = token;

	}

	function change_date(node) {
		
		var info_node = node.getElementsByClassName("day-info")[0];
		var info = JSON.parse(info_node.innerHTML);
	
		if(info.mood !== -1) {
		}
			
		chosen_date = get_date(info.date);
		change_displayed_date();	

	}

	document.addEventListener('DOMContentLoaded', function() {
		
		change_displayed_date();
		
		document.getElementById("last-week").addEventListener('click', function(event) {
			if(event.target.className.includes("day-box")) {
				change_date(event.target);			
			}
		});

		var buttons = document.getElementsByClassName("choose-button");
		for(var i = 0; i < buttons.length; i++) {
			buttons[i].addEventListener('click', choose_callback);
		}
		
	});
	
}())
