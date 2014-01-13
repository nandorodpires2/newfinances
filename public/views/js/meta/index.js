/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (){    
    $("#label-repetir-meta").hide();    
    
    $("#repetir").click(function (){
        if ($("#repetir").is(':checked')) {
            $("#label-repetir-meta").show();
        } else {
            $("#label-repetir-meta").hide();
        }
    });
    // sugere o valor da meta de acordo com a media mensal
    $("#id_categoria").change(function(){
        var id_categoria = $(this).val(); 
        valueSugest(id_categoria);
        $("#valor_meta").focus();
    });
    
});

function valueSugest(id_categoria) {
    
    var base_url = baseUrl();
        
    $.ajax({
        url: base_url + 'metas/val-sugest',
        type: "get",
        data: {
            id_categoria: id_categoria
        },
        dataType: "html",
        beforeSend: function() {            
            $("#loading").show();
        },
        success: function(dados) { 
            $("#loading").hide();
            $("#val-sugest").html("Valor sugerido: " + dados);
        },
        error: function(error) {
            alert('Houve um erro');
        }
    });    
    
}
