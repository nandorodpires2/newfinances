/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){

    $("#fatura").change(function(){
        var vencimento_fatura = $(this).val();
        $("#formFaturas").submit();        
    });
    
});


