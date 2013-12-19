<?php

require_once APPLICATION_PATH . '/../library/PagSeguroLibrary/PagSeguroLibrary.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlanosController
 *
 * @author Realter
 */
class PlanosController extends Application_Controller {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        
    }

    /**
     * plano usuario
     */
    public function planoUsuarioAction() {

        $this->view->messages = Controller_Helper_Messeges::getMesseges();

        $last_id = (int) $this->_getParam("id", $this->_session->id_usuario);
        $dadosUsuario = $this->_modelUsuario->fetchRow("id_usuario = {$last_id}");

        $this->view->dadosUsuario = $dadosUsuario;
        
        // envia o form dos planos pagos para a view
        $this->_formUsuariosPlanosUsuario->id_usuario->setValue($this->_session->id_usuario);
        $this->view->formPlanosUsuario = $this->_formUsuariosPlanosUsuario;
        
        // verifica o plano atual do usuario
        $planoUsuario = $this->_modelUsuarioPlano->getPlanoAtual($this->_session->id_usuario);
        
        // verifica se o usuario pode experimentar o plano
        if (Controller_Helper_Plano::canExperience($planoUsuario)) {
            $this->view->flagExperience = true;
            // envia o plano de experiencia de 30 dias para a view
            $planoExperiencia = $this->_modelPlano->getPlanoExperiencia();
            $this->view->id_usuario = $this->_session->id_usuario;
            $this->view->planoExperiencia = $planoExperiencia;
        } else {
            $this->view->flagExperience = false;            
        }
        
        if ($this->_request->isPost()) {            
            $dadosPlanoUsuario = $this->_request->getPost();
            if ($this->_formUsuariosPlanosUsuario->isValid($dadosPlanoUsuario)) {
                $dadosPlanoUsuario = $this->_formUsuariosPlanosUsuario->getValues();

                $cobranca = $this->_modelPlano->find($dadosPlanoUsuario['id_plano'])->current();

                if ($cobranca->cobranca) {

                    // dados do usuario
                    $dadosUsuario = $this->_modelUsuario->find($this->_session->id_usuario)->current();
                    $cpf_usuario = Controller_Helper_Application::formatCPF($dadosUsuario->cpf_usuario);

                    // buscando os dados do plano escolhido
                    $dadosPlano = $this->_modelPlanoValor->getPlanoValorUsuario($dadosPlanoUsuario['id_plano']);
                    
                    try {
                        $paymentRequest = new PagSeguroPaymentRequest();
                        $paymentRequest->addItem('0001', $dadosPlano->descricao_plano, 1, $dadosPlano->valor_plano);
                        $paymentRequest->setCurrency("BRL");

                        $paymentRequest->setSenderName($dadosUsuario->nome_completo);
                        $paymentRequest->setSenderEmail($dadosUsuario->email_usuario);
                        //$paymentRequest->setSender($dadosUsuario->nome_completo, $dadosUsuario->email_usuario);
                        $paymentRequest->addParameter('senderCPF', $cpf_usuario);
                        $paymentRequest->setReference($dadosUsuario->cpf_usuario);
                        
                        // verifica se tem copom de desconto
                        if ($dadosPlanoUsuario['cupom'] !== '') {                            
                            // valida o cupom
                            $dadosCupomDesconto = $this->_modelCupomDesconto->getDescontoCupom($dadosPlanoUsuario['cupom'], $this->_session->id_usuario);                                                        
                            if ($dadosCupomDesconto) {
                                // calcula o desconto
                                $desconto = ($dadosPlano->valor_plano * ($dadosCupomDesconto->porcentagem / 100)) * -1;
                                $paymentRequest->setExtraAmount($desconto);                                
                            }
                        }
                        
                        // Informando as credenciais  
                        $credentials = $this->_credentials;

                        // fazendo a requisição a API do PagSeguro pra obter a URL de pagamento                  
                        $url = $paymentRequest->register($credentials);
                        
                        // grava a solicitacao de pagamento na tabela pagamento
                        $dadosPagamento['id_usuario'] = $this->_session->id_usuario;
                        $dadosPagamento['id_plano'] = $dadosPlano->id_plano;
                        $dadosPagamento['id_plano_valor'] = $dadosPlano->id_plano_valor;
                        $dadosPagamento['data_solicitado'] = Controller_Helper_Date::getDatetimeNowDb();
                        $dadosPagamento['status'] = 0;
                        $dadosPagamento['processado'] = 0;

                        $this->_modelPagamento->insert($dadosPagamento);
                        
                        // redirecionando para o site do pagseguro    
                        $this->_redirect($url);
                    } catch (PagSeguroServiceException $error) {
                        Controller_Helper_Messeges::setMesseges(array(
                            'error' => 'Houve um erro na comunicação com o PagSeguro'
                        ));
                    }
                } else {
                    
                    // busca os dados do plano atual
                    $dadosPlanoAtual = $this->_modelUsuarioPlano->getPlanoAtual($last_id);

                    // desativa o plano atual                
                    $dadosDesativaPlano['ativo_plano'] = 0;
                    $whereDesativaPlano = "id_usuario_plano = " . $dadosPlanoAtual->id_usuario_plano;
                    $this->_modelUsuarioPlano->update($dadosDesativaPlano, $whereDesativaPlano);

                    // cadastra o novo plano
                    $dadosPlanoUsuarioNovo['id_usuario'] = $last_id;
                    $dadosPlanoUsuarioNovo['data_aderido'] = Controller_Helper_Date::getDatetimeNowDb();
                    $dadosPlanoUsuarioNovo['data_encerramento'] = Controller_Helper_Date::getDataEncerramentoPlano(
                                $dadosPlanoUsuarioNovo['data_aderido'], $dadosPlanoUsuario['id_plano']
                    );
                    $dadosPlanoUsuarioNovo['ativo_plano'] = 1;
                    $dadosPlanoUsuarioNovo['id_plano'] = (int)$dadosPlanoUsuario['id_plano'];
                            
                    // buscar o valor do plano
                    $planoValor = $this->_modelPlanoValor->fetchRow("id_plano = {$dadosPlanoUsuario['id_plano']} and usuario = 1");
                    $dadosPlanoUsuarioNovo['id_plano_valor'] = $planoValor->id_plano_valor;

                    try {
                        $this->_modelUsuarioPlano->insert($dadosPlanoUsuarioNovo);
                        // seta a mensagem de parabens
                        // redireciona para a pagina principal
                        $this->_redirect("index/");
                    } catch (Zend_Exception $error) {                        
                        echo $error->getMessage();
                    }
                }
            } else {
                Zend_Debug::dump($this->_formUsuariosPlanosUsuario->getErrors()); die();
            }
        }
    }

    public function responsePlanoAction() {

        $credentials = $this->_credentials;
        $transaction_id = $this->_getParam("transaction_id", null);

        //$transaction_id = '27464905-0E86-4F89-8476-93EA185C382E'; // paga
        //$transaction_id = "5C8A55D0-9D22-446E-BDC9-44CB04A6535B"; // aguardando pagamento

        define('TOKEN', '1E3A21173CC2409EBB2DE17317045312');

        if (count($_POST) > 0) {

            $post = Zend_Debug::dump($_POST);
            
            // POST recebido, indica que é a requisição do NPI.
            $npi = new PagSeguroNpi();
            $result = $npi->notificationPost();

            $transacaoID = isset($_POST['TransacaoID']) ? $_POST['TransacaoID'] : '';

            if ($result == "VERIFICADO") {
                
                $dadosTransacao = $this->_request->getPost();
                
                // busca os dados do usuario
                $dadosUsuario = $this->_modelUsuario->getDadosUsuarioCpf($dadosTransacao['Referencia']);
                // busca o plano requisitado pelo usuario
                $dadosPlanoRequisitado = $this->_modelPagamento->fetchRow(
                    "id_usuario = {$dadosUsuario->id_usuario} and processado = 0"
                );
                
                // verifica o status da transacao
                if ($dadosTransacao['StatusTransacao'] == "Aprovado") {
                    // dasativar o plano atual do usuario                    
                    $dadosDesativaPlanoUsuario['ativo_plano'] = 0;
                    $whereDesativaPlanoUsuario = "id_usuario_plano = {$dadosUsuario->id_usuario_plano}";
                    
                    $this->_modelUsuarioPlano->update($dadosDesativaPlanoUsuario, $whereDesativaPlanoUsuario);
                    
                    // cadastrar o usuario no novo plano 
                    $dadosNovoPlanoUsuario['id_usuario'] = $dadosPlanoRequisitado->id_usuario;
                    $dadosNovoPlanoUsuario['id_plano'] = $dadosPlanoRequisitado->id_plano;
                    $dadosNovoPlanoUsuario['data_aderido'] = Controller_Helper_Date::getDatetimeNowDb();
                    $dadosNovoPlanoUsuario['data_encerramento'] = Controller_Helper_Date::getDataEncerramentoPlano(
                            $dadosNovoPlanoUsuario['data_aderido'], 
                            $dadosPlanoRequisitado->id_plano
                    );
                    $dadosNovoPlanoUsuario['ativo_plano'] = 1;
                    $dadosNovoPlanoUsuario['id_plano_valor'] = $dadosPlanoRequisitado->id_plano_valor;

                    $this->_modelUsuarioPlano->insert($dadosNovoPlanoUsuario);
                    
                    // atualizar o processo              
                    $dadosAtualizaProcessoPagamento['cod_transacao'] = $transaction_id;                    
                    $dadosAtualizaProcessoPagamento['processado'] = 1;
                    $dadosAtualizaProcessoPagamento['data_processado'] = Controller_Helper_Date::getDatetimeNowDb();
                    $dadosAtualizaProcessoPagamento['status'] = $status;
                    $whereAtualizaProcessoPagamento = "id_pagamento = {$dadosPlanoRequisitado->id_pagamento}";
                    
                    $this->_modelPagamento->update($dadosAtualizaProcessoPagamento, $whereAtualizaProcessoPagamento);
                    
                    // envia o email para o usuario
                    $mail = new Zend_Mail('utf-8');

                    $mail->setBodyHtml("Pagamento confirmado. Você já pode desfrutar...");
                    $mail->setFrom('email@portal.redemorar.com.br', 'NewFinances - Controle Financeiro');
                    $mail->addTo("nandorodpires@gmail.com");
                    //$mail->addTo('tiago@realter.com.br');
                    //$mail->setReplyTo('email@portal.redemorar.com.br');
                    $mail->setSubject('Pagamento confirmado');

                    $mail->send(Zend_Registry::get('mail_transport'));
                    
                    $this->desativaCupom($dadosPlanoRequisitado->id_usuario);
                }                
                
            } else if ($result == "FALSO") {
                
                $dadosTransacao = $this->_request->getPost();
                $transaction_id = $dadosTransacao['notificationCode'];
                $transaction = $this->getTransaction($transaction_id);
                
                // recupero o status da transacao
                $status = (int)$transaction->getStatus()->getValue();
                
                
            } else {
                $result = "ERRO";                
                //Erro na integração com o PagSeguro.
            }
            
            // envia o email para o usuario
            $mail = new Zend_Mail('utf-8');

            $mail->setBodyHtml("um post do PagSeguro foi enviado - {$result} <p>{$transacaoID}</p><p>{$post}</p><p>Status: {$status}</p>");
            $mail->setFrom('email@portal.redemorar.com.br', 'NewFinances - Controle Financeiro');
            $mail->addTo("nandorodpires@gmail.com");
            //$mail->addTo('tiago@realter.com.br');
            //$mail->setReplyTo('email@portal.redemorar.com.br');
            $mail->setSubject('POST enviado - ' . $result);

            $mail->send(Zend_Registry::get('mail_transport'));
            
        } else {            
            if ($transaction_id) {
                
                // busca os dados da transacao
                $transaction = $this->getTransaction($transaction_id);
                $value_transaction = $transaction->getStatus()->getValue();
                $name = $transaction->getSender()->getName();
                $email = $transaction->getSender()->getEmail();
                $code = $transaction->getCode();
                
                // buscando a referencia do usuario que e o cpf
                $cpf_reference = $transaction->getReference();
                
                // busca os dados do usuario
                $dadosUsuario = $this->_modelUsuario->getDadosUsuarioCpf($cpf_reference);

                // busca o plano requisitado pelo usuario
                $dadosPlanoRequisitado = $this->_modelPagamento->fetchRow(
                    "id_usuario = {$dadosUsuario->id_usuario} and processado = 0"
                );
                    
                // recupero o status da transacao
                $status = (int)$transaction->getStatus()->getValue();
                
                // verifica se o status esta como pago para liberacao automática do
                // sistema
                if ($status === parent::PAID) {               

                    // dasativar o plano atual do usuario                    
                    $dadosDesativaPlanoUsuario['ativo_plano'] = 0;
                    $whereDesativaPlanoUsuario = "id_usuario_plano = {$dadosUsuario->id_usuario_plano}";
                    
                    $this->_modelUsuarioPlano->update($dadosDesativaPlanoUsuario, $whereDesativaPlanoUsuario);
                    
                    // cadastrar o usuario no novo plano 
                    $dadosNovoPlanoUsuario['id_usuario'] = $dadosPlanoRequisitado->id_usuario;
                    $dadosNovoPlanoUsuario['id_plano'] = $dadosPlanoRequisitado->id_plano;
                    $dadosNovoPlanoUsuario['data_aderido'] = Controller_Helper_Date::getDatetimeNowDb();
                    $dadosNovoPlanoUsuario['data_encerramento'] = Controller_Helper_Date::getDataEncerramentoPlano(
                            $dadosNovoPlanoUsuario['data_aderido'], 
                            $dadosPlanoRequisitado->id_plano
                    );
                    $dadosNovoPlanoUsuario['ativo_plano'] = 1;
                    $dadosNovoPlanoUsuario['id_plano_valor'] = $dadosPlanoRequisitado->id_plano_valor;

                    $this->_modelUsuarioPlano->insert($dadosNovoPlanoUsuario);
                    
                    // atualizar o processo              
                    $dadosAtualizaProcessoPagamento['cod_transacao'] = $transaction_id;                    
                    $dadosAtualizaProcessoPagamento['processado'] = 1;
                    $dadosAtualizaProcessoPagamento['data_processado'] = Controller_Helper_Date::getDatetimeNowDb();
                    $dadosAtualizaProcessoPagamento['status'] = $status;
                    $whereAtualizaProcessoPagamento = "id_pagamento = {$dadosPlanoRequisitado->id_pagamento}";
                    
                    $this->_modelPagamento->update($dadosAtualizaProcessoPagamento, $whereAtualizaProcessoPagamento);
                    
                    // envia o email para o usuario
                    $mail = new Zend_Mail('utf-8');

                    $mail->setBodyHtml("Pagamento confirmado. Você já pode desfrutar...");
                    $mail->setFrom('email@portal.redemorar.com.br', 'NewFinances - Controle Financeiro');
                    $mail->addTo("nandorodpires@gmail.com");
                    //$mail->addTo('tiago@realter.com.br');
                    //$mail->setReplyTo('email@portal.redemorar.com.br');
                    $mail->setSubject('Pagamento confirmado');

                    $mail->send(Zend_Registry::get('mail_transport'));
                    
                    $this->desativaCupom($dadosPlanoRequisitado->id_usuario);

                } else {
                    // atualizar o processo              
                    $dadosAtualizaProcessoPagamento['cod_transacao'] = $transaction_id;                    
                    $dadosAtualizaProcessoPagamento['status'] = $status;                    
                    $whereAtualizaProcessoPagamento = "id_pagamento = {$dadosPlanoRequisitado->id_pagamento}";
                    
                    $this->_modelPagamento->update($dadosAtualizaProcessoPagamento, $whereAtualizaProcessoPagamento);
                }

                $this->view->status = $status;
                
            }            
            
        }
    }
    
    /**
     * 
     */
    protected function getTransaction($transaction_id) {
        $transaction = PagSeguroTransactionSearchService::searchByCode(  
            $this->_credentials,  
            $transaction_id  
        );
        
        return $transaction;
    }
       
    /**
     * atualiza a tabela de cupom para os cupons utilizados
     */
    protected function desativaCupom($id_usuario) {
        
        // verifica se tem cupom
        $cupom = $this->_modelCupomDesconto->getCupom($id_usuario);
        
        if ($cupom) {        
            $dadosDesativaCupom['utilizado'] = 1;
            $dadosDesativaCupom['data_utilizado'] = Controller_Helper_Date::getDatetimeNowDb();
            $whereDesativaCupom = "id_cupom_desconto = " . $cupom->id_cupom_desconto;

            $this->_modelCupomDesconto->update($dadosDesativaCupom, $whereDesativaCupom);
        }
        
    }

    /*
    public function alterarPlanoAction() {

        $this->view->messages = Controller_Helper_Messeges::getMesseges();

        // busca os dados do plano do usuario
        $dadosPlano = $this->_modelUsuarioPlano->getPlanoAtual($this->_session->id_usuario);
        $this->view->dadosPlano = $dadosPlano;
        $this->view->usuario = $this->_session->nome_completo;
               
        $this->_formUsuariosPlanosUsuario->id_usuario->setValue($this->_session->id_usuario);
        $this->view->formPlanosUsuario = $this->_formUsuariosPlanosUsuario;
        
        // envia o plano de experiencia de 30 dias para a view
        $planoExperiencia = $this->_modelPlano->getPlanoExperiencia();
        $this->view->id_usuario = $this->_session->id_usuario;
        $this->view->planoExperiencia = $planoExperiencia;

        if ($this->_request->isPost()) {
            $dadosAtualizaPlano = $this->_request->getPost();
            if ($this->_formUsuariosPlanosUsuario->isValid($dadosAtualizaPlano)) {
                $dadosAtualizaPlano = $this->_formUsuariosPlanosUsuario->getValues();

                Zend_Debug::dump($dadosAtualizaPlano); die();
                
                // dados do usuario
                $dadosUsuario = $this->_modelUsuario->find($this->_session->id_usuario)->current();
                $cpf_usuario = Controller_Helper_Application::formatCPF($dadosUsuario->cpf_usuario);

                // buscando os dados do plano escolhido
                $dadosPlano = $this->_modelPlanoValor->getPlanoValorUsuario($dadosAtualizaPlano['id_plano']);

                try {
                    $paymentRequest = new PagSeguroPaymentRequest();
                    $paymentRequest->addItem('0001', $dadosPlano->descricao_plano, 1, $dadosPlano->valor_plano);
                    $paymentRequest->setCurrency("BRL");

                    $paymentRequest->setSenderName($dadosUsuario->nome_completo);
                    $paymentRequest->setSenderEmail($dadosUsuario->email_usuario);
                    //$paymentRequest->setSender($dadosUsuario->nome_completo, $dadosUsuario->email_usuario);
                    $paymentRequest->addParameter('senderCPF', $cpf_usuario);
                    $paymentRequest->setReference($dadosUsuario->cpf_usuario);

                    // Informando as credenciais  
                    $credentials = $this->_credentials;

                    // fazendo a requisição a API do PagSeguro pra obter a URL de pagamento                  
                    $url = $paymentRequest->register($credentials);
                    
                    // grava a solicitacao de pagamento na tabela pagamento
                    $dadosPagamento['id_usuario'] = $this->_session->id_usuario;
                    $dadosPagamento['id_plano'] = $dadosPlano->id_plano;
                    $dadosPagamento['id_plano_valor'] = $dadosPlano->id_plano_valor;
                    $dadosPagamento['data_solicitado'] = Controller_Helper_Date::getDatetimeNowDb();
                    $dadosPagamento['status'] = 0;
                    $dadosPagamento['processado'] = 0;
                    
                    $this->_modelPagamento->insert($dadosPagamento);
                    
                    // redirecionando para o site do pagseguro    
                    $this->_redirect($url);
                    
                } catch (PagSeguroServiceException $error) {
                    $this->view->messages = array(
                        array(
                            'error' => $error->getMessage()
                        )
                    );
                }
            }
        }
    }
    */

}

class PagSeguroNpi {

    private $timeout = 20; // Timeout em segundos

    public function notificationPost() {
        $postdata = 'Comando=validar&Token=' . TOKEN;
        foreach ($_POST as $key => $value) {
            $valued = $this->clearStr($value);
            $postdata .= "&$key=$valued";
        }
        return $this->verify($postdata);
    }

    private function clearStr($str) {
        if (!get_magic_quotes_gpc()) {
            $str = addslashes($str);
        }
        return $str;
    }

    private function verify($data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = trim(curl_exec($curl));
        curl_close($curl);
        return $result;
    }

}

