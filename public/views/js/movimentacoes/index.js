/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (){

    $("#dia").change(function (){
        
        var dia = $(this).val();        
        
        $("html,body").animate(
            {
                scrollTop: $("#" + dia).offset().top
            },
            'slow'
        );    
    });
    
    $("#mes").change(function () {
        $("#formMes").submit();
    });
    
    $("#conta").change(function (){
        $("#formConta").submit();
    });
    
    var mes = $("#mes").val();
    var ano = $("#ano").val();
    
    var dia = 1;
    while (dia <= 31) {        
        
        $("#loader-lanc-"+dia).hide();
        
        var data = ano + '-' + mes + '-' + dia;
        
        if ($("#"+dia).length) {
            buscaLancamentos(data, dia);
        }
        
        dia++;        
    }
    
});

/*
 * altera o status da movimentacao (Previsto X Realizado)
 */
function status(id_movimentacao, status) {
    
    var host = window.location.hostname;  
    var base_url = "";    
    
    // verifica se e base de teste
    if (host == "localhost") {
        base_url = "http://" + host + "/newfinances/public/movimentacoes/status";        
    } else {
        base_url = "http://" + host + "/movimentacoes/status";        
    }
        
    $.ajax({
        url: base_url,
        type: "get",
        data: {
            id_movimentacao: id_movimentacao,
            status: status
        },
        dataType: "html",
        beforeSend: function() {            
            $("#loading").show();
        },
        success: function(dados) { 
            window.location.reload();			
        },
        error: function(error) {
            alert('Houve um erro');
        }
    }); 
    
}


/**
 * busca os lancamentos
 */
function buscaLancamentos(data, dia) {
    
    var base_url = baseUrl();
    
    $.ajax({
        url: base_url + "movimentacoes/busca-lancamentos",
        type: "get",
        data: {
            data: data
        },
        dataType: "html",
        beforeSend: function() {            
            $("#loader-lanc-"+dia).show();
        },
        success: function(dados) {     
            $("#lanc-" + dia).html(dados);
            $("#loader-lanc-"+dia).hide();
        },
        error: function(error) {
            $("#lanc-" + dia).html("<center><b>Não foi possível carregar os dados.</b></center>");
            $("#loader-lanc-"+dia).hide();
        }
    }); 
}


