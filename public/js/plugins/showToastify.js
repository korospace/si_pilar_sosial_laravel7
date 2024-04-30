let showToast = "";

$.getScript(BASE_URL+ "/js/plugins/toastify-js.js", function() {

	showToast = (text,type,autoClose=true) => {

		let icon = "";
		let alertStyle = {};

		if (type == 'warning') {
			icon = `<i class="fa fa-question-circle"></i>`;
			alertStyle = {
				color: "#856404",
				background: "#fff3cd",
				border: "1px solid #ffeeba",
			}
		}
		else if (type == 'danger') {
			icon = `<i class="fa fa-times-circle"></i>`;
			alertStyle = {
				color: "#721c24",
				background: "#f8d7da",
				border: "1px solid #f5c6cb",
			}
		}
		else if (type == 'success') {
			icon = `<i class="fa fa-check-circle"></i>`;
			alertStyle = {
				color: "#155724",
				background: "#d4edda",
				border: "1px solid ##c3e6cb",
			}
		}
		else {
			icon = `<i class="fa fa-exclamation-circle"></i>`;
			alertStyle = {
				color: "#0c5460",
				background: "#d1ecf1",
				border: "1px solid #bee5eb",
			}
		}

		Toastify({
			text: `<div style='display:flex;align-items:center;'>${icon} <div style='margin:0 0 2px 6px;'>${text}</div></div>`,
			escapeMarkup: false,
			duration: autoClose ? 5000 : -1,
            position: "center",
			style: alertStyle
		}).showToast();

	}

});
