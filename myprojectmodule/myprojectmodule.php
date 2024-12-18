<?php

require_once _PS_MODULE_DIR_ . 'myprojectmodule/classes/ProjectModel.php';

if (!defined('_PS_VERSION_')) {
    exit;
}
class MyProjectModule extends Module {

    public function __construct() {
        $this->name = 'myprojectmodule';
        $this->version = '1.0.0';
        $this->author = 'Abian';
        $this->need_instance = 0;
        $this->displayName = $this->l('My Project Module');
        $this->description = $this->l('Mi primer proyecto en PrestaShop');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        parent::__construct();

    }

    public function install() {

        if (!parent::install()) {
            return false;
        }


        if(!$this->registerHook('displayCustomerAccount')){
            return false;
        }


        if (!(new ProjectModel)->installDb()){
            return false;
        }

        if (!($this->registerHook('actionValidateOrder'))){
            return false;
        }

        return true;
    }

    public function uninstall() {

        if (!parent::uninstall()) {
            return false;
        }

        if (!Configuration::deleteByName('NEW_MODULE_CONFIG')) {
            return false;
        }

        if (!(new ProjectModel)->uninstallDb()){
            return false;
        }
        return true;
    }

    public function hookDisplayCustomerAccount()
    {
        $link = Context::getContext()->link->getModuleLink('myprojectmodule', 'points');

        return '<a class="col-lg-4 col-md-6 col-sm-6 col-xs-12" id="psgdpr-link" href="' . $link . '">
                    <span class="link-item">
                        <i class="material-icons">emoji_events</i> PuniPoints
                    </span>
                </a>';
    }


    public function hookActionValidateOrder($params)
    {
        // Obtenemos el pedido y el cliente
        $order = $params['order'];
        $customer = $params['customer'];

        // Comprobamos si el cliente está autenticado
        if (!$customer || !$order) {
            return;
        }

        // Definir los puntos que el cliente ganará con este pedido.
        $pointsToAdd = (int)$order->total_paid_tax_incl;

        // Llamamos al modelo para agregar los puntos
        ProjectModel::addPointsForCustomer($customer->id, $pointsToAdd);
    }


    public function getContent()
    {
        // Obtener los puntos de los clientes desde la base de datos
        $customersPoints = Db::getInstance()->executeS('
        SELECT p.id_customer, c.firstname, c.lastname, p.points
        FROM ' . _DB_PREFIX_ . 'project_table p
        JOIN ' . _DB_PREFIX_ . 'customer c ON p.id_customer = c.id_customer
    ');

        if ($customersPoints === false || !is_array($customersPoints)) {
            $customersPoints = [];
        }


        // Asignar los datos de los clientes con sus puntos a Smarty
        $this->context->smarty->assign(
            'customers_points', $customersPoints
        );

        // Mostrar la plantilla de configuración con la tabla
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }
}