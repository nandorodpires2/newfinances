<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Categorias
 *
 * @author Realter
 */
class View_Helper_Categorias extends Zend_View_Helper_Abstract {

    public static function getPorcentagemGastCategoriaMesAtual($valor) {
        
        $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;
        
        // busca o total gasto do mes
        $modelMovimentacao = new Model_Movimentacao();
        $totalDespesa = $modelMovimentacao->getTotalDespesaMesAtual($id_usuario)->total_despesa;
        
        $porcentagem = ($valor * 100) / $totalDespesa;
        
        return number_format($porcentagem, 2, ",", ",") . "%";
        
    }
    
    /**
     * retorna o valor previsto para a categoria
     */
    public static function getValorPrevistoCategoria($id_categoria) {
        $id_usuario = Zend_Auth::getInstance()->getIdentity()->id_usuario;
        
        // busca o total gasto do mes
        $modelMovimentacao = new Model_Movimentacao();
        $totalPrevistoCategoria = $modelMovimentacao->getTotalPrevistoCategoriaMes($id_categoria, $id_usuario);
        
        return View_Helper_Currency::getCurrency($totalPrevistoCategoria->total_categoria);
    }
    
}

