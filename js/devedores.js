// Call the dataTables jQuery plugin
$(document).ready(function () {

    //formata o campo valor:
    $(".valor").maskMoney({prefix: "R$ ", decimal: ",", thousands: "."});

    //Ao clicar no Link o sistema atribui o id para um campo oculto que será usando no formulario para a exclusão:
    $('.linkExclusaoDevedor').on('click', function () {

        var idCliente = $(this).attr('idCliente');

        $('#idClienteModal').val(idCliente);

    });

    //Ao clicar no Link o sistema atribui o id para um campo oculto que será usando no formulario para a exclusão:
    $('.linkExclusaoDivida').on('click', function () { 

        var idDivida = $(this).attr('idDivida');

        $('#idDividaModal').val(idDivida);

    });
});
