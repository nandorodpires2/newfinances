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
        base_url = "http://" + host + "/public/movimentacoes/status";        
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


