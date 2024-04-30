let loadingHtml = `<div
	id="loading-spinner-wraper">
		<div 
		class="loading">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%; display: block; shape-rendering: auto;" width="40px" height="40px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
			<circle cx="50" cy="50" fill="none" stroke="#3C8DBC" stroke-width="10" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">
			<animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="0.5952380952380952s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform>
			</circle>
		</svg>
		</div>
	</div>`;

$(loadingHtml).appendTo('body');

// show spinner
function showLoadingSpinner() {
	$('#loading-spinner-wraper').addClass('show fade-in');
	$('#loading-spinner-wraper div.loading').addClass('bounce-in'); 
}

// hide spinner
function hideLoadingSpinner() {
	$('#loading-spinner-wraper').addClass('fade-out');
	setTimeout(() => {
		$('#loading-spinner-wraper').removeClass('show fade-in fade-out');
		$('#loading-spinner-wraper div.loading').removeClass('bounce-in'); 
	}, 50);
}
