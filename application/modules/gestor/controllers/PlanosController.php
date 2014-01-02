<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanosController
 *
 * @author Realter
 */
class Gestor_PlanosController extends Application_Controller {

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        
        // busca os planos cadastrados
        $planos = $this->_modelPlano->fetchAll();
        $this->view->planos = $planos;
        
        // busca os valores cadastrados para os planos
        $planosValores = $this->_modelPlanoValor->getValoresPlanos();           
        $this->view->planosValores = $planosValores;        
        
    }
    
    public function novoValorPlanoAction() {
     
        // envia o form do plano valor para a view
        $this->view->formPlanosValor = $this->_formPlanosValor;
        
        if ($this->_request->isPost()) {
            $dadosPlanoValor = $this->_request->getPost();
            if ($this->_formPlanosValor->isValid($dadosPlanoValor)) {
                $dadosPlanoValor = $this->_formPlanosValor->getValues();
                
                // busca o nome do plano
                $plano = $this->_modelPlano->fetchRow("id_plano = {$dadosPlanoValor['id_plano']}");
                
                // desabilitar o plano valor atual
                $planoValorAtivo = $this->_modelPlanoValor->fetchRow("
                    id_plano = {$dadosPlanoValor['id_plano']}
                    and usuario = 1
                ");
                    
                $dadosDesativaPlanoValor['usuario'] = 0;
                $whereDesativaPlanoValor = "id_plano_valor = {$planoValorAtivo->id_plano_valor}";
                
                // formatando o valor
                $dadosPlanoValor['valor_plano'] = View_Helper_Currency::setCurrencyDb($dadosPlanoValor['valor_plano'], "positivo");
                
                // seta o banner do plano
                $banner_plano = 'banner_' . str_replace(" ", "_", strtolower($plano->descricao_plano)) . '.png';
                $dadosPlanoValor['banner'] = $banner_plano;
                
                // cadastrar o novo
                try {   
                    $this->_modelPlanoValor->update($dadosDesativaPlanoValor, $whereDesativaPlanoValor);
                    $this->_modelPlanoValor->insert($dadosPlanoValor);
                    $this->_redirect("gestor/planos/");                    
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                            
            }
        }
        
    }
    
}

