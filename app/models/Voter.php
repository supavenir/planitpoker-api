<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;

#[\AllowDynamicProperties()]
#[Table(name: "voter")]
class Voter{
	
	#[Id()]
	#[Column(name: "idUser",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $idUser;

	
	#[Id()]
	#[Column(name: "idStory",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $idStory;

	
	#[Column(name: "points",nullable: true,dbType: "tinyint(4)")]
	private $points;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Story",name: "idStory")]
	private $story;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\User",name: "idUser")]
	private $user;


	public function getIdUser(){
		return $this->idUser;
	}


	public function setIdUser($idUser){
		$this->idUser=$idUser;
	}


	public function getIdStory(){
		return $this->idStory;
	}


	public function setIdStory($idStory){
		$this->idStory=$idStory;
	}


	public function getPoints(){
		return $this->points;
	}


	public function setPoints($points){
		$this->points=$points;
	}


	public function getStory(){
		return $this->story;
	}


	public function setStory($story){
		$this->story=$story;
	}


	public function getUser(){
		return $this->user;
	}


	public function setUser($user){
		$this->user=$user;
	}


	 public function __toString(){
		return ($this->points??'no value').'';
	}

}