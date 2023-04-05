<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Transformer;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\OneToMany;
use Ubiquity\attributes\items\ManyToMany;
use Ubiquity\attributes\items\JoinTable;

#[\AllowDynamicProperties()]
#[Table(name: "user")]
class User{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "username",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>"50"])]
	private $username;

	
	#[Column(name: "email",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "email",constraints: [])]
	#[Validator(type: "length",constraints: ["max"=>"255"])]
	#[Transformer(name: "crypt")]
	private $email;

	
	#[Column(name: "password",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>"255"])]
	#[Transformer(name: "hpassword")]
	private $password;

	
	#[Column(name: "completeName",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>"255"])]
	#[Transformer(name: "crypt")]
	private $completeName;

	
	#[OneToMany(mappedBy: "user",className: "models\\Permission")]
	private $permissions;

	
	#[OneToMany(mappedBy: "user",className: "models\\Room")]
	private $rooms;

	
	#[OneToMany(mappedBy: "user",className: "models\\Team")]
	private $teams;

	
	#[OneToMany(mappedBy: "user",className: "models\\Voter")]
	private $voters;

	
	#[ManyToMany(targetEntity: "models\\Team",inversedBy: "users")]
	#[JoinTable(name: "team_users")]
	private $team_userss;


	 public function __construct(){
		$this->permissions = [];
		$this->rooms = [];
		$this->teams = [];
		$this->voters = [];
		$this->team_userss = [];
	}


	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function getUsername(){
		return $this->username;
	}


	public function setUsername($username){
		$this->username=$username;
	}


	public function getEmail(){
		return $this->email;
	}


	public function setEmail($email){
		$this->email=$email;
	}


	public function getPassword(){
		return $this->password;
	}


	public function setPassword($password){
		$this->password=$password;
	}


	public function getCompleteName(){
		return $this->completeName;
	}


	public function setCompleteName($completeName){
		$this->completeName=$completeName;
	}


	public function getPermissions(){
		return $this->permissions;
	}


	public function setPermissions($permissions){
		$this->permissions=$permissions;
	}


	 public function addToPermissions($permission){
		$this->permissions[]=$permission;
		$permission->setUser($this);
	}


	public function getRooms(){
		return $this->rooms;
	}


	public function setRooms($rooms){
		$this->rooms=$rooms;
	}


	 public function addToRooms($room){
		$this->rooms[]=$room;
		$room->setUser($this);
	}


	public function getTeams(){
		return $this->teams;
	}


	public function setTeams($teams){
		$this->teams=$teams;
	}


	 public function addToTeams($team){
		$this->teams[]=$team;
		$team->setUser($this);
	}


	public function getVoters(){
		return $this->voters;
	}


	public function setVoters($voters){
		$this->voters=$voters;
	}


	 public function addToVoters($voter){
		$this->voters[]=$voter;
		$voter->setUser($this);
	}


	public function getTeam_userss(){
		return $this->team_userss;
	}


	public function setTeam_userss($team_userss){
		$this->team_userss=$team_userss;
	}


	 public function addTeam_user($team_user){
		$this->team_userss[]=$team_user;
	}


	 public function __toString(){
		return ($this->username??'no value').'';
	}

}