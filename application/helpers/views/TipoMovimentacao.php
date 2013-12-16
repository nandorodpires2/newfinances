<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TipoMovimentacao
 *
 * @author Realter
 */
class View_Helper_TipoMovimentacao extends Zend_View_Helper_Abstract {
    
    public static function getTipoMovimentacaoImage($tipoMovimentacao) {
        
        $img = "";
        $zendView= new Zend_View();
        
        switch ((int)$tipoMovimentacao) {
            case 1:                
                $img = "<img src='{$zendView->baseUrl('views/img/ball_green.png')}' title='Receita' width=15 />";
                break;
            case 2:
                $img = "<img src='{$zendView->baseUrl('views/img/ball_red.png')}' title='Despesa' width=15 />";
                break;
            case 3:
                $img = "<img src='{$zendView->baseUrl('views/img/credit_card.png')}' title='Cartão de Crédito' width=15 />";
                break;
            case 4:
                $img = "<img src='{$zendView->baseUrl('views/img/transfer.png')}' title='Transferência' width=15 />";
                break;            
            case 5:                
                $img = "<img src='{$zendView->baseUrl('views/img/ball_blue.png')}' title='Saldo Inicial' width=15 />";
                break;
            case 6:                
                $img = "<img src='{$zendView->baseUrl('views/img/icon_pay.png')}' title='Fatura Cartão' width=15 />";
                break;
            case 7:                
                $img = "<img src='{$zendView->baseUrl('views/img/ball_red.png')}' title='Fatura Cartão' width=15 />";
                break;
            case 8:                
                $img = "<img src='{$zendView->baseUrl('views/img/ball_red.png')}' title='Fatura Cartão' width=15 />";
                break;
            default :
                $img = "";
                break;
        }
        
        return $img;
        
    }
    
}

