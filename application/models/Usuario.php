<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author Fernando Rodrigues
 */
class Model_Usuario extends Zend_Db_Table {

    protected $_name = 'usuario';
    
    protected $_primary = 'id_usuario';
    
    public function login(array $dados) {
        
        $email = $dados['email_usuario'];
        $senha = $dados['senha_usuario'];
        
        $db = $this->getAdapter();

        //cria o adaptador
        $adapter = new Zend_Auth_Adapter_DbTable($db);

        //define o nome da tabela
        $adapter->setTableName($this->_name)
            //define o campo de identidade ( email, senha )
            ->setIdentityColumn('email_usuario')
            //define o campo de senha
            ->setCredentialColumn('senha_usuario')
            //define o tratameno da senha
            ->setCredentialTreatment('md5(?)')
            //define o usuario informado
            ->setIdentity($email)
            //define a senha informada
            ->setCredential($senha);
        //define que apenas usuarios ativos poder�o logar
        $adapter->getDbSelect()->where('ativo_usuario = ?', 1 );

        return $adapter;
    }
    
    /**
     * retorna o ultimo id
     */
    public function lastInsertId() {
        $select = $this->select()
                ->from($this->_name, array(
                    'last_id' => 'last_insert_id(id_usuario)'
                ))
                ->order("id_usuario desc")
                ->limit(1);
        
        $query = $this->fetchRow($select);
        
        return (int)$query->last_id;
    }
    
    /**
     * valida usuario
     */
    public function validaUsuario($email, $senha) {
        $query = $this->fetchRow("email_usuario = '{$email}' and senha_usuario = '{$senha}'");
        
        if ($query) {
            return true;
        }
        return false;
    }
    
    /**
     * retorna todos os dados do usuario
     */
    public function getDadosUsuario($email) {
        
        $select = $this->select()
                ->from(array('u' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('up' => 'usuario_plano'), 'u.id_usuario = up.id_usuario', array('*'))
                ->joinInner(array('p' => 'plano'), 'up.id_plano = p.id_plano', array(
                    'p.descricao_plano'
                ))
                ->where("u.email_usuario = ?", $email)
                ->where("u.ativo_usuario = ?", 1)
                ->where("up.ativo_plano = ?", 1);                
        
        return $this->fetchRow($select);
        
    }
    
    /**
     * retorna todos os dados do usuario
     */
    public function getDadosUsuarioCpf($cpf) {
        
        $select = $this->select()
                ->from(array('u' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('up' => 'usuario_plano'), 'u.id_usuario = up.id_usuario', array('*'))
                ->joinInner(array('p' => 'plano'), 'up.id_plano = p.id_plano', array(
                    'p.descricao_plano'
                ))
                ->where("u.cpf_usuario = ?", $cpf)
                ->where("u.ativo_usuario = ?", 1)
                ->where("up.ativo_plano = ?", 1);                
        
        return $this->fetchRow($select);
        
    }
    
    /**
     * retorna todos os dados do usuario
     */
    public function getUsuario($id_usuario) {
        
        $select = $this->select()
                ->from(array('u' => $this->_name), array('*'))
                ->setIntegrityCheck(false)
                ->joinInner(array('up' => 'usuario_plano'), 'u.id_usuario = up.id_usuario', array('*'))
                ->where("u.id_usuario = ?", $id_usuario)
                ->where("u.ativo_usuario = ?", 1)
                ->where("up.ativo_plano = ?", 1);                
                
        return $this->fetchRow($select);
        
    }
    
    /**
     * qtde usuarios ativos
     */
    public function getQtdeUsuariosAtivos() {
        $query = $this->fetchAll("ativo_usuario = 1");
        return $query->count();
    }
    
    /**
     * usuarios ativos
     */
    public function getUsuariosAtivos() {
        return $this->fetchAll("ativo_usuario = 1");
        
    }
    
    /**
     * retorna uma lista de todos os usuarios do sistema
     */
    public function getListaDadosUsuarios() {
        
        $select = $this->select()
                ->from(array('u' => $this->_name), array(
                    '*'
                ))                
                ->setIntegrityCheck(false)
                ->joinInner(array('up' => 'usuario_plano'), 'u.id_usuario = up.id_usuario', array(
                    '*'
                ))
                ->joinInner(array('p' => 'plano'), 'up.id_plano = p.id_plano', array(
                    '*'
                ))
                ->joinInner(array('pv' => 'plano_valor'), 'up.id_plano_valor = pv.id_plano_valor', array(
                    '*'
                ))                
                ->joinInner(array('e' => 'estado'), 'u.id_estado = e.id_estado', array(
                    '*'
                ))        
                ->where("up.ativo_plano = ?", 1)
                ->group("u.id_usuario")
                ->order("u.nome_completo asc");                
        
        return $this->fetchAll($select);
    }
}
