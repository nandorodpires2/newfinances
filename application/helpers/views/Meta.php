<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Meta
 *
 * @author Realter
 */
class View_Helper_Meta extends Zend_View_Helper_Abstract {

    public static function getProjecaoMeta($porcentagem) {
        
        $zendDate = new Zend_Date();
        $dias_mes = (int)$zendDate->get(Zend_Date::MONTH_DAYS);
        $dia_atual = (int)date('d');
        
        $projecao = number_format(($porcentagem * $dias_mes) / $dia_atual, 2, ',', '');
        
        return $projecao;
        
    } 
    
    public static function getTotalGastosMetaMes($id_categoria) {
        
        $modelCategoria = new Model_Categoria();
        $totalGastos = $modelCategoria->getTotalGastoCategoriaMes($id_categoria);
        
        return $totalGastos->total;
        
    }

    /**
     * retorna a porcentagem total dos gastos
     */
    public static function getPorcentagemTotalGastos($total_meta, $total_gasto) {        
        
        $total_gasto *= -1;
        
        $porcentagem = $porcentagem_gastos = number_format(
            ($total_gasto * 100) / $total_meta, 
            2, 
            ',', 
            ''
        );        
        
        return $porcentagem;        
        
    }
    
    public static function getColorMeta($total_gasto) {
        
        $view = new Zend_View();
                
        $img = "";
        
        if ($total_gasto < 50) {
            $img = "<img src='" . $view->baseUrl('views/img/ball_green.png') . "' width=15px title='Sinal OK' />";
        } else if ($total_gasto > 50 && $total_gasto <= 70) {
            $img = "<img src='" . $view->baseUrl('views/img/ball_yellow.png') . "' width=15px title='Sinal de Alerta'/>";
        } elseif ($total_gasto > 70 && $total_gasto <= 100) {
            $img = "<img src='" . $view->baseUrl('views/img/ball_red.png') . "' width=15px title='Quase Estourando'/>";
        } else {
            $img = "<img src='" . $view->baseUrl('views/img/ball_black.png') . "' width=15px title='Estourou o Orçamento'/>";
        }
                
        return $img;
        
    }
    
}

