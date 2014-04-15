/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function (){
    
    $("#date").hide();
    
    $("#date-choise").click(function (){
        $("#date").focus();
        $("#date").show();
    });
   
    $("#date").datepicker({
        onSelect: function(dateText) {
            $("#date").hide();
            $('#loading').fadeIn().delay(5000).fadeOut('slow');
            $("#formDate").submit();
        }
    });    
    
});