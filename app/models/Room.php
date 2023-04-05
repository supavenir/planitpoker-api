<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\OneToMany;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;

#[\AllowDynamicProperties()]
#[Table(name: "room")]
class Room{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "name",dbType: "varchar(100)")]
	#[Validator(type: "length",constraints: ["max"=>"100","notNull"=>true])]
	private $name;

	
	#[Column(name: "description",dbType: "text")]
	#[Validator(type: "notNull",constraints: [])]
	private $description;

	
	#[Column(name: "points",dbType: "tinyint(4)")]
	#[Validator(type: "notNull",constraints: [])]
	private $points;

    #[Column(name: "uuid",dbType: "varchar(100)")]
    private $uuid;

	
	#[Column(name: "connectedUsers",dbType: "text")]
	#[Validator(type: "notNull",constraints: [])]
	private $connectedUsers='[]';

	
	#[OneToMany(mappedBy: "room",className: "models\\Configuration")]
	private $configurations;

	
	#[OneToMany(mappedBy: "room",className: "models\\Permission")]
	private $permissions;

	
	#[OneToMany(mappedBy: "room",className: "models\\Story")]
	private $storys;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Suite",name: "idSuite")]
	private $suite;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Team",name: "idTeam")]
	private $team;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\User",name: "idOwner")]
	private $user;


	 public function __construct(){
		$this->configurations = [];
		$this->permissions = [];
		$this->storys = [];
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


	public function getConnectedUsers(){
		return $this->connectedUsers;
	}


	public function setConnectedUsers($connectedUsers){
		$this->connectedUsers=$connectedUsers;
	}


	public function getConfigurations(){
		return $this->configurations;
	}


	public function setConfigurations($configurations){
		$this->configurations=$configurations;
	}


	 public function addToConfigurations($configuration){
		$this->configurations[]=$configuration;
		$configuration->setRoom($this);
	}


	public function getPermissions(){
		return $this->permissions;
	}


	public function setPermissions($permissions){
		$this->permissions=$permissions;
	}


	 public function addToPermissions($permission){
		$this->permissions[]=$permission;
		$permission->setRoom($this);
	}


	public function getStorys(){
		return $this->storys;
	}


	public function setStorys($storys){
		$this->storys=$storys;
	}


	 public function addToStorys($tory){
		$this->storys[]=$tory;
		$tory->setRoom($this);
	}


	public function getSuite(){
		return $this->suite;
	}


	public function setSuite($suite){
		$this->suite=$suite;
	}


	public function getTeam(){
		return $this->team;
	}


	public function setTeam($team){
		$this->team=$team;
	}


	public function getUser(){
		return $this->user;
	}


	public function setUser($user){
		$this->user=$user;
	}

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid): void{
        $this->uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getUuid() {
        return $this->uuid;
    }

	 public function __toString(){
		return ($this->name??'no value').'';
	}

    public function addConnectedUser(User $user): void{
         $connectedUsers=\json_decode($this->connectedUsers,true);
         $connectedUsers[]=$user->_rest;
        $this->connectedUsers=json_encode($connectedUsers);
    }
    public function removeConnectedUser(User $user): void{
         $connectedUsers=\json_decode($this->connectedUsers,true);
         $connectedUsers=\array_filter($connectedUsers,function($u) use ($user){
			 return $u['id']!=$user->getId();
		 });
        $this->connectedUsers=\json_encode($connectedUsers);
    }

}