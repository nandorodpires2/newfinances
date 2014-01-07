/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
   
   $("#validar").click(function(){
       var cupom = $("#cupom").val();
       var id_plano = $("#id_plano").val();
       var valor_plano = $("#valor_plano").val();
       alert(id_plano);
       validaCupom(cupom, id_plano);
       recalculaValor(valor_plano, cupom, id_plano)
   });

});

function validaCupom(cupom, id_plano) {
   
   var base_url = baseUrl();
   
   $.ajax({
        url: base_url + 'planos/valida-cupom/',
        type: "get",
        data: {
            cupom: cupom,
            id_plano: id_plano
        },
        dataType: "html",
        beforeSend: function() {            
            $("#loading").show();
        },
        success: function(dados) { 
            $("#loading").hide();
            $("#valor-desconto").html(dados);
        },
        error: function(error) {
            alert('Houve um erro');
        }
    }); 
   
}

function recalcularValor(valor_plano, cupom, id_plano) {
   var base_url = baseUrl();
      
   $.ajax({
        url: base_url + 'planos/recalcula-valor/',
        type: "get",
        data: {
            valor_plano: valor_plano,
            cupom: cupom,
            id_plano: id_plano
        },
        dataType: "html",
        beforeSend: function() {            
            $("#loading").show();
        },
        success: function(dados) { 
            $("#loading").hide();
            $("#valor-total-pagar").html(dados);           
        },
        error: function(error) {
            alert('Houve um erro');
        }
    }); 
}