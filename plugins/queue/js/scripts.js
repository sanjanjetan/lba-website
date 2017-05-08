$(document).ready(function(){
	$('#queue').DataTable({
		rowReorder: {
			selector: 'td:nth-child(2)'
		},
		"columns": [
			null,
			{ "width": "20px" },
			null,
			null,
			null,
			null,
			null,
			null
		],
		"columnDefs": [
			{"className": "dt-center", "targets": [2,5]},
			{ "orderable": false, "targets": [6,7] }
		],
		responsive: true
	});
});
