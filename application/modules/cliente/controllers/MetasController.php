<?php

class MetasController extends Zend_Controller_Action {

    protected $_session;
    
    protected $_modelMeta;
    protected $_modelCategoria;

    protected $_formMetasMeta;

    public function init() {
        
        $this->_session = Zend_Auth::getInstance()->getIdentity();       
        
        $this->_modelMeta = new Model_Meta();
        $this->_modelCategoria = new Model_Categoria();
        
        $this->_formMetasMeta = new Form_Metas_Meta();
        
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

}

