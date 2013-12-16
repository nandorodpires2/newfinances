<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cidade
 *
 * @author Realter
 */
class Model_Cidade extends Zend_Db_Table {

    protected $_name = "cidade";
    
    protected $_primary = "id_cidade";
    
    public function getCidade($id_cidade) {
        return $this->fetchRow("id_cidade = {$id_cidade}");
    }

}

