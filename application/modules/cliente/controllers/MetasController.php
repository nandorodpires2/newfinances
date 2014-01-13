<?php

class MetasController extends Application_Controller {

    public function init() {
<<<<<<< HEAD
        parent::init();        
=======
        parent::init();
>>>>>>> 60f6981b3a1b4aac3e479854f94865b84890d4b5
    }

    public function indexAction() {
     
        $this->view->formMetas = $this->_formMetasMeta;
        
        // recupera as metas ja cadastradas para este mes
        $metasUsuario = $this->_modelMeta->getMetasUsuario($this->_session->id_usuario);
        $this->view->metasUsuario = $metasUsuario;
                
        $total_meta = $this->_modelMeta->getTotalMetaMes($this->_session->id_usuario, date('m'), date('Y'));
        $this->view->total_meta= $total_meta;
        
        if ($this->_request->isPost()) {
            $dadosMeta = $this->_request->getPost();
            if ($this->_formMetasMeta->isValid($dadosMeta)) {
                $dadosMeta = $this->_formMetasMeta->getValues();
                
                $dadosMeta['valor_meta'] = View_Helper_Currency::setCurrencyDb($dadosMeta['valor_meta'], "positivo");
                $dadosMeta['data_cadastro'] = Controller_Helper_Date::getDatetimeNowDb();
                
                try {
                    $this->_modelMeta->insert($dadosMeta);                    
                } catch (Exception $error) {
                    echo $error->getMessage();
                }
                
                if ($dadosMeta['repetir'] == 1) {
                    $data = $dadosMeta['ano_meta'] . '-' . $dadosMeta['mes_meta'] . '-' . date('d');
                    $zendDate = new Zend_Date($data);                    
                    $dadosInsert = array();
                    $dadosInsert['valor_meta'] = $dadosMeta['valor_meta'];
                    $dadosInsert['id_categoria'] = $dadosMeta['id_categoria'];
                    $dadosInsert['id_usuario'] = $dadosMeta['id_usuario'];
                    $dadosInsert['repetir'] = 0;
                    for ($i = 1; $i <= 12; $i++) {    
                        $dadosInsert['mes_meta'] = $zendDate->addMonth(1)->toString("MM");                        
                        $dadosInsert['ano_meta'] = $zendDate->toString("yyyy");
                        $this->_modelMeta->insert($dadosInsert);
                    }
                }
                $this->_redirect("metas/");
            }
        }
        
    }
    
    /**
     * editar meta
     */
    public function editarMetaAction() {
        
        $id_meta = (int)$this->_getParam("id_meta");
        
        $meta = $this->_modelMeta->fetchRow("id_meta = {$id_meta} and id_usuario = {$this->_session->id_usuario}")->toArray();
        $this->_formMetasMeta->removeElement("repetir");
        $this->_formMetasMeta->submit->setLabel("Alterar");
        
        $this->_formMetasMeta->addElement("hidden", "id_categoria", array('value' => $meta['id_categoria']));
        
        $this->_formMetasMeta->populate($meta);        
        $this->view->formMeta = $this->_formMetasMeta;
        
        // recupera as metas ja cadastradas para este mes
        $metasUsuario = $this->_modelMeta->getMetasUsuario($this->_session->id_usuario);
        $this->view->metasUsuario = $metasUsuario;
        
        if ($this->_request->isPost()) {
          $dadosMeta = $this->_request->getPost();
          if ($this->_formMetasMeta->isValid($dadosMeta)) {
              $dadosMeta = $this->_formMetasMeta->getValues();
              
              $dadosMeta['valor_meta'] = View_Helper_Currency::setCurrencyDb($dadosMeta['valor_meta'], "positivo");
              $whereUpdate = "id_meta = " . $id_meta;
              
              try {
                  $this->_modelMeta->update($dadosMeta, $whereUpdate);
                  $this->_redirect("metas/");
              } catch (Exception $exc) {
                  echo $exc->getTraceAsString();
              }                        
              
          }
        }
        
    }
    
    /**
     * excluir meta
     */
    public function excluirMetaAction() {        
        $this->_helper->viewRenderer->setNoRender(true);        
        $id_meta = (int)$this->_getParam("id_meta");
        $meta = $this->_modelMeta->fetchRow("id_meta = {$id_meta} and id_usuario = {$this->_session->id_usuario}");
        
        $where = "id_meta = " . $id_meta;   
        
        // apaga todos os lancamentos futuros
        $whereRepeticao = "
            id_usuario = {$this->_session->id_usuario}
            and id_categoria = {$meta->id_categoria}
            and data_cadastro = '{$meta->data_cadastro}'
        ";        
        
        try {
            $this->_modelMeta->delete($where);
            $this->_modelMeta->delete($whereRepeticao);
            $this->_redirect("metas/");
        } catch (Zend_Db_Exception $error) {
            echo $error->getMessage();
        }   
        
    }
    
    /**
<<<<<<< HEAD
     * sugestao de valor de meta
     * baseado nos gastos dos meses anteriores
     */
    public function sugestValuesAction() {
                
=======
     * sugestao de valor para meta
     */
    public function valSugestAction() {
        
        // desabilita o layout e view
>>>>>>> 60f6981b3a1b4aac3e479854f94865b84890d4b5
        $this->_disabledLayout();
        $this->_disabledView();
        
        // recupera o id da categoria
        $id_categoria = $this->_getParam("id_categoria");
        
<<<<<<< HEAD
        // buscar no banco 
        /*
        select	avg(m.valor_meta)
        from	meta m
        where	m.id_categoria = 5
                and m.id_usuario = 1
        */
        
=======
        // busca a media de gastos mensais para a categoria
        $mediaMovimentacao = $this->_modelMovimentacao->getMediaMovimentacaoCategoria($id_categoria, $this->_session->id_usuario);
        
        if ($mediaMovimentacao->count() > 0) {
            $total = 0;
            foreach ($mediaMovimentacao as $valores) {                        
                $total += $valores->total;           
            }                

            $media = ($total / $mediaMovimentacao->count()) * -1;

            echo View_Helper_Currency::getCurrency($media);                    
        } else {
            echo " - ";
        }
>>>>>>> 60f6981b3a1b4aac3e479854f94865b84890d4b5
    }

}

