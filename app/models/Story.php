<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;
use Ubiquity\attributes\items\OneToMany;

#[Table(name: "story")]
class Story{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "name",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>"255"])]
	private $name;

	
	#[Column(name: "description",nullable: true,dbType: "text")]
	private $description;

	
	#[Column(name: "points",nullable: true,dbType: "tinyint(4)")]
	private $points;

	
	#[Column(name: "completed",nullable: true,dbType: "tinyint(1)")]
	#[Validator(type: "isBool",constraints: [])]
	private $completed;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Room",name: "idRoom")]
	private $room;

	
	#[OneToMany(mappedBy: "story",className: "models\\Voter")]
	private $voters;


	 public function __construct(){
		$this->voters = [];
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


	public function getDescription(){
		return $this->description;
	}


	public function setDescription($description){
		$this->description=$description;
	}


	public function getPoints(){
		return $this->points;
	}


	public function setPoints($points){
		$this->points=$points;
	}


	public function getCompleted(){
		return $this->completed;
	}


	public function setCompleted($completed){
		$this->completed=$completed;
	}


	public function getRoom(){
		return $this->room;
	}


	public function setRoom($room){
		$this->room=$room;
	}


	public function getVoters(){
		return $this->voters;
	}


	public function setVoters($voters){
		$this->voters=$voters;
	}


	 public function addToVoters($voter){
		$this->voters[]=$voter;
		$voter->setStory($this);
	}


	 public function __toString(){
		return ($this->name??'no value').'';
	}

}