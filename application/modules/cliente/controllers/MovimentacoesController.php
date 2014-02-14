<?php

class MovimentacoesController extends Application_Controller {

    const TIPO_MOVIMENTACAO_RECEITA = 1;
    const TIPO_MOVIMENTACAO_DESPESA = 2;
    const TIPO_MOVIMENTACAO_CARTAO = 3;
    const TIPO_MOVIMENTACAO_TRANSFERENCIA = 4;

    public function init()
    {   
        parent::init();
        
        // verifica se tem pelo menos uma conta cadastrada
        if (!Controller_Helper_Application::hasConta()) {        
            Controller_Helper_Messeges::setMesseges(array('alert' => 'É necessário cadastrar uma conta para acessar esta página'));
            $this->_redirect("configuracoes/nova-conta");
        } 
        
        // enviando as mensagens para a view
        $this->view->messages = Controller_Helper_Messeges::getMesseges();
        
    }

    public function indexAction() {        
        
        set_time_limit(60);
        
        // enviando os forms de filtros para a view
        $this->view->formMes = $this->_formMes;
        $this->view->formDias = $this->_formDias;
        $this->view->formBusca = $this->_formBusca;
        $this->view->formConta = $this->_formConta;
        
        // buscar as movimentacoes por dia do mes atual        
        $ano = (int)$this->_getParam("ano", date('Y'));
        $mes = (int)$this->_getParam("mes", date('m'));
        
        // caso tenha sido selecionado uuma conta
        $conta = $this->_getParam("conta", null);
        
        // popula o combo de mes
        $this->_formMes->populate(array("mes" => $mes, "ano" => $ano));
        
        // busca as datas onde possui lancamentos
        $datasMes = $this->_modelMovimentacao->getDatasMes($ano, $mes, $this->_session->id_usuario, $conta);        
        $this->view->datasMes = $datasMes;
        
    }
    
    /**
     * busca as movimentacoes da data (Ajax)
     */
    public function buscaLancamentosAction() {
    
        set_time_limit(3600);
        
        $this->_disabledLayout();
        
        // recupera a data
        $data = $this->_getParam("data");
        
        // busca as movimentacoes da data
        $movimentacoes = $this->_modelVwMovimentacao->getMovimentacoesData($data, $this->_session->id_usuario);        
        $this->view->movimentacoes = $movimentacoes;
        
        // busca os saldos previstos e realizados
        $this->view->saldoAnterior = Controller_Helper_Movimentacao::getSaldoAnterior($data, $this->_session->id_usuario);
        $this->view->saldoRealizado = Controller_Helper_Movimentacao::saldoDiaRealizado($data, $this->_session->id_usuario);
        $this->view->saldoPrevisto = Controller_Helper_Movimentacao::saldoDiaPrevisto($data, $this->_session->id_usuario);
        
    }

