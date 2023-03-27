<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\OneToMany;

#[Table(name: "params")]
class Params{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "name",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>"50"])]
	private $name;

	
	#[OneToMany(mappedBy: "params",className: "models\\Configuration")]
	private $configurations;


	 public function __construct(){
		$this->configurations = [];
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


	public function getConfigurations(){
		return $this->configurations;
	}


	public function setConfigurations($configurations){
		$this->configurations=$configurations;
	}


	 public function addToConfigurations($configuration){
		$this->configurations[]=$configuration;
		$configuration->setParams($this);
	}


	 public function __toString(){
		return ($this->name??'no value').'';
	}

}