/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
   
    $("#parcelas-label").hide();
    $("#parcelas").hide();
    $("#repetir-label").hide();
    $("#repetir").hide();
    $("#modo_repeticao-label").hide();
    $("#modo_repeticao-element").hide();   
    $("#opt_repetir-label").hide();   
    $("#opt_repetir").hide();   
    
    if ($("#tipo_pgto-conta").is(":checked")) {
        $("#id_conta-label").show();
        $("#id_conta").show();
        $("#id_cartao-label").hide();
        $("#id_cartao").hide();
    } else if ($("#tipo_pgto-cartao").is(":checked")){
        $("#id_cartao-label").show();
        $("#id_cartao").show();
        $("#id_conta-label").hide();
        $("#id_conta").hide();
    }
});
