<?php

class IndexController extends Application_Controller {

    public function init() {
    
        parent::init();
                
        // verifica se tem pelo menos uma conta cadastrada
        if (!Controller_Helper_Application::hasConta()) {        
            Controller_Helper_Messeges::setMesseges(array('alert' => 'É necessário cadastrar uma conta para acessar esta página'));
            $this->_redirect("configuracoes/nova-conta");
        }      
        
    }
    
    public function indexAction() {     
        
        $this->view->formDate = $this->_formDate;
        
        /**
         * busca as movimentacoes do dia
         */                
        $data = Controller_Helper_Date::getDateDb($this->_request->getParam("date", date('d/m/Y')));
        
        $movimentacoes = $this->_modelVwMovimentacao->getMovimentacoesData($data, $this->_session->id_usuario);        
        $this->view->movimentacoes = $movimentacoes;
        $this->view->date = Controller_Helper_Date::getDateViewComplete($data);
                
        // busca as faturas do mes
        $faturasCartoes = $this->_modelVwLancamentoCartao->getFaturasAtual($this->_session->id_usuario);
        $this->view->faturasCartoes = $faturasCartoes;
        
        /**
         * top categorias
         */
        $gastosCategorias = $this->_modelCategoria->getGastosCategoriasMes($this->_session->id_usuario);
        $this->view->gastosCategorias = $gastosCategorias;
        
        /**
         * saldo das metas          
         */
        $metas = $this->_modelMeta->getGastosMetasUsuario($this->_session->id_usuario);
        $this->view->metas = $metas;
        $this->view->total_meta = $this->_modelMeta->getTotalMetaMes(
            $this->_session->id_usuario,
            (int)date('m'),
            (int)date('Y')
        );        
        $this->view->total_gastos = $this->_modelMeta->getTotalGastoMetas(
            $this->_session->id_usuario,
            (int)date('m'),
            (int)date('Y')
        );        
         
        /**
         * fatura cartao
         */     
        // seleciona os cartoes do usuario
        $cartoes = $this->_modelCartao->fetchAll("id_usuario = {$this->_session->id_usuario}");
        $this->view->cartoes = $cartoes;
        
        /**
         * avisos
         */        
        // verifica se existe pendencias
        $pendencias = $this->_modelMovimentacao->getPendencias($this->_session->id_usuario);
        $this->view->pendencias = $pendencias;      
        
        // proximas 3 receitas
        $receitas = $this->_modelMovimentacao->getProximasReceitas($this->_session->id_usuario);
        $this->view->receitas = $receitas;
        
        // proximas 5 despesas
        $despesas = $this->_modelMovimentacao->getProximasDespesas($this->_session->id_usuario);
        $this->view->despesas = $despesas;
        
    }
    
    public function denyAction() {
        
        $module = $this->_getParam("module");
        
        // caso o bloqueio de acesso seja do tipo 1 
        // e pq e o gestor do sistema e nao tem permissao para ver
        // caso 2 e pq o plano nao contempla esta visualizacao
        // envia uma msg especifica para o usurio        
        $this->view->typeDeny = $module;
        
        
    }

}

