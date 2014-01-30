/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * funcoes padrao para todo a aplicacao
 */
$(document).ready(function (){

    /*
    var deviceAgent = navigator.userAgent.toLowerCase();
    var agentID = deviceAgent.match(/(iphone|ipod|ipad|android)/);

    if (agentID) {
        alert("Você está em um aplicativo mobile");
        location = "index/";    
    }
    */

    // calculadora
    $("#calculator").click(function (){        
        $("#box-calculator").show();
        $("#box-calculator").dialog();        
    });
    
    var $j = jQuery.noConflict();    
    $j("#to-top").hide();
    $j(function () {
        $j(window).scroll(function () {
            if ($j(this).scrollTop() > 300) {
                $j('#to-top').fadeIn();
            } else {
                $j('#to-top').fadeOut();
            }
        });
        $j('#to-top').click(function() {
            $j('body,html').animate({scrollTop:0},600);
        }); 
    });
    
   
    /*
    $("a").click(function (event) {
       $('#loading').fadeIn().delay(6000).fadeOut('slow');  
    });
    */
   
    $("input:submit").click(function (event) {
       $('#loading').fadeIn().delay(5000).fadeOut('slow');  
    });
    
    $("#data_movimentacao").datepicker();
    
    //$("#table").dataTable();
    
    //$("#loading").hide();
    
    // campo valor movimentacao
    $("#valor_movimentacao").maskMoney({symbol:'', thousands:'.', decimal:',', symbolStay: true});    
    
    // campo valor meta
    $("#valor_meta").maskMoney({symbol:'', thousands:'.', decimal:',', symbolStay: true});   
    
    // saldo inicial conta
    $("#saldo_inicial").maskMoney({symbol:'', thousands:'.', decimal:',', symbolStay: true});   
    
    // limite cartao
    $("#limite_cartao").maskMoney({symbol:'', thousands:'.', decimal:',', symbolStay: true});   
    
    // valor plano
    $("#valor_plano").maskMoney({symbol:'', thousands:'.', decimal:',', symbolStay: true});   
    
    // campo data movimentacao
    $("#data_movimentacao").mask("99/99/9999");    
    
    // campo data_nascimento
    $("#data_nascimento").mask("99/99/9999");    
    
    // campo cpf_usuario
    $("#cpf_usuario").mask("999.999.999-99");
    
    // mostra a aba de saldos
    $("#aba-saldo-open").click(function (){
        $("#box-saldo").show("slow");
        $("#aba-saldo-open").hide();
        $("#aba-saldo-close").show();
    })
    
    // fecha a aba de saldos
    $("#aba-saldo-close").click(function (){
        $("#box-saldo").hide("slow");
        $("#aba-saldo-open").show();
        $("#aba-saldo-close").hide();
    })
    
    // fechar box alerta plano basico
    $("#fechar").click(function (){
        $("#plano-basico").hide("slow");
    })
    
});

function baseUrl() {
    var host = window.location.hostname;
    var base_url = "";

    if (host === 'localhost') {
        base_url = 'http://' + host + '/newfinances/public/';
    } else {
        base_url = 'http://' + host + '/';
    }
    
    return base_url;
}
