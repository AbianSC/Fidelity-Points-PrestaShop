<?php

require_once _PS_MODULE_DIR_ . 'myprojectmodule/classes/ProjectModel.php';
class MyProjectModulePointsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        // Obtener los puntos del cliente (o de lo que desees)
        $id_customer = (int)$this->context->customer->id;
        $points = ProjectModel::getPointsForCustomer($id_customer);

        // Asignar los puntos a Smarty
        $this->context->smarty->assign('points', $points);

        // Cargar la plantilla
        $this->setTemplate('module:myprojectmodule/views/templates/front/display.tpl');
    }
}


