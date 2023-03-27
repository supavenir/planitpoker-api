<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\OneToMany;

#[Table(name: "suite")]
class Suite{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "name",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>"50"])]
	private $name;

	
	#[Column(name: "public",nullable: true,dbType: "tinyint(1)")]
	#[Validator(type: "isBool",constraints: [])]
	private $public;

	
	#[Column(name: "suitevalues",nullable: true,dbType: "text")]
	private $suitevalues;

	
	#[OneToMany(mappedBy: "suite",className: "models\\Room")]
	private $rooms;


	 public function __construct(){
		$this->rooms = [];
	}


	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function getName(){
		return $this->name;
	}


	public function setName($name){
		$this->name=$name;
	}


	public function getPublic(){
		return $this->public;
	}


	public function setPublic($public){
		$this->public=$public;
	}


	public function getSuitevalues(){
		return $this->suitevalues;
	}


	public function setSuitevalues($suitevalues){
		$this->suitevalues=$suitevalues;
	}


	public function getRooms(){
		return $this->rooms;
	}


	public function setRooms($rooms){
		$this->rooms=$rooms;
	}


	 public function addToRooms($room){
		$this->rooms[]=$room;
		$room->setSuite($this);
	}


	 public function __toString(){
		return ($this->name??'no value').'';
	}

}