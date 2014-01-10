<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Realter
 */
class Site_IndexController extends Zend_Controller_Action {

    const LIMIT_FEEDS = 5;
    
    public function init() {        
        
        parent::init();
        
        $this->_helper->layout()->setLayout('site');
    }
    
    public function indexAction() {
        
        // enviando os feeds de noticia financeira        
        $feed_url = 'http://www.valor.com.br/financas/rss';
        
        $dadosFeed = array();
        
        if (Zend_Feed_Reader::import($feed_url)) {
            $feeds = Zend_Feed_Reader::import($feed_url);
            foreach ($feeds as $key => $feed) {
                if ($key <= self::LIMIT_FEEDS) {            
                    $dadosFeed[$key]['titulo'] = $feed->getTitle();
                    $dadosFeed[$key]['link'] = $feed->getLink();
                    $dadosFeed[$key]['data'] = $feed->getDateModified()->get(Zend_Date::DATETIME_MEDIUM);            
                }
            }
        }
        
        $this->view->dadosFeed = $dadosFeed;        
        
    }
    
}

