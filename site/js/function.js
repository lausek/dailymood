(function () {
	
	const SERVICE_GET_TIMELINE = "/background/get_timeline.php";
	const SERVICE_GET_MOODS = "/background/get_moods.php";
	const SERVICE_SET_DAY = "/background/set_day.php";
	
	let timeline = [];
	let chosen_date = get_date();
	
	class DayBox {
		
		constructor(dayinfo) {
			this.day = dayinfo.day;
			this.mood = dayinfo.mood;
			
			this.node = document.createElement("div");
			this.node.className = "day-box"
		}
		
		set_mood(m) {
			this.mood = m;
		}
		
	}
	
	function join_params(obj) {
		let str = "";
		let i = 0;
		for(var key in obj) {
			str += (i?"&":"") + key+"="+encodeURI(obj[key]);
			i++;
		}
		return str;
	}

	function format_date(date) {
		return new Date(date.getTime() - (date.getTimezoneOffset()*60000)).toISOString().split('T')[0];
	}

	function call_service(url, callback, body) {
		var request = new XMLHttpRequest();
		request.open('POST', url, true);
		
		request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		request.addEventListener('load', function() {
			callback(request);
		});
		request.send(join_params(body));
	}
	
	function choose_callback(evt) {
	
		/* recursively get button element */
		var button = (function that(node) {
			return node.className.includes("choose-button") ? node : that(node.parentElement);
		}(evt.target));

		var params = {
			ondate: format_date(chosen_date),
			mood: button.getElementsByClassName("choose-text")[0].innerHTML,
		}; 

		call_service(SERVICE_SET_DAY, function(request) {
			if(request.status === 200) {
				var focused = document.getElementById("day-focused");
				if(focused === null) {
					var today = document.getElementsByClassName("day-box")[0];
					today.id = "day-focused";
					focused = today;
				}

				var classes = focused.classList;
				for(var i = 0; i < classes.length; i++) {
					var cls = classes[i];
					if(cls !== "day-box") {
						classes.remove(cls);
					}
				}
				classes.add("day-mood-"+params.mood);
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

		/* is selected date not today? */
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
		
		var previous = document.getElementById("day-focused");
		if(previous) {
			previous.id = "";
		}
		node.id = "day-focused";

		chosen_date = get_date(info.date);
		change_displayed_date();	

	}

	document.addEventListener('DOMContentLoaded', function() {
		
		let week = document.getElementById("last-week");
		let month = document.getElementById("last-month");
		
		call_service(SERVICE_GET_TIMELINE, function(request) {
			if(request.status === 200) {
				let i = 1;
				for(let day of JSON.parse(request.response)) {
					let daynode = new DayBox(day);
					
					timeline.push(daynode);
					if(i <= 7) {
						week.appendChild(daynode.node);
					}
					month.appendChild(daynode.node);
					
					i++;
				}
			}
		});
		
		/* TODO: wait until data was retrieved */
		for(let button of document.getElementsByClassName("choose-button")) {
			button.addEventListener('click', choose_callback);
		}
		
		week.addEventListener('click', event => {
			if(event.target.className.includes("day-box")) {
				change_date(event.target);			
			}
		});
		
	});
	
}())
