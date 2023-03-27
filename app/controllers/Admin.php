<?php
namespace controllers;
use Ubiquity\contents\transformation\TransformersManager;
use Ubiquity\controllers\admin\UbiquityMyAdminBaseController;
use Ubiquity\orm\DAO;

class Admin extends UbiquityMyAdminBaseController{
	public function initialize() {
		parent::initialize();
        TransformersManager::startProd('toView');
    }

}
