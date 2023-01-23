const updateUrl = (dataRaw) => {
	let dataArray = dataRaw.split('&');
	let dataFormatted = [];

	dataArray.forEach(data => {
		dataFormatted.push(data.split('='));
	});

	let searchText = '';
	let locations = [];
	let times = [];
	let lengths = [];

	dataFormatted.forEach(data => {
		if (data[0].includes('search--text')) {
			searchText = data[1];
		}

		if (data[0].includes('event-location')) {
			let location = data[1].substr(16).toLowerCase();
			locations.push(location);
		}

		if (data[0].includes('event-time')) {
			let time = data[1].substr(12).toLowerCase();
			times.push(time);
		}

		if (data[0].includes('event-length')) {
			let length = data[1].substr(14).toLowerCase();
			lengths.push(length);
		}
	});

	let url = 'events/?';

	if (searchText.length > 0) {
		if (url.length > 8) {
			url += '&s=';
		} else {
			url += 's=';
		}
		url = url + searchText;
	}

	if (locations.length > 0) {
		if (url.length > 8) {
			url += '&event-location=';
		} else {
			url += 'event-location=';
		}
		locations.forEach(location => {
			let locationFormatted = location.replace(/%20/g, "-");
			url += locationFormatted + ',';
		});	
		url = url.slice(0, -1);
	}

	if (times.length > 0) {
		if (url.length > 8) {
			url += '&event-time=';
		} else {
			url += 'event-time=';
		}
		times.forEach(time => {
			let timesFormatted = time.replace(/%20/g, "-");
			url += timesFormatted + ',';
		});	
		url = url.slice(0, -1);
	}

	if (lengths.length > 0) {
		if (url.length > 8) {
			url += '&event-length=';
		} else {
			url += 'event-length=';
		}
		lengths.forEach(length => {
			let lengthsFormatted = length.replace(/%20/g, "-");
			url += lengthsFormatted + ',';
		});	
		url = url.slice(0, -1);
	}

	history.pushState({}, 'rewrite', `https://${window.location.hostname}/${url}`)
}

(function($) {
	$(document).ready(function() {
		
		$('#events__form').on('submit', function(e) {

			e.preventDefault();

			let data = $('#events__form').serialize() + '&nonce=' + wpAjax.nonce + '&action=filter';
			updateUrl(data);

			$.ajax({
				url: wpAjax.ajaxUrl,
				data: data,
				type: 'post',
				success: function(result) {
					$('#events__list').fadeOut();
					setTimeout(function() {
						$('#events__list').html(result);
						$('#events__list').fadeIn();
					}, 500);
				},
				error: function(result) {
					console.warn(result);
				}
			});

		});

		$('#events__reset').on('click', function(e) {
			e.preventDefault();
			$('#events__form')[0].reset();

			let data = 'nonce=' + wpAjax.nonce + '&action=filter';
			updateUrl(data);

			const selectOptions = document.querySelectorAll('#events__form option');

			for (let i=0; i<selectOptions.length; i++) {

				if (selectOptions[i].selected == true) {
					selectOptions[i].removeAttribute('selected');
				}

				if (selectOptions[i].disabled == true) {
					selectOptions[i].setAttribute('selected', '');
				}
			}

			$.ajax({
				url: wpAjax.ajaxUrl,
				data: data,
				type: 'post',
				success: function(result) {
					$('#events__list').fadeOut();
					setTimeout(function() {
						$('#events__list').html(result);
						$('#events__list').fadeIn();
					}, 500);
				},
				error: function(result) {
					console.warn(result);
				}
			});

		});

	});
})(jQuery);