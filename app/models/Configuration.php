<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;

#[Table(name: "configuration")]
class Configuration{
	
	#[Id()]
	#[Column(name: "id_idRoom",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id_idRoom;

	
	#[Id()]
	#[Column(name: "idParam",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $idParam;

	
	#[Column(name: "pValue",nullable: true,dbType: "text")]
	private $pValue;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Params",name: "idParam")]
	private $params;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Room",name: "id_idRoom")]
	private $room;


	public function getId_idRoom(){
		return $this->id_idRoom;
	}


	public function setId_idRoom($id_idRoom){
		$this->id_idRoom=$id_idRoom;
	}


	public function getIdParam(){
		return $this->idParam;
	}


	public function setIdParam($idParam){
		$this->idParam=$idParam;
	}


	public function getPValue(){
		return $this->pValue;
	}


	public function setPValue($pValue){
		$this->pValue=$pValue;
	}


	public function getParams(){
		return $this->params;
	}


	public function setParams($params){
		$this->params=$params;
	}


	public function getRoom(){
		return $this->room;
	}


	public function setRoom($room){
		$this->room=$room;
	}


	 public function __toString(){
		return ($this->pValue??'no value').'';
	}

}