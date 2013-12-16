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
    
});


