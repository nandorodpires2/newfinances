<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Default
 *
 * @author Realter
 */
class Form_Default extends Zend_Form {

    public $_session;
    
    public function init() {
        $this->_session = Zend_Auth::getInstance()->getIdentity();
    }

    /**
     * Retorna as categorias cadastradas no sistema
     * 
     * @return type
     */
    public function getCategorias() {
        
        $multiOptions = array('' => 'Selecione...');
        
        $modelCategoria = new Model_Categoria();
        $categorias = $modelCategoria->fetchAll("ativo_categoria = 1", "descricao_categoria asc");
                
        foreach ($categorias as $categoria) {
            $multiOptions[$categoria->id_categoria] = $categoria->descricao_categoria;
        }
        
        return $multiOptions;
        
    }
    
     /**
     * Retorna as categorias que ainda nao tem meta cadastrada
     * 
     * @return type
     */
    public function getCategoriasMeta() {
        
        $multiOptions = array('' => 'Selecione...');
        
        $modelCategoria = new Model_Categoria();
        $categorias = $modelCategoria->getCategoriasCadastradasMes($this->_session->id_usuario);
        
        // busca as categorias cadastradas para este mes
        $categoriasCadastradas = $modelCategoria;
        
        foreach ($categorias as $categoria) {
            $multiOptions[$categoria->id_categoria] = $categoria->descricao_categoria;
        }
        
        return $multiOptions;
        
    }    
    
    /**
     * 
     * Retorna as contas do usuario
     * 
     * @param type $idUsuario
     * @return type
     */
    public function getContasUsuario() {
        
        $modelConta = new Model_Conta();
        $contas = $modelConta->getContasUsuario($this->_session->id_usuario);
        
        if ($contas->count() == 0 ) {            
            return $multiOptions = array('' => "Nenhuma conta cadastrada");
        }
        
        foreach ($contas as $conta) {
            $descricaoConta = $conta->descricao_conta . ' - ' . $conta->descricao_tipo_conta;
            $multiOptions[$conta->id_conta] = $descricaoConta;
        }
        
        return $multiOptions;        
        
    }
    
    public function getCartoesUsuario() {
        
        $modelCartao = new Model_Cartao();
        $cartoes = $modelCartao->fetchAll("id_usuario = {$this->_session->id_usuario} and ativo_cartao = 1");
        
        if ($cartoes->count() == 0 ) {            
            return $multiOptions = array('' => "Nenhum cartão cadastrado");
        }
        
        foreach ($cartoes as $cartao) {
            $multiOptions[$cartao->id_cartao] = $cartao->descricao_cartao;
        }
        
        return $multiOptions;
        
    }
    
    /**
     * retorna os bancos
     */
    public function getBancos() {
        
        $multiOptions = array(null => 'Nenhum');
        
        $modelBanco = new Model_Banco();
        $bancos = $modelBanco->fetchAll();        
        
        foreach ($bancos as $banco) {
            $multiOptions[$banco->id_banco] = $banco->nome_banco;
        }
        
        return $multiOptions;
        
    }
    
    /**
     * retorna os tipos de contas
     */
    public function getTipoContas() {
        
        $multiOptions = array();
        
        $modelTipoConta = new Model_TipoConta();
        $tipo_contas = $modelTipoConta->fetchAll("id_tipo_conta <> 3");
        
        foreach ($tipo_contas as $tipo_conta) {
            $multiOptions[$tipo_conta->id_tipo_conta] = $tipo_conta->descricao_tipo_conta;
        }
        
        return $multiOptions;
        
    }    
    
    /**
     * retorna os planos 
     */
    public function getPlanos() {
        
        $multiOptions = array();
        
        $modelPlano = new Model_Plano();
        $planos = $modelPlano->getPlanosUsuario();
        
        foreach ($planos as $plano) {
            if ($plano->valor_plano != 0) {
                $multiOptions[$plano->id_plano] = $plano->descricao_plano . 
                    ' - ' . 
                    View_Helper_Currency::getCurrency($plano->valor_plano) .
                    ' | ' .
                    View_Helper_Currency::getCurrency($plano->valor_mes) . '/mês';
            } else {
                $multiOptions[$plano->id_plano] = $plano->descricao_plano;
            }
        }
        
        return $multiOptions;
        
    }
    
    /**
     * retorna os estados
     */
    public function getEstados() {
        
        $multiOptions = array("" => "Selecione...");
        
        $modelEstado = new Model_Estado();
        $estados = $modelEstado->fetchAll(null, "descricao_estado asc");
        
        foreach ($estados as $estado) {
            $multiOptions[$estado->id_estado] = $estado->descricao_estado;
        }
        
        return $multiOptions;
        
    }
    
    /**
     * retorna as posicoes para os menus cadastradas
     */
    public function getPosicoesMenu() {
        
        $multiOptions = array("" => "Selecione...");
        
        $modelMenuPosciao = new Model_MenuPosicao();
        $posicoes = $modelMenuPosciao->fetchAll();
        
        foreach ($posicoes as $posicao) {
            $multiOptions[$posicao->id_menu_posicao] = $posicao->posicao;
        }
        
        return $multiOptions;
        
    }
    
    /**
     * retorna as funcionalidade do sistema
     */
    public function getFuncionalidades() {
        $multiOptions = array();
        
        $modelFuncionalidades = new Model_Funcionalidade();
        $funcionalidades = $modelFuncionalidades->getFuncionalidades();
        
        foreach ($funcionalidades as $funcionalidade) {
            $multiOptions[$funcionalidade->id_funcionalidade] = "  -  " . $funcionalidade->descricao_permissao;
        }
        
        return $multiOptions;
    }
    
}

