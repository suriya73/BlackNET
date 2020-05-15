// Call the dataTables jQuery plugin
$(document).ready(function() {
  $("#dataTable").DataTable({
    ordering: true,
    responsive: true,
    select: {
      style: "multi"
    },
    order: [[1,null]],
    columnDefs: [ {
	"targets": 0,
	"orderable": false
   }
   ]
  });
});
