/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (){

    //$("#politica-privacidade").hide();
    $("#termo-uso").hide();
    $("#fechar").hide();

    // mostrando a politica de privacidade
    $("#info_politica").click(function (){
        $("#politica-privacidade").show();
        $("#termo-uso").hide();
        $("#fechar").show();
    });
    
    // mostrando o termo de uso
    $("#info_termo").click(function (){
        $("#termo-uso").show();
        $("#politica-privacidade").hide();
        $("#fechar").show();
    });
    
    // fechando a política de privacidade
    $("#fechar").click(function (){
        $("#politica-privacidade").hide();
        $("#termo-uso").hide();
        $("#fechar").hide();
    });
        
});

// busca as cidades de acordo com o estado selecionado
function buscaCidades(id_estado) {
    
    var host = window.location.hostname;    
    var base_url = "";
    
    if (host === 'localhost') {
        base_url = 'http://' + host + '/finances/public/cidades/busca-cidades'
    } else {
        base_url = 'http://' + host + '/public/cidades/busca-cidades'
    }
    
    $.ajax({
        url: base_url,
        type: "post",
        data: {
            id_estado: id_estado
        },
        dataType: "html",
        beforeSend: function() {            
            $("#id_cidade").html("<option>Buscando...</option>");
            $("#loading").show();
        },
        success: function(dados) {            
            $("#id_cidade").html(dados);
            $("#id_cidade").attr("disabled", false);
            $("#loading").hide();
        },
        error: function(error) {
            alert('Houve um erro');
        }
    }); 
}
