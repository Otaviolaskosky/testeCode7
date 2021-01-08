// Call the dataTables jQuery plugin
$(document).ready(function () {

    $(".valor").maskMoney({prefix: "R$ ", decimal: ",", thousands: "."});

    $('.linkExclusaoDevedor').on('click', function () {

        var idCliente = $(this).attr('idCliente');

        $('#idClienteModal').val(idCliente);

    });

    $('.linkExclusaoDivida').on('click', function () { 

        var idDivida = $(this).attr('idDivida');

        $('#idDividaModal').val(idDivida);

    });
});
