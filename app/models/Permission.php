<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;

#[Table(name: "permission")]
class Permission{
	
	#[Id()]
	#[Column(name: "idRoom",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $idRoom;

	
	#[Id()]
	#[Column(name: "idUser",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $idUser;

	
	#[Column(name: "rValue",nullable: true,dbType: "tinyint(4)")]
	private $rValue;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Room",name: "idRoom")]
	private $room;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\User",name: "idUser")]
	private $user;


	public function getIdRoom(){
		return $this->idRoom;
	}


	public function setIdRoom($idRoom){
		$this->idRoom=$idRoom;
	}


	public function getIdUser(){
		return $this->idUser;
	}


	public function setIdUser($idUser){
		$this->idUser=$idUser;
	}


	public function getRValue(){
		return $this->rValue;
	}


	public function setRValue($rValue){
		$this->rValue=$rValue;
	}


	public function getRoom(){
		return $this->room;
	}


	public function setRoom($room){
		$this->room=$room;
	}


	public function getUser(){
		return $this->user;
	}


	public function setUser($user){
		$this->user=$user;
	}


	 public function __toString(){
		return ($this->rValue??'no value').'';
	}

}