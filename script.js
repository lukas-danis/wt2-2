$(document).ready(function() {
  $('#myTable').DataTable({
    "columnDefs": [{
        "orderable": false,
        "targets": [1, 4]
      },
      {
        "orderable": true,
        "targets": [0, 2, 3]
      },
      {
        "targets": [ 3 ],
        "orderData": [ 3, 2 ],
      }
    ]
  })

  
})
