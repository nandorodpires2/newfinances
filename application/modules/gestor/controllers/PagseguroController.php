<?php

require_once APPLICATION_PATH . '/../library/PagSeguroLibrary/PagSeguroLibrary.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PagSeguroController
 *
 * @author Realter
 */
class Gestor_PagseguroController extends Application_Controller {

    public function init() {
        parent::init();
    }
    
    public function indexAction() {
        
        $credentials = $this->_credentials;

        /* Definindo a data de ínicio da consulta */  
        $initialDate = '2013-12-01T08:50';  

        /* Definindo a data de término da consulta */  
        
        $zendDate = new Zend_Date();
        $finalDate = $zendDate->toString("YYYY-MM-dTH:m");        

        /* Definindo o número máximo de resultados por página */  
        $maxPageResults = 20;  

        /* Definindo o número da página */  
        $pageNumber = 1;  

        try {
        
            /* Realizando a consulta */  
            $result = PagSeguroTransactionSearchService::searchByDate(  
                $credentials,       // credenciais  
                $pageNumber,        // número da página  
                $maxPageResults,    // número máximo de resultados por página  
                $initialDate,       // data de ínicio  
                $finalDate         // data de término  
            );  

            /* Obtendo as transações do objeto PagSeguroTransactionSearchResult */  
            $transactions = $result->getTransactions();
            
            $dadosTransacao = array();            
            foreach ($transactions as $key => $transaction) {
                $dadosTransacao[$key]['status'] = $this->getNameStatusTransaction($transaction->getStatus()->getValue());
                $dadosTransacao[$key]['data'] = $transaction->getDate();
                $dadosTransacao[$key]['code'] = $transaction->getCode();
                $dadosTransacao[$key]['cpf'] = $transaction->getReference();
                $dadosTransacao[$key]['valor'] = $transaction->getGrossAmount();
                $dadosTransacao[$key]['receber'] = $transaction->getNetAmount();
                //$dadosTransacao[$key]['taxas'] = $transaction->getFreeAmount();
            }
            
            //Zend_Debug::dump($transactions[0]); die();
            
            $this->view->dadosTransacao = $dadosTransacao;
            
        } catch (PagSeguroServiceException $error) {             
            echo $error->getTraceAsString();
        }  
     
    }

    public function responsesPagseguroAction() {
        $this->_disabledLayout();
        $this->_disabledView();
        
        $credentials = $this->_credentials;  

    }

    /**
     * 
     */
    public function consultaPagamentoAction() {
        $this->_disabledLayout();
        $this->_disabledView();
    }
    
}

