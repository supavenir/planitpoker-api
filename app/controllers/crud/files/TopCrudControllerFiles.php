<?php
namespace controllers\crud\files;


use Ubiquity\controllers\crud\CRUDFiles;

/**
 * Class TopCrudControllerFiles
 */

class TopCrudControllerFiles extends CRUDFiles {
	public function getViewIndex(): string {
		return "TopCrudController/index.html";
	}

	public function getViewForm(): string {
		return "TopCrudController/form.html";
	}

	public function getViewDisplay(): string {
		return "TopCrudController/display.html";
	}

	public function getViewHome(): string {
		return "TopCrudController/home.html";
	}

	public function getViewItemHome(): string {
		return "TopCrudController/itemHome.html";
	}

	public function getViewNav(): string {
		return "TopCrudController/nav.html";
	}


}
