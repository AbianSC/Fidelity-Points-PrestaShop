<?php

class ProjectModel extends ObjectModel {

    public $id;
    public $id_customer;  // Agregar esta columna para relacionar puntos con el cliente
    public $points;

    public static $definition = [
        'table' => 'project_table',
        'primary' => 'id_project',
        'fields' => [
            'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true],
            'points' => ['type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => true],
        ],
    ];

    public function installDb()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'project_table` (
            `id_project` INT(11) NOT NULL AUTO_INCREMENT,
            `id_customer` INT(11) NOT NULL,
            `points` INT NOT NULL,
            PRIMARY KEY (`id_project`),
            INDEX(`id_customer`)  
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        try {
            if (!Db::getInstance()->execute($sql)) {
                throw new Exception('Table creation failed');
            }
        } catch (Exception $e) {
            PrestaShopLogger::addLog($e->getMessage(), 3, null, 'MyProjectModule', (int)$this->id);
            return false;
        }

        return true;
    }

    public function uninstallDb()
    {
        $sql = 'DROP TABLE IF EXISTS ' . _DB_PREFIX_ . 'project_table';
        return Db::getInstance()->execute($sql);
    }

    // Método para agregar puntos a un cliente
    public static function addPointsForCustomer($id_customer, $points)
    {
        // Verifica si el cliente ya tiene puntos
        $sql = 'SELECT `points` FROM `' . _DB_PREFIX_ . 'project_table` WHERE `id_customer` = ' . (int)$id_customer;
        $existingPoints = Db::getInstance()->getValue($sql);

        if ($existingPoints !== false) {
            // Si el cliente ya tiene puntos, sumarlos
            $newPoints = $existingPoints + $points;
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'project_table` SET `points` = ' . (int)$newPoints . ' WHERE `id_customer` = ' . (int)$id_customer;
        } else {
            // Si el cliente no tiene puntos, insertarlos
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'project_table` (`id_customer`, `points`) VALUES (' . (int)$id_customer . ', ' . (int)$points . ')';
        }

        return Db::getInstance()->execute($sql);
    }

    // Método para obtener puntos de un cliente
    public static function getPointsForCustomer($id_customer)
    {
        $sql = 'SELECT `points` FROM `' . _DB_PREFIX_ . 'project_table` WHERE `id_customer` = ' . (int)$id_customer;
        return Db::getInstance()->getValue($sql);
    }


}
