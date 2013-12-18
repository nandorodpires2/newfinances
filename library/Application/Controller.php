<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPLICATION_PATH . '/../library/PagSeguroLibrary/PagSeguroLibrary.php';

/**
 * Description of Controller
 *
 * @author Realter
 */
class Application_Controller extends Zend_Controller_Action {
    
    
    /*
    1	Aguardando pagamento: o comprador iniciou a transação, mas até o momento o PagSeguro não recebeu nenhuma informação sobre o pagamento.	 WAITING_PAYMENT
    2	Em análise: o comprador optou por pagar com um cartão de crédito e o PagSeguro está analisando o risco da transação.	 IN_ANALYSIS
    3	Paga: a transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento.	 PAID
    4	Disponível: a transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta.	 AVAILABLE
    5	Em disputa: o comprador, dentro do prazo de liberação da transação, abriu uma disputa.	 IN_DISPUTE
    6	Devolvida: o valor da transação foi devolvido para o comprador.	 REFUNDED
    7	Cancelada: a transação foi cancelada sem ter sido finalizada.	 CANCELLED
    */
    
    /* CONSTANTS PAGSEGURO STATUS */
    const WAITING_PAYMENT = 1;
    const IN_ANALYSIS = 2;
    const PAID = 3;
    const AVAILABLE = 4;
    const IN_DISPUTE = 5;
    const REFUNDED = 6;
    const CANCELLED = 7;
    
    /* SESSIONS */
    public $_hasIdentity;
    public $_session;
    
    /* MODELS */
    public $_modelBanco;
    public $_modelUsuarioPlano;
    public $_modelPlanoValor;
    public $_modelUsuario;
    public $_modelPlano;
    public $_modelPagamento;
    public $_modelCartao;
    public $_modelConta;
    public $_modelMovimentacao;
    public $_modelCategoria;

    /* FORMS */
    public $_formUsuariosPlanosUsuario;
    public $_formUsuariosLogin;
    public $_formNovoUsuario;    
    public $_formConfiguracoesCartao;
    public $_formConfiguracoesConta;
    public $_formConfiguracoesSenha;
    // form plano valor
    public $_formPlanosValor;
    // form para nova categoria
    public $_formNovaCategoria;
    // form de recuperacao de senha
    public $_formUsuariosRecuperarSenha;

    /* PAGSEGURO CREDENTIALS */
    public $_credentials;

    public function init() {
     
        $this->_hasIdentity = Zend_Auth::getInstance()->hasIdentity(); 
        $this->_session = $this->_hasIdentity ? Zend_Auth::getInstance()->getIdentity() : null;
    
        $this->_modelUsuarioPlano = new Model_UsuarioPlano();
        $this->_modelPlanoValor = new Model_PlanoValor();
        $this->_modelUsuario = new Model_Usuario();
        $this->_modelBanco = new Model_Banco();
        $this->_modelPlano = new Model_Plano();
        $this->_modelPagamento = new Model_Pagamento();        
        $this->_modelCartao = new Model_Cartao();
        $this->_modelConta = new Model_Conta();
        $this->_modelMovimentacao = new Model_Movimentacao();
        $this->_modelCategoria = new Model_Categoria();
        
        
        $this->_formUsuariosPlanosUsuario = new Form_Usuarios_PlanoUsuario();
        $this->_formUsuariosLogin = new Form_Usuarios_Login();
        $this->_formNovoUsuario = new Form_Usuarios_NovoUsuario();        
        $this->_formConfiguracoesCartao = new Form_Configuracoes_Cartao();
        $this->_formConfiguracoesConta = new Form_Configuracoes_Conta();        
        $this->_formConfiguracoesSenha = new Form_Configuracoes_Senha();
        $this->_formPlanosValor = new Form_Planos_Valor();
        $this->_formNovaCategoria = new Form_Categorias_NovaCategoria();
        $this->_formUsuariosRecuperarSenha = new Form_Usuarios_RecuperarSenha();
        
        $credentials = new PagSeguroAccountCredentials(  
            'nandorodpires@gmail.com',   
            '1E3A21173CC2409EBB2DE17317045312' 
        );
        $this->setCredentials($credentials);
        
    }
    
    public function setLayout($layout) {
        $this->_helper->layout->setLayout($layout);
    }
    
    public function _disabledLayout() {
        return $this->_helper->layout->disableLayout();
    }
    
    public function _disabledView() {
        return $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function setCredentials(PagSeguroAccountCredentials $values) {
        $this->_credentials = $values;
    }
    
    public function getCredentials() {
        return $this->_credentials;
    }
    
    public function getNameStatusTransaction($status) {
        
        $status_name = "";
        switch ($status) {
            case self::WAITING_PAYMENT:
                $status_name = "Aguardando";
                break;
            case self::IN_ANALYSIS:
                $status_name = "Em análise";
                break;
            case self::PAID:
                $status_name = "Pago";
                break;
            case self::AVAILABLE:
                $status_name = "Disponível";
                break;
            case self::IN_DISPUTE:
                $status_name = "Em disputa";
                break;
            case self::REFUNDED:
                $status_name = "Devolvido";
                break;
            case self::CANCELLED:
                $status_name = "Cancelado";
                break;
            default:
                $status_name = "";
                break;
        }
        
        return $status_name;
        
    }
}

