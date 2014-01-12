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
        
        return View_Helper_Currency::getCurrency($totalGastos->total);
        
    }

    /**
     * retorna a porcentagem total dos gastos
     */
    public static function getPorcentagemTotalGastos($total_meta, $total_gasto) {        
        return $porcentagem_gastos = number_format(
            ($total_gasto * 100) / $total_meta, 
            2, 
            ',', 
            ''
        );        
    }
    
}

