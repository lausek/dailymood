(function () {
	
	const SERVICE_GET_TIMELINE = '/background/get_timeline.php';
	const SERVICE_GET_MOODS = '/background/get_moods.php';
	const SERVICE_SET_DAY = '/background/set_day.php';
	
	const week  = () => document.getElementById('last-week-boxes');
	const month = () => document.getElementById('last-month-boxes');
	const get_day = (obj, n) => obj.getElementsByClassName('day-box')[n];
	
	const moods = (() => {
		let m = [];
		call_service(SERVICE_GET_MOODS)
			.then(request => {
				let i = 1;
				JSON.parse(request.response).forEach(node => {
					m[i] = node;
					i += 1;
				});
				/* TODO: will fail if DOM isn't loaded */
				initialize_buttons(m);
			});
		return m;
	})();

	let chosen_date = get_date();
	
	const new_day = (day => {
		const node = document.createElement('div');
		node.className = 'day-box';
		if(day.mood) {
			node.className += ' day-mood-'+moods[day.mood].name;
		}
		node.innerHTML = '<span class="day-info" hidden>{ "date": "'+day.day+'", "mood": "'+(day.mood ? day.mood : -1)+'" }</span>';
		return node;
	});
	
	function clear_loading(node) {
		for(let loading_node of node.getElementsByClassName('loading')) {
			node.removeChild(loading_node);
		}
	}
	
	function join_params(obj) {
		let str = "";
		let i = 0;
		for(let key in obj) {
			str += (i?"&":"") + encodeURI(key)+"="+encodeURI(obj[key]);
			i++;
		}
		return str;
	}

	function format_date(date) {
		return new Date(date.getTime() - (date.getTimezoneOffset()*60000)).toISOString().split('T')[0];
	}

	function call_service(url, body) {
		return new Promise((resolve, reject) => {
			const request = new XMLHttpRequest();
			request.open('POST', url, true);
			
			request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			request.addEventListener('load', () => {
				if(request.status === 200) {
					resolve(request);
				} else {
					reject(request);
				}
			});
			request.send(join_params(body));
		});
	}
	
	function choose_callback(evt) {
	
		/* recursively get button element */
		const button = (function that(node) {
			return node.className.includes('choose-button') ? node : that(node.parentElement);
		}(evt.target));

		const params = {
			ondate: format_date(chosen_date),
			mood: button.getElementsByClassName('choose-text')[0].innerHTML,
		}; 

		call_service(SERVICE_SET_DAY, params)
			.then(request => {
				let focused = document.getElementById('day-focused');
				if(focused === null) {
					const today = get_day(week(), 0);
					today.id = 'day-focused';
					focused = today;
				}
				
				const change_mood = node => {
					const classes = node.classList;
					classes.forEach(cls => {
						if(cls !== 'day-box') {
							classes.remove(cls);
						}
					});
					classes.add('day-mood-'+params.mood);
				};
				
				change_mood(focused);
				
				let i = 0;
				for(let node of focused.parentElement.children) {
					if(node == focused) {
						change_mood(get_day(month(), i));
						break;
					}
					i += 1;
				}				
			});

	}
	
	function get_date(str) {
		const obj = str === undefined ? new Date() : new Date(str);
		obj.setHours(0, 0, 0, 0);
		return obj;	
	}

	function change_displayed_date() {
	
		let token = 'today';

		/* is selected date not today? */
		if(chosen_date.valueOf() !== get_date().valueOf()) {
			token = 'on '+format_date(chosen_date);
		}
		
		document.getElementById('choose-day').innerHTML = token;

	}

	function change_date(node) {
		
		const info_node = node.getElementsByClassName('day-info')[0];
		const info = JSON.parse(info_node.innerHTML);
	
		if(info.mood !== -1) {
			
		}
		
		const previous = document.getElementById('day-focused');
		if(previous) {
			previous.id = '';
		}
		
		node.id = 'day-focused';

		chosen_date = get_date(info.date);
		change_displayed_date();	

	}
	
	function initialize_buttons(moods) {
		const buttons = document.getElementById('choose-buttons');
		moods.forEach(mood => {
			const node = document.createElement('div');
			node.id = 'choose-'+mood.name;
			node.className = 'choose-button';
			node.innerHTML = "<div class='choose-icon'>&#"+mood.icon+";</div>"
								+ "<div class='choose-text'>"+mood.name+"</div>";
			node.addEventListener('click', choose_callback);
			buttons.appendChild(node);
		});
		clear_loading(buttons);
	}
	
	document.addEventListener('DOMContentLoaded', () => {
		
		const ref_week  = week();
		const ref_month = month();
		
		call_service(SERVICE_GET_TIMELINE)
			.then(function(request) {
				let i = 1;
				JSON.parse(request.response).forEach(day => {
					if(i <= 7) {
						ref_week.appendChild(new_day(day));
					}
					ref_month.appendChild(new_day(day));
					i += 1;
				});
				clear_loading(ref_week);
				clear_loading(ref_month);
			});
		
		ref_week.addEventListener('click', event => {
			if(event.target.className.includes('day-box')) {
				change_date(event.target);			
			}
		});
		
	});
	
}())
