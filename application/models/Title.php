<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Title
 *
 * @author Fernando Rodrigues
 */
class Model_Title extends Zend_Db_Table {
    
    protected $_name = "title";
    
    protected $_primary = "id_title";
    
    protected $_rows = array(
        'id_title',
        'module',
        'contorller',
        'action',
        'title'
    );
    
}

