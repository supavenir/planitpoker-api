<?php
namespace controllers;

use controllers\crud\datas\TopCrudControllerDatas;
use Ubiquity\controllers\crud\CRUDDatas;
use controllers\crud\viewers\TopCrudControllerViewer;
use Ubiquity\controllers\crud\viewers\ModelViewer;
use controllers\crud\events\TopCrudControllerEvents;
use Ubiquity\controllers\crud\CRUDEvents;
use controllers\crud\files\TopCrudControllerFiles;
use Ubiquity\controllers\crud\CRUDFiles;
use Ubiquity\attributes\items\router\Route;

#[Route(path: "/crud/{resource}",inherited: true,automated: true)]
class TopCrudController extends \Ubiquity\controllers\crud\MultiResourceCRUDController {

	#[Route(name: "crud.index",priority: -1)]
	public function index() {
		parent::index();
	}


	#[Route(path: "#//crud/home",name: "crud.home",priority: 100)]
	public function home() {
		parent::home();
	}

	protected function getIndexType():array {
		return ['four link cards','card'];
	}
	
	public function _getBaseRoute():string {
		return "/crud/".$this->resource."";
	}
	
	protected function getAdminData(): CRUDDatas {
		return new TopCrudControllerDatas($this);
	}

	protected function getModelViewer(): ModelViewer {
		return new TopCrudControllerViewer($this,$this->style);
	}

	protected function getEvents(): CRUDEvents {
		return new TopCrudControllerEvents($this);
	}

	protected function getFiles(): CRUDFiles {
		return new TopCrudControllerFiles();
	}


}
