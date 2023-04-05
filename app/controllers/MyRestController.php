<?php

namespace controllers;

use models\Room;
use models\User;
use Ubiquity\attributes\items\rest\Authorization;
use Ubiquity\attributes\items\router\Delete;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Options;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Put;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\attributes\items\rest\Rest;
use Ubiquity\contents\transformation\TransformersManager;
use Ubiquity\controllers\rest\RestServer;
use Ubiquity\controllers\Router;
use Ubiquity\orm\DAO;
use Ubiquity\security\data\EncryptionManager;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;

#[Rest()]
#[Route(path: "/api/")]
class MyRestController extends \Ubiquity\controllers\rest\api\json\JsonRestController {

	/**
	 * Returns all the instances from the model $resource.
	 * Query parameters:
	 * - **include**: A string of associated members to load, comma separated (e.g. users,groups,organization...), or a boolean: true for all members, false for none (default: true).
	 * - **filter**: The filter to apply to the query (where part of an SQL query) (default: 1=1).
	 * - **page[number]**: The page to display (in this case, the page size is set to 1).
	 * - **page[size]**: The page size (count of instance per page) (default: 1).
	 *
	 * @route("{resource}/","methods"=>["get"],"priority"=>0)
	 */
	#[Get('{resource}', priority: 0)]
	public function all($resource) {
		$this->getAll_($resource);
	}

	/**
	 * Returns an instance of $resource, by primary key $id.
	 *
	 * @param string $resource The resource (model) to use
	 * @param string $id The primary key value(s), if the primary key is composite, use a comma to separate the values (e.g. 1,115,AB)
	 *
	 * @route("{resource}/{id}/","methods"=>["get"],"priority"=>1000)
	 */
	#[Get('{resource}/{id}', priority: 1000)]
	public function one($resource, $id) {
		$this->getOne_($resource, $id);
	}

	/**
	 * Deletes an existing instance of $resource.
	 *
	 * @param string $resource The resource (model) to use
	 * @param string $ids The primary key value(s), if the primary key is composite, use a comma to separate the values (e.g. 1,115,AB)
	 *
	 * @route("{resource}/{id}/","methods"=>["delete"],"priority"=>0)
	 * @authorization
	 */
	#[Delete('{resource}/{id}')]
	public function delete($resource, ...$id) {
		$this->delete_($resource, ...$id);
	}

	/**
	 * Route for CORS
	 *
	 * @route("{resource}","methods"=>["options"],"priority"=>3000)
	 */
	#[Options('{resource}', priority: 3000)]
	public function options(...$resource) {
	}

	/**
	 * Inserts a new instance of $resource.
	 * Data attributes are send in request body (in JSON format)
	 *
	 * @param string $resource The resource (model) to use
	 * @route("{resource}/","methods"=>["post"],"priority"=>0)
	 * @authorization
	 */
	#[Post('{resource}', priority: 0)]
    #[Authorization()]
	public function add($resource) {
		TransformersManager::startProd('transform');
		parent::add_($resource);
	}

	/**
	 * Updates an existing instance of $resource.
	 * Data attributes are send in request body (in JSON format)
	 *
	 * @param string $resource The resource (model) to use
	 *
	 * @route("{resource}/{id}","methods"=>["patch"],"priority"=>0)
	 * @authorization
	 */
	#[Put('{resource}/{id}', priority: 0)]
	public function update($resource, ...$id) {
		TransformersManager::startProd('transform');
		parent::update_($resource, ...$id);
	}

	protected function getRestServer(): RestServer {
		$srv = new RestServer($this->config);
		$srv->setAllowedOrigins(['http://127.0.0.1:3000']);
		TransformersManager::startProd('toView');
		return $srv;
	}

	/**
	 * Connection to the API
	 * Returns a token and the user
	 * @route("connect","methods"=>["post"],"priority"=>10)
	 * @throws \Exception
	 * @throws \Ubiquity\exceptions\DAOException
	 */
	#[Post('connect', priority: 10)]
	public function connect() {
		if (URequest::has('username')) {
			DAO::$useTransformers = false;
			$user = DAO::getOne(User::class, 'username= ?', false, [URequest::post('username')]);
			DAO::$useTransformers = true;
			if ($user && URequest::password_verify('password', $user->getPassword())) {
				$tokenInfos = $this->server->connect();
                TransformersManager::transformInstance($user,'toView');
				$tokenInfos['user'] = $user;
				echo $this->_format($tokenInfos);
				return;
			}
			Router::setStatusCode(401);
			throw new \Exception('Unauthorized', 401);
		}
	}
    #[Get('rooms/{room}/users', priority: 4000)]
    public function getConnectedUsersInRoom(string $room){
        $roomInstance = DAO::getOne(Room::class, 'name= ? or uuid= ?', false, [$room,$room]);
        if(isset($roomInstance)){
            $users = json_decode($roomInstance->getConnectedUsers(),true);
            $this->getRestServer()->_setContentType('text/event-stream;charset=utf-8');
            $this->getRestServer()->_header('Cache-Control', 'no-cache, no-transform');
            $this->getRestServer()->_header('X-Accel-Buffering', 'no');
            echo "id: ".$roomInstance->getId()."\n";
            echo "event: message\n";
            echo "data: ".json_encode($users)."\n\n";
        }
    }

    #[Post('rooms/{room}/users/{userId}', priority: 10)]
    public function enterInRoom(string $room, int $userId){
        $roomInstance = DAO::getOne(Room::class, 'name= ? or uuid= ?', false, [$room,$room]);
        if (isset($roomInstance)) {
            $user = DAO::getById(User::class,$userId,false);
            if (isset($user)) {
                $roomInstance->addConnectedUser($user);
                DAO::save($roomInstance);
                echo $this->_format($roomInstance);
            }
        }
    }

    #[Delete('rooms/{room}/users/{userId}', priority: 10)]
    public function leaveRoom(string $room, int $userId){
        $roomInstance = DAO::getOne(Room::class, 'name= ? or uuid= ?', false, [$room,$room]);
        if (isset($roomInstance)) {
            $user = DAO::getById(User::class,$userId,false);
            if (isset($user)) {
                $roomInstance->removeConnectedUser($user);
                DAO::save($roomInstance);
                echo $this->_format($roomInstance);
            }
        }
    }
}