    /**
     * Nova Receita
     */
    public function novaReceitaAction() {
     
        $this->view->formMovimentacoesReceitas = $this->_formMovimentacoesReceitas;
        
        if ($this->_request->isPost()) {
            $dadosReceita = $this->_request->getPost();
            if ($this->_formMovimentacoesReceitas->isValid($dadosReceita)) {
                $dadosReceita = $this->_formMovimentacoesReceitas->getValues();
                                
                $dadosReceita['id_tipo_movimentacao'] = self::TIPO_MOVIMENTACAO_RECEITA;
                
                $dadosReceita['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosReceita['data_movimentacao']);
                $dadosReceita['data_movimentacao'] = Controller_Helper_Date::getDateDb($dadosReceita['data_movimentacao']);
                $dadosReceita['data_inclusao'] = Controller_Helper_Date::getDatetimeNowDb();
                $dadosReceita['id_cartao'] = null;
                $dadosReceita['valor_movimentacao'] = View_Helper_Currency::setCurrencyDb($dadosReceita['valor_movimentacao'], "positivo");
                
                
                // verificando se a movimentacao repete                
                if ((int)$dadosReceita['opt_repetir']) {
                    $dadosRepeticao = array();                    
                    $dadosRepeticao['tipo'] = $dadosReceita['modo_repeticao'];
                    $dadosRepeticao['processado'] = 0;
                    switch ($dadosReceita['modo_repeticao']) {
                        case 'fixo':                            
                            $dadosRepeticao['modo'] = $dadosReceita['repetir'];
                            break;;
                        case 'parcelado':
                            $dadosRepeticao['modo'] = $dadosReceita['parcelas'];
                            $dadosReceita['valor_movimentacao'] /= (int)$dadosReceita['parcelas'];
                            $dadosReceita['descricao_movimentacao'] .= ' - 1/' . (int)$dadosReceita['parcelas'];
                            break;
                    }
                }                
                
                // retirando campos nao necessarios                                
                $repetir = (int)$dadosReceita['opt_repetir'];
                unset($dadosReceita['repetir']);                
                unset($dadosReceita['opt_repetir']);
                unset($dadosReceita['parcelas']);                
                unset($dadosReceita['modo_repeticao']);
                
                try {
                    $this->_modelMovimentacao->insert($dadosReceita);
                    
                    // recuperando o id da movimentacao
                    if ($repetir) {                        
                        $dadosRepeticao['id_movimentacao'] = $this->_modelMovimentacao->lastInsertId();                    
                        $this->_modelMovimentacaoRepeticao->insert($dadosRepeticao);                    
                    }
                    
                    // recupera o id da movimentacao repeticao
                    $lastId = $this->_modelMovimentacaoRepeticao->lastInsertId();
                    
                    // atualiza o id pai 
                    $dadosUpdateMovimentacao['id_movimentacao_pai'] = $lastId;
                    $whereUpdateMovimentacao = "id_movimentacao = " . $this->_modelMovimentacao->lastInsertId();
                    $this->_modelMovimentacao->update($dadosUpdateMovimentacao, $whereUpdateMovimentacao);
                    
                    $this->_redirect("index/index");
                } catch (Zend_Exception $error) {
                    echo $error->getMessage();
                }
                            
            }
        }
        
    }
    
    /**
     * Nova Despesa
     */
    public function novaDespesaAction() {
        
        $this->view->formMovimentacoesDespesa = $this->_formMovimentacoesDespesa;
        
        if ($this->_request->isPost()) {
            $dadosDespesa = $this->_request->getPost();
            if ($this->_formMovimentacoesDespesa->isValid($dadosDespesa)) {
                $dadosDespesa = $this->_formMovimentacoesDespesa->getValues();
                
                $dadosDespesa['valor_movimentacao'] = View_Helper_Currency::setCurrencyDb($dadosDespesa['valor_movimentacao']);
                
                if ($dadosDespesa['tipo_pgto'] == 'conta') {
                    $dadosDespesa['id_tipo_movimentacao'] = self::TIPO_MOVIMENTACAO_DESPESA;
                    $dadosDespesa['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosDespesa['data_movimentacao']);
                    $dadosDespesa['id_cartao'] = null;
                } else {
                    $dadosDespesa['id_tipo_movimentacao'] = self::TIPO_MOVIMENTACAO_CARTAO;
                    $dadosDespesa['realizado'] = 1;
                    $dadosDespesa['id_conta'] = null;
                }
                
                // verificando se a movimentacao repete                
                if ((int)$dadosDespesa['opt_repetir']) {
                    $dadosRepeticao = array();                    
                    $dadosRepeticao['tipo'] = $dadosDespesa['modo_repeticao'];
                    $dadosRepeticao['processado'] = 0;
                    switch ($dadosDespesa['modo_repeticao']) {
                        case 'fixo':                            
                            $dadosRepeticao['modo'] = $dadosDespesa['repetir'];
                            break;;
                        case 'parcelado':
                            $dadosRepeticao['modo'] = $dadosDespesa['parcelas'];
                            $dadosDespesa['valor_movimentacao'] /= (int)$dadosDespesa['parcelas'];
                            $dadosDespesa['descricao_movimentacao'] .= ' - 1/' . (int)$dadosDespesa['parcelas'];
                            break;
                    }
                }
                
                $dadosDespesa['data_movimentacao'] = Controller_Helper_Date::getDateDb($dadosDespesa['data_movimentacao']);
                $dadosDespesa['data_inclusao'] = Controller_Helper_Date::getDatetimeNowDb();
                
                // retirando campos nao necessarios
                $repetir = (int)$dadosDespesa['opt_repetir'];
                unset($dadosDespesa['tipo_pgto']);           
                unset($dadosDespesa['opt_repetir']);
                unset($dadosDespesa['repetir']);                
                unset($dadosDespesa['parcelas']);                
                unset($dadosDespesa['modo_repeticao']);
                
                try {
                    $this->_modelMovimentacao->insert($dadosDespesa);
                    
                    // recuperando o id da movimentacao
                    if ($repetir) {
                        unset($dadosDespesa['opt_repetir']);
                        $dadosRepeticao['id_movimentacao'] = $this->_modelMovimentacao->lastInsertId();                    
                        $this->_modelMovimentacaoRepeticao->insert($dadosRepeticao);                    
                    }
                    
                    // recupera o id da movimentacao repeticao
                    $lastId = $this->_modelMovimentacaoRepeticao->lastInsertId();
                    
                    // atualiza o id pai 
                    $dadosUpdateMovimentacao['id_movimentacao_pai'] = $lastId;
                    $whereUpdateMovimentacao = "id_movimentacao = " . $this->_modelMovimentacao->lastInsertId();
                    $this->_modelMovimentacao->update($dadosUpdateMovimentacao, $whereUpdateMovimentacao);
                    
                    $this->_redirect("index/index");
                } catch (Zend_Exception $error) {
                    echo $error->getMessage();
                }            
                
            }
        }
        
    }
    
    /**
     * transferencia
     */
    public function transferenciaAction() {
        $this->view->formMovimentacaoTransferencia = $this->_formMovimentacoesTransferencia;
        
        if ($this->_request->isPost()) {
            $dadosTransferencia = $this->_request->getPost();
            if ($this->_formMovimentacoesTransferencia->isValid($dadosTransferencia)) {
                $dadosTransferencia = $this->_formMovimentacoesTransferencia->getValues();
                
                $id_conta_origem = $dadosTransferencia['id_conta_origem'];
                unset($dadosTransferencia['id_conta_origem']);
                
                $dadosTransferencia['id_tipo_movimentacao'] = self::TIPO_MOVIMENTACAO_TRANSFERENCIA;
                
                $dadosTransferencia['data_movimentacao'] = Controller_Helper_Date::getDateDb($dadosTransferencia['data_movimentacao']);
                $dadosTransferencia['data_inclusao'] = Controller_Helper_Date::getDatetimeNowDb();
                $dadosTransferencia['realizado'] = Controller_Helper_Movimentacao::getStatusMovimentacao($dadosTransferencia['data_movimentacao']);                
                $dadosTransferencia['valor_movimentacao'] = View_Helper_Currency::setCurrencyDb($dadosTransferencia['valor_movimentacao']) * -1;
                
                try {
                    $this->_modelMovimentacao->insert($dadosTransferencia);                 
                
                    // lancando a movimentacao de saida
                    $dadosTransferencia['id_conta'] = $id_conta_origem;
                    $dadosTransferencia['valor_movimentacao'] *= -1; 
                
                    $this->_modelMovimentacao->insert($dadosTransferencia);
                    $this->_redirect("index/index");                            
                } catch (Zend_Exception $error) {
                    echo $error->getMessage();
                }
                
            }
        }
    }
    
    /**
     * altera o status de previsto para realizado e vice-versa (AJAX)
     */
    public function statusAction() {
        
        // desabilitando o layout
        $this->_helper->layout->disableLayout(true);
        // desabilitando a view
        $this->_helper->viewRenderer->setNoRender(true);
        
        $id = $this->_getParam("id_movimentacao");
        $status = $this->_getParam("status");
        
        $where = "id_movimentacao = " . $id;
        
        $statusUpdate['realizado'] = ((bool)$status) ? 0 : 1;
        
        try {
            $this->_modelMovimentacao->update($statusUpdate, $where);
            
            // verificar de onde veio a solicitacao
            if(!isset($_GET['ajax'])) {
                $this->_redirect("index/");
            }
            
        } catch (Zend_Exception $error) {
            echo $error->getMessage();
        }
            
    }
    
    /**
     * editar movimentacao
     */
    public function editarMovimentacaoAction() {

        $idMovimentacao = $this->_getParam("id_movimentacao");
        // buscando os dados da movimentacao
        $dadosMovimentacao = $this->_modelMovimentacao->getDadosMovimentacao($idMovimentacao, $this->_session->id_usuario);

        // data da movimentacao
        $data_movimentacao = $dadosMovimentacao->data_movimentacao;
        
        if ($dadosMovimentacao) {

            $dadosMovimentacao = $dadosMovimentacao->toArray();
            
            // formatando alguns dados
            if ($dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_TRANSFERENCIA || 
                    $dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_RECEITA
                ) {
                $dadosMovimentacao['valor_movimentacao'] = number_format($dadosMovimentacao['valor_movimentacao'], 2, ',', '.');
            } else {
                $dadosMovimentacao['valor_movimentacao'] = number_format($dadosMovimentacao['valor_movimentacao'] * -1, 2, ',', '.');
            }
            
            $dadosMovimentacao['data_movimentacao'] = View_Helper_Date::getDataView($dadosMovimentacao['data_movimentacao']);
            
            if ($dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_CARTAO) {
                $dadosMovimentacao['tipo_pgto'] = 'cartao';
            } elseif ($dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_RECEITA || 
                    $dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_DESPESA
                ) {
                $dadosMovimentacao['tipo_pgto'] = 'conta';
            }
            
            // verifica se a movimentacao se repete
            if ($dadosMovimentacao['id_movimentacao_pai'] != null) {                 
                $this->view->dadosRepeticao = false;                
            } else {
                $this->view->dadosRepeticao = false;
            }
            
            $formUpdate = $this->populateMovimentacao($dadosMovimentacao);            
            
            // atualizando a movimentacao            
            if ($this->_request->isPost()) {
                $dadosMovimentacaoUpdate = $this->_request->getPost();
                if ($formUpdate->isValid($dadosMovimentacaoUpdate)) {
                    $dadosMovimentacaoUpdate = $formUpdate->getValues();
                    
                    Zend_Debug::dump($dadosMovimentacaoUpdate);
                    
                    if (isset ($dadosMovimentacaoUpdate['tipo_pgto'])) {                    
                        if ($dadosMovimentacaoUpdate['tipo_pgto'] == 'conta') {
                            $dadosMovimentacaoUpdate['id_tipo_movimentacao'] = self::TIPO_MOVIMENTACAO_DESPESA;
                            $dadosMovimentacaoUpdate['id_cartao'] = null;
                        } else {
                            $dadosMovimentacaoUpdate['id_tipo_movimentacao'] = self::TIPO_MOVIMENTACAO_CARTAO;
                            $dadosMovimentacaoUpdate['id_conta'] = null;
                        }                    
                        unset($dadosMovimentacaoUpdate['tipo_pgto']);
                    }
                    
                    $dadosMovimentacaoUpdate['data_movimentacao'] = Controller_Helper_Date::getDateDb($dadosMovimentacaoUpdate['data_movimentacao']);
                    
                    if ($dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_TRANSFERENCIA ||
                        $dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_RECEITA    
                        ) {
                        $dadosMovimentacaoUpdate['valor_movimentacao'] = View_Helper_Currency::setCurrencyDb($dadosMovimentacaoUpdate['valor_movimentacao']) * -1;
                    } else {
                        $dadosMovimentacaoUpdate['valor_movimentacao'] = View_Helper_Currency::setCurrencyDb($dadosMovimentacaoUpdate['valor_movimentacao']);
                    }
                                        
                    if ( isset ($dadosMovimentacaoUpdate['id_conta_origem'])) {
                        $id_conta_origem = $dadosMovimentacaoUpdate['id_conta_origem'];
                        unset($dadosMovimentacaoUpdate['id_conta_origem']);
                    }
                    
                    // retirando os campos que nao serao usados                    
                    unset($dadosMovimentacaoUpdate['opt_repetir']);
                    unset($dadosMovimentacaoUpdate['modo_repeticao']);
                    unset($dadosMovimentacaoUpdate['parcelas']);
                    unset($dadosMovimentacaoUpdate['repetir']);                    
                    unset($dadosMovimentacaoUpdate['modo_edicao']);                    
                    
                    $whereUpdate = "id_movimentacao = " . $idMovimentacao;
                    
                    try {
                        $this->_modelMovimentacao->update($dadosMovimentacaoUpdate, $whereUpdate);
                                                
                        // atualizando a movimentacao de origem no caso de transferencia
                        $idMovimentacao++;
                        $whereOrigem = "id_movimentacao = " . $idMovimentacao;
                        $dadosMovimentacaoUpdate['id_conta'] = $id_conta_origem;
                        $dadosMovimentacaoUpdate['valor_movimentacao'] *= -1; 

                        if ($dadosMovimentacao['id_tipo_movimentacao'] == self::TIPO_MOVIMENTACAO_TRANSFERENCIA) {                        
                            $this->_modelMovimentacao->update($dadosMovimentacaoUpdate, $whereOrigem);
                        }
                        
                        $this->_redirect("index/index");
                    } catch (Exception $erro) {
                        echo $erro->getMessage();
                    }
                                    
                }
            }
            
        } else {
            $messeges = array(
                array(
                    "error" => "Página não encontrada."
                )                
            );
            $this->view->messages = $messeges;
        }
    }

    /**
     * excluir movimentacao
     */
    public function excluirMovimentacaoAction() {
        
        $idMovimentacao = $this->_getParam("id_movimentacao");
        
        // buscando os dados da movimentacao
        $dadosMovimentacao = $this->_modelMovimentacao->getDadosMovimentacao($idMovimentacao, $this->_session->id_usuario);
        $this->view->dadosMovimentacao = $dadosMovimentacao;
        
        if ($this->_request->isPost()) {
            $dadosExclusao = $this->_request->getPost();
            
            if ($dadosExclusao['btnResposta'] == 'Cancelar') {
                
                $this->_redirect("movimentacoes/");
                
            } else {                                
                
                $dadosVwMovimentacao = $this->_modelVwMovimentacao->fetchRow("id_movimentacao = {$idMovimentacao}");
                
                if ($dadosVwMovimentacao->id_tipo_movimentacao == 4) {
                    $where = "id_movimentacao in ({$idMovimentacao}, {$dadosVwMovimentacao->id_movimentacao_origem})";
                } else {               
                    $where = "id_movimentacao = " . $idMovimentacao;
                }
                
                try {
                    $this->_modelMovimentacao->delete($where);
                    $this->_redirect("index/");
                } catch (Exception $error) {
                    echo $error->getMessage(); die('aki');
                }
                
            }                        
            
        }
        
    }
    
    protected function populateMovimentacao($dadosMovimentacao) {
        
        switch ($dadosMovimentacao['id_tipo_movimentacao']) {
            case self::TIPO_MOVIMENTACAO_RECEITA:
                if ($dadosMovimentacao['id_movimentacao_pai'] != null) {
                    $this->_formMovimentacoesReceitas->addElement('radio', 'modo_edicao', array(
                        'label' => 'Essa movimentacao se repete, escolha um modo de edição:',
                        'multioptions' => array(
                            1 => 'Atualizar somente o registro atual',
                            2 => 'Atualizar este registro e todos os anteriores',
                            3 => 'Atualizar este registro e todos os futuros',
                            4 => 'Atualizar todos os registros'
                        ),
                        'value' => 1
                    ));
                }
                $this->_formMovimentacoesReceitas->populate($dadosMovimentacao);
                $this->view->formMovimentacoes = $this->_formMovimentacoesReceitas;
                return $this->_formMovimentacoesReceitas;
                break;
            case self::TIPO_MOVIMENTACAO_DESPESA:
                if ($dadosMovimentacao['id_movimentacao_pai'] != null) {
                    $this->_formMovimentacoesDespesa->addElement('radio', 'modo_edicao', array(
                        'label' => 'Essa movimentacao se repete, escolha um modo de edição:',
                        'multioptions' => array(
                            1 => 'Atualizar somente o registro atual',
                            2 => 'Atualizar este registro e todos os anteriores',
                            3 => 'Atualizar este registro e todos os futuros',
                            4 => 'Atualizar todos os registros'
                        ),
                        'value' => 1
                    ));
                }
                $this->_formMovimentacoesDespesa->populate($dadosMovimentacao);
                $this->view->formMovimentacoes = $this->_formMovimentacoesDespesa;
                return $this->_formMovimentacoesDespesa;
                break;
            case self::TIPO_MOVIMENTACAO_TRANSFERENCIA:                
                $this->_formMovimentacoesTransferencia->populate($dadosMovimentacao);
                $this->view->formMovimentacoes = $this->_formMovimentacoesTransferencia;
                return $this->_formMovimentacoesTransferencia;
                break;
            case self::TIPO_MOVIMENTACAO_CARTAO:
                $this->_formMovimentacoesDespesa->populate($dadosMovimentacao);
                $this->view->formMovimentacoes = $this->_formMovimentacoesDespesa;
                return $this->_formMovimentacoesDespesa;
                break;
            default :
                die('Nenhum tipo');
                break;
        }
        
    }

}

