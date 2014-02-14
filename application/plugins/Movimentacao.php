<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Movimentacao
 *
 * @author Realter
 */
class Plugin_Movimentacao extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        $id_usuario = Zend_Auth::getInstance()->hasIdentity() ? Zend_Auth::getInstance()->getIdentity()->id_usuario : 0;
        
        $modelMovimentacaoRepeticao = new Model_MovimentacaoRepeticao();
        $modelMovimentacao = new Model_Movimentacao();
        
        $dadosProcessar = $modelMovimentacaoRepeticao->getMovimentacoesProcessar($id_usuario);  
                
        foreach ($dadosProcessar as $dados) {            
            
            if ($dados->tipo == 'parcelado') {            
                
                for ($i = 2; $i <= $dados->modo; $i++) {                                                            
            
                    $dadosMovimentacao = $modelMovimentacao->fetchRow("id_movimentacao = {$dados->id_movimentacao}")->toArray();                        
                    $zend_Date = new Zend_Date($dadosMovimentacao['data_movimentacao']);
                    
                    $dadosMovimentacao['descricao_movimentacao'] = substr($dadosMovimentacao['descricao_movimentacao'], 0, -6);
                    
                    $dadosMovimentacao['data_movimentacao'] = $zend_Date->addMonth($i - 1)->toString("yyyy-MM-dd");    
                    $dadosMovimentacao['descricao_movimentacao'] .= ' - ' . $i . '/' . $dados->modo;    
                    $dadosMovimentacao['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosMovimentacao['data_movimentacao'], $dadosMovimentacao['id_tipo_movimentacao']);
                    $dadosMovimentacao['id_movimentacao_pai'] = $dados->id_movimentacao;
                    unset($dadosMovimentacao['id_movimentacao']);                   
                  
                    $modelMovimentacao->insert($dadosMovimentacao);
                    
                }
                
                // atualiza a situacao
                $where = "id_movimentacao_repeticao = " . $dados->id_movimentacao_repeticao;
                $dadosUpdate['processado'] = 1;
                $modelMovimentacaoRepeticao->update($dadosUpdate, $where);
                
            } else {
                
                $loop = "";
                
                switch ($dados->modo) {
                    case 'day';    
                        $loop = 365;
                        for ($i = 1; $i <= $loop; $i++) {
                            
                            $dadosMovimentacao = $modelMovimentacao->fetchRow("id_movimentacao = {$dados->id_movimentacao}")->toArray();                        
                            $zendDate = new Zend_Date($dadosMovimentacao['data_movimentacao']);
                            
                            $dadosMovimentacao['data_movimentacao'] = $zendDate->addDay($i)->toString("yyyy-MM-dd");                            
                            $dadosMovimentacao['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosMovimentacao['data_movimentacao']);
                            $dadosMovimentacao['id_movimentacao_pai'] = $dados->id_movimentacao;
                            unset($dadosMovimentacao['id_movimentacao']);

                            $modelMovimentacao->insert($dadosMovimentacao);
                        }
                        break;
                    case 'week';
                        $loop = 52;
                        for ($i = 1; $i <= $loop; $i++) {
                            
                            $dadosMovimentacao = $modelMovimentacao->fetchRow("id_movimentacao = {$dados->id_movimentacao}")->toArray();                        
                            $zendDate = new Zend_Date($dadosMovimentacao['data_movimentacao']);
                            
                            $dadosMovimentacao['data_movimentacao'] = $zendDate->addWeek($i)->toString("yyyy-MM-dd");                            
                            $dadosMovimentacao['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosMovimentacao['data_movimentacao']);
                            $dadosMovimentacao['id_movimentacao_pai'] = $dados->id_movimentacao;
                            unset($dadosMovimentacao['id_movimentacao']);

                            $modelMovimentacao->insert($dadosMovimentacao);
                        }                        
                        break;
                    case 'month';                        
                        $loop = 12;
                        for ($i = 1; $i <= $loop; $i++) {
                            
                            $dadosMovimentacao = $modelMovimentacao->fetchRow("id_movimentacao = {$dados->id_movimentacao}")->toArray();                        
                            $zendDate = new Zend_Date($dadosMovimentacao['data_movimentacao']);
                            
                            $dadosMovimentacao['data_movimentacao'] = $zendDate->addMonth($i)->toString("yyyy-MM-dd");                            
                            $dadosMovimentacao['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosMovimentacao['data_movimentacao']);
                            $dadosMovimentacao['id_movimentacao_pai'] = $dados->id_movimentacao;
                            unset($dadosMovimentacao['id_movimentacao']);

                            $modelMovimentacao->insert($dadosMovimentacao);
                        }
                        break;
                    case 'year';
                        $loop = 10;
                        for ($i = 1; $i <= $loop; $i++) {
                            
                            $dadosMovimentacao = $modelMovimentacao->fetchRow("id_movimentacao = {$dados->id_movimentacao}")->toArray();                        
                            $zendDate = new Zend_Date($dadosMovimentacao['data_movimentacao']);
                            
                            $dadosMovimentacao['data_movimentacao'] = $zendDate->addYear($i)->toString("yyyy-MM-dd");                            
                            $dadosMovimentacao['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosMovimentacao['data_movimentacao']);
                            $dadosMovimentacao['id_movimentacao_pai'] = $dados->id_movimentacao;
                            unset($dadosMovimentacao['id_movimentacao']);

                            $modelMovimentacao->insert($dadosMovimentacao);
                        }
                        break;
                }
                
                // atualiza a situacao
                $where = "id_movimentacao_repeticao = " . $dados->id_movimentacao_repeticao;
                $dadosUpdate['processado'] = 1;
                $modelMovimentacaoRepeticao->update($dadosUpdate, $where);
                
            }
            
        }
        
    }
    
}
