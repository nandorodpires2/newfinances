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
                    $this->_redirect("planos/altera-plano/id_plano/{$dadosPlanoUsuario['id_plano']}");                    
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
                        
                        // envia o email para o usario  
                        $mail = new Zend_Mail('utf-8');

                        $data_encerramento = Controller_Helper_Date::getDateViewComplete($dadosPlanoUsuarioNovo['data_encerramento']);
                        
                        $message = "                            
                            <p>Olá {$dadosUsuario->nome_completo}</p>
                            <p>Seu plano foi alterado com sucesso!</p>                            
                            <p>De: {$dadosPlanoAtual->descricao_plano} para: {$cobranca->descricao_plano}</p> 
                            <p>Data Encerramento: {$data_encerramento}</p>    
                        ";

                        $mail->setBodyHtml($message);
                        $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                        $mail->addTo($dadosUsuario->email_usuario);
                        $mail->setSubject('Plano Alterado');

                        $mail->send(Zend_Registry::get('mail_transport'));
                        
                        // envia a mensagem para o gestor do sistema
                        $mail = new Zend_Mail('utf-8');

                        $message = "                            
                            <p>O plano do usuário {$dadosUsuario->nome_completo} foi alterado</p>                            
                            <p>De: {$dadosPlanoAtual->descricao_plano} para: {$cobranca->descricao_plano}</p>
                            <p>Data Encerramento: {$data_encerramento}</p>
                        ";

                        $mail->setBodyHtml($message);
                        $mail->setFrom('newfinances@newfinances.com.br', 'NewFinances - Controle Financeiro');
                        $mail->addTo("nandorodpires@gmail.com");
                        $mail->setSubject('Plano Alterado');

                        $mail->send(Zend_Registry::get('mail_transport'));
                        
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
    
    public function alteraPlanoAction() {
        $id_plano = $this->_getParam("id_plano");
        
        // dados do usuario
        $dadosUsuario = $this->_modelUsuario->find($this->_session->id_usuario)->current();
        $cpf_usuario = Controller_Helper_Application::formatCPF($dadosUsuario->cpf_usuario);
        
        // buscando os dados do plano escolhido
        $dadosPlano = $this->_modelPlanoValor->getPlanoValorUsuario($id_plano);
        
        // calculando a validade do plano
        $usuarioPlano = $this->_modelUsuarioPlano->getPlanoAtual($this->_session->id_usuario);
        if ($usuarioPlano->tempo_plano == 30) {
            $validadePlano = Controller_Helper_Date::getDataEncerramentoPlano(null, $id_plano);
        } else {
            $validadePlano = Controller_Helper_Date::getDataEncerramentoPlano($usuarioPlano->data_encerramento, $id_plano);
        }
        
        $this->view->validade_plano = $validadePlano;        
        $this->view->dadosPlano = $dadosPlano;
        
        if ($this->_request->isPost()) {    
            
            try {
                
                // grava a solicitacao de pagamento na tabela pagamento
                $dadosPagamento['id_usuario'] = $this->_session->id_usuario;
                $dadosPagamento['id_plano'] = $dadosPlano->id_plano;
                $dadosPagamento['id_plano_valor'] = $dadosPlano->id_plano_valor;
                $dadosPagamento['data_solicitado'] = Controller_Helper_Date::getDatetimeNowDb();
                $dadosPagamento['status'] = 0;
                $dadosPagamento['processado'] = 0;

                $this->_modelPagamento->insert($dadosPagamento);
                
                // recupera o ultimo id inserido
                $last_id = $this->_modelPagamento->getLastId();
                
                $paymentRequest = new PagSeguroPaymentRequest();
                $paymentRequest->addItem('0001', $dadosPlano->descricao_plano, 1, $dadosPlano->valor_plano);
                $paymentRequest->setCurrency("BRL");

                $paymentRequest->setSenderName($dadosUsuario->nome_completo);
                $paymentRequest->setSenderEmail($dadosUsuario->email_usuario);
                //$paymentRequest->setSender($dadosUsuario->nome_completo, $dadosUsuario->email_usuario);
                $paymentRequest->addParameter('senderCPF', $cpf_usuario);
                $paymentRequest->setReference($last_id);
                                
                // Informando as credenciais  
                $credentials = $this->_credentials;

                // fazendo a requisição a API do PagSeguro pra obter a URL de pagamento                  
                $url = $paymentRequest->register($credentials);
                
                // redirecionando para o site do pagseguro    
                $this->_redirect($url);
            } catch (PagSeguroServiceException $error) {
                Controller_Helper_Messeges::setMesseges(array(
                    'error' => 'Houve um erro na comunicação com o PagSeguro'
                ));
                Zend_Debug::dump($error->getMessage()); die();
            }
        }
    }
    
    public function responsePlanoAction() {

        $credentials = $this->_credentials;
        
        $data = "<p>Dados enviados</p>";
        
        $type = "";
        
        if (count($_POST) > 0) {
	
            $type = "post";
            
            $post = Zend_Debug::dump($_POST);
            
            // POST recebido, indica que é a requisição do NPI.
            $npi = new PagSeguroNpi();
            $result = $npi->notificationPost();

            $transaction_id = isset($_POST['TransacaoID']) ? $_POST['TransacaoID'] : '';

            if ($result == "VERIFICADO") {
                //O post foi validado pelo PagSeguro.
            } else if ($result == "FALSO") {
                //O post não foi validado pelo PagSeguro.
            } else {
                //Erro na integração com o PagSeguro.
            }
            
            $data .= "<p>Resultado: {$result}</p>";
            $data .= "<p>POST: <pre>{$post}</pre></p>";

        } else {
            
            // recupera o id da transacao
            $transaction_id = $this->_getParam("transaction_id", null);            
            // busca os dados da transacao
            $transaction = $this->getTransaction($transaction_id);            
            // status 
            $status = (int)$transaction->getStatus()->getValue();            
            // busca a referencia
            $reference = (int)$transaction->getReference();            
            // processa a transacao a partir do status
            $this->processaTransacao($status, $reference, $transaction_id);
            
            $type = "redircionamento";
            $data .= "<p>Status: {$status}</p>";
        }  
        
        // envia o status para a view
        $this->view->status = $status;
        
        // enviando os dados do usuario para a view
        $this->view->nomeCompleto = $this->_session->nome_completo;
                
    }
    
     /**
     * VALIDA O CUPOM DE DESCONTO (ajax)
     */
    public function validaCupomAction() {
        $this->_disabledLayout();
        $this->_disabledView();
        
        $cupom = $this->_getParam("cupom");
        $id_plano = $this->_getParam("id_plano");
        
        $dadosPlano = $this->_modelPlanoValor->getPlanoValorUsuario($id_plano);        
        $dadosCupomDesconto = $this->_modelCupomDesconto->getDescontoCupom($cupom, $dadosPlano->usuario);                                                        
        
        if ($dadosCupomDesconto) {
            // calcula o desconto
            $desconto = ($dadosPlano->valor_plano * ($dadosCupomDesconto->porcentagem / 100)) * -1;                                      
        } else {
            $desconto = 0;
        }     
        
        echo View_Helper_Currency::getCurrency($desconto);
    }

    /**
     * VALIDA O CUPOM DE DESCONTO (ajax)
     */
    public function recalculaValorAction() {
        $this->_disabledLayout();
        $this->_disabledView();
        
        $valor_plano = (float)$this->_getParam("valor_plano");
        $cupom = $this->_getParam("cupom");
        $id_plano = $this->_getParam("id_plano");
        
        $dadosPlano = $this->_modelPlanoValor->getPlanoValorUsuario($id_plano);        
        $dadosCupomDesconto = $this->_modelCupomDesconto->getDescontoCupom($cupom, $dadosPlano->usuario);                                                        
        
        if ($dadosCupomDesconto) {
            // calcula o desconto
            $desconto = ($dadosPlano->valor_plano * ($dadosCupomDesconto->porcentagem / 100)) * -1;                                      
            $novo_valor = $valor_plano - $desconto;
        } else {
            $novo_valor = $valor_plano;
        }     
        
        echo View_Helper_Currency::getCurrency($novo_valor);
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

}

define('TOKEN', '1E3A21173CC2409EBB2DE17317045312');
header('Content-Type: text/html; charset=ISO-8859-1');

class PagSeguroNpi {

    private $timeout = 50; // Timeout em segundos

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


