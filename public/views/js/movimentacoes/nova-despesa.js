/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function (){    
    
    $("#tipo_pgto-cartao").is();
    
    $("#parcelas-label").hide();
    $("#parcelas").hide();
    $("#repetir-label").hide();
    $("#repetir").hide();
    $("#modo_repeticao-label").hide();
    $("#modo_repeticao-element").hide();    
    
    $("#tipo_pgto-conta").click(function(){
        $("#id_conta-label").show();
        $("#id_conta").show();
        $("#id_cartao-label").hide();
        $("#id_cartao").hide();
    })
    
    $("#tipo_pgto-cartao").click(function(){
        $("#id_cartao-label").show();
        $("#id_cartao").show();
        $("#id_conta-label").hide();
        $("#id_conta").hide();
    })
    
    $("#opt_repetir").click(function(){
        if($(this).is(':checked')) {
            $("#modo_repeticao-label").show();
            $("#modo_repeticao-element").show();    
        } else {
            $("#modo_repeticao-label").hide();
            $("#modo_repeticao-element").hide();    
            $("#parcelas-label").hide();
            $("#parcelas").hide();
            $("#repetir-label").hide();
            $("#repetir").hide();
        }
    });
    
    $("#modo_repeticao-fixo").click(function (){
        $("#repetir-label").show();
        $("#repetir").show();
        $("#parcelas-label").hide();
        $("#parcelas").hide();
    })
    
    $("#modo_repeticao-parcelado").click(function (){
        $("#parcelas-label").show();
        $("#parcelas").show();
        $("#repetir-label").hide();
        $("#repetir").hide();
    })
    
});


