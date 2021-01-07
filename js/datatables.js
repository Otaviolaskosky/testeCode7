// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
      dom: "<'row'<'col-sm-10'l><'col-sm-2'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'i><'col-sm-3'p>>",
      language: {
            url: '//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json'
        }
  });
});
