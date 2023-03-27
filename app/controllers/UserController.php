<?php
namespace controllers;

use Ajax\php\ubiquity\JsUtils;
use models\User_;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;

/**
 * Controller UserController
 * @property JsUtils $jquery
 */
#[Route('/users',automated: true)]
class UserController extends \controllers\ControllerBase {

    public function initialize(){
        parent::initialize();
        $this->jquery->getHref('a[data-target]',
            parameters: ['hasLoader'=>false,'historize'=>false,'listenerOn'=>'body']);
    }

    public function index(){
	    $users=DAO::getAll(User_::class);
		$this->jquery->renderView("UserController/index.html",compact('users'));
	}

    public function one(int $id){
        $user=DAO::getById(User_::class,$id);
        $this->loadDefaultView(compact('user'));
    }
    #[Get]
    public function add(){
        $this->loadDefaultView(['user'=>new User_()]);
    }

    #[Post]
    public function submitAdd(){
        $user=new User_();
        URequest::setValuesToObject($user);
        $user->setPassword(URequest::password_hash('password'));
        if(!DAO::insert($user)){
            echo "<div class='ui error message'>Erreur !</div>";
        }
        $this->index();
    }

    public function remove(int $id){
        if(DAO::deleteById(User_::class,$id)!=1){
            echo "<div class='ui error message'>Erreur !</div>";
        }
        $this->index();
    }

    public function edit(int $id){
        $user=DAO::getById(User_::class,$id);
        $this->loadView("UserController/add.html",compact('user'));
    }

}
