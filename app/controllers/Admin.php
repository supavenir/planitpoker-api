<?php
namespace controllers;
use Ubiquity\controllers\admin\UbiquityMyAdminBaseController;
use Ubiquity\orm\DAO;

class Admin extends UbiquityMyAdminBaseController{
	public function initialize() {
		parent::initialize();
		DAO::$useTransformers = true;
	}

}
