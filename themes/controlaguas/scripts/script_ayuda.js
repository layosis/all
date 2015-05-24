
(function ($) {

  $(document).ready(function() {
    $('#table').DataTable({
	  responsive: true,
      "order": [[ 0, "asc" ]],
      "columns": [ null, null, null, null, null, null],
      "pagingType": "full_numbers",
      "language": {
        "emptyTable":     "No hay datos disponibles en la tabla",
        "info":           "Mostrando _START_ a _END_ de _TOTAL_ elementos",
        "infoEmpty":      "Mostrando 0 a 0 de 0 elementos",
        "infoFiltered":   "(filtrado de _MAX_ elementos totales)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Mostrar _MENU_ elementos",
        "loadingRecords": "Cargando...",
        "processing":     "Procesando...",
        "search":         "Buscar",
        "zeroRecords":    "No hay elementos coincidentes encontrados",
        "paginate": {
          "first":        "Primero",
          "last":         "Último",
          "next":         "Siguiente",
          "previous":     "Anterior"
        },
        "aria": {
          "sortAscending":  ": activar para ordenar la columna ascedentemente",
          "sortDescending": ": activar para ordenar la columna descedentemente"
        }
      }
    });
  });

}) (jQuery);
