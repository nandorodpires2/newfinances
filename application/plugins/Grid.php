<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grid
 *
 * @author Realter
 */
class Plugin_Grid extends Zend_Controller_Plugin_Abstract {
    
    public function grid() {

        //$config = new Zend_Config_Ini(sprintf('%s/configs/grid.ini', APPLICATION_PATH), APPLICATION_ENV);
        $grid = Bvb_Grid::factory('Table');
        $grid->setView(new Zend_View(array("encoding" => "UTF-8")));
        //$grid->setEscapeOutput(false);
        //$form->setUseDecorators(false); 
        $grid->setDeleteConfirmationPage(false); // página para confirmação do cadastro
        $grid->setExport(array()); // Retirar os links de exportação do grid
        $grid->setAlwaysShowOrderArrows(false);
        $grid->setNoFilters(true);

        return $grid;

    }
    
}

