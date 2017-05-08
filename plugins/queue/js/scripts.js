$(document).ready(function(){
	$('#queue').DataTable({
		rowReorder: {
			selector: 'td:nth-child(2)'
		},
		"columns": [
			{ "width": "50px" },
			null,
			null,
			null,
			null,
			null,
			null
		],
		"columnDefs": [
			{"className": "dt-center", "targets": [1,4]}
		],
		responsive: true
	});
});
