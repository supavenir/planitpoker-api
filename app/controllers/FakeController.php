<?php

namespace controllers;

use Faker\Factory;
use Faker\Generator;
use models\Params;
use models\Room;
use models\Story;
use models\Suite;
use models\Team;
use models\User;
use models\Voter;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\orm\DAO;

/**
 * Controller FakeController
 * @property \Ajax\php\ubiquity\JsUtils $jquery
 */
class FakeController extends \controllers\ControllerBase {

	private Generator $faker;

	public function initialize() {
		parent::initialize();
		$this->faker = Factory::create('fr_FR');
		$this->faker->seed(1234);
		DAO::$useTransformers = true;
	}

	protected function createUsers(int $count = 30) {
		$users = [];
		for ($i = 0; $i < $count; $i++) {
			$user = new \models\User();
			$user->setUsername($this->faker->unique()->userName());
			$user->setEmail($this->faker->unique()->email());
			$user->setPassword('0000');
			$user->setCompleteName($this->faker->name);
			$users[] = $user;
			DAO::insert($user);
		}
		return $users;
	}

	protected function createSuites() {
		$fibonacci = new Suite();
		$fibonacci->setPublic(true);
		$fibonacci->setName('Fibonacci');
		$fibonacci->setSuitevalues('[0,1,3,5,8,13,21,34,55,89]');
		DAO::insert($fibonacci);
		$teaShirtSizes = new Suite();
		$teaShirtSizes->setPublic(true);
		$teaShirtSizes->setName('T-shirt');
		$teaShirtSizes->setSuitevalues('["XS","S","M","L","XL","XXL"]');
		DAO::insert($teaShirtSizes);
		$scrumSuite = new Suite();
		$scrumSuite->setPublic(true);
		$scrumSuite->setName('Scrum');
		$scrumSuite->setSuitevalues('[0,0.5,1,2,3,5,8,13,20,40,100]');
		DAO::insert($scrumSuite);
		$playingCards = new Suite();
		$playingCards->setPublic(true);
		$playingCards->setName('Playing cards');
		$playingCards->setSuitevalues('["2","3","4","5","6","7","8","9","10","J","Q","K","A"]');
		DAO::insert($playingCards);
	}

	protected function createConfigurations() {
		$paramsNames = ['hasObservers' => 'Request confirmation when skipping stories?',
			'skipConfirmation' => 'Request confirmation when skipping stories?',
			'autoReveal' => 'Reveal cards automatically?',
			'changeVote' => 'Allow changing vote?',
			'countDownTimer' => 'Enable countdown timer?'
		];
		foreach ($paramsNames as $name => $description) {
			$params = new Params();
			$params->setName($name);
			$params->setDescription($description);
			DAO::toInsert($params);
		}
		DAO::flushInserts();
	}

	protected function getRandomInstance(string $model, $include = true) {
		return DAO::getOne($model, '1=1 order by rand()', $include);
	}

	protected function getRandomInstances(string $model, int $count) {
		return DAO::getAll($model, '1=1 order by rand() limit ' . $count);
	}

	protected function createStories(Room $room, int $count): array {
		$stories = [];
		for ($i = 0; $i < $count; $i++) {
			$story = new Story();
			$story->setName($this->faker->unique()->sentence(5));
			$story->setRoom($room);
			$story->setDescription($this->faker->paragraph());
			$stories[] = $story;
			DAO::insert($story);
		}
		return $stories;
	}

	protected function getRandomValueInSuite(Suite $suite) {
		$values = \explode(',', $suite->getSuitevalues());
		return $values[\array_rand($values)];
	}

	protected function createVotes(array $stories, array $users, Suite $suite) {
		foreach ($stories as $story) {
			foreach ($users as $user) {
				$v = new Voter();
				$v->setUser($user);
				$v->setStory($story);
				$v->setPoints($this->getRandomValueInSuite($suite));
				DAO::insert($v);
			}
		}
	}

	protected function createRoomAndStories(int $count = 10, int $nbStories = 20, bool $createVotes = false) {
		for ($i = 0; $i < $count; $i++) {
			$room = new Room();
			$room->setName($this->faker->unique()->sentence(1));
			$room->setUser($this->getRandomInstance(User::class));
			$suite = $this->getRandomInstance(Suite::class);
			$room->setSuite($suite);
			$team = $this->getRandomInstance(Team::class);
			$room->setTeam($team);
			DAO::insert($room, true);
			$stories = $this->createStories($room, $nbStories);
			if ($createVotes) {
				$this->createVotes($stories, $team->getUsers(), $suite);
			}
		}
	}

	protected function createTeams(int $count = 10, int $nbUsers = 10) {
		for ($i = 0; $i < $count; $i++) {
			$team = new Team();
			$team->setName($this->faker->unique()->sentence(1));
			$team->setUser($this->getRandomInstance(User::class));
			$team->setUsers($this->getRandomInstances(User::class, $nbUsers));
			DAO::insert($team, true);
		}
	}

	#[Route('_fake')]
	public function index() {
		$data = ['nbUsers' => 50, 'nbRooms' => 5, 'nbTeams' => 5, 'nbStories' => 20, 'hasSuites' => DAO::count(Suite::class) == 0,
			'hasConfig' => DAO::count(Params::class) == 0, 'nbUsersTeam' => 10, 'nbStoriesRoom' => 20];
		$this->loadView('/FakeController/index.html', $data);
	}

	#[Post('_fake/create', name: 'fake.create')]
	public function createFakeAction() {
		$nbUsers = $_POST['nbUsers'] ?? 50;
		$nbRooms = $_POST['nbRooms'] ?? 5;
		$nbTeams = $_POST['nbTeams'] ?? 5;
		$nbUsersTeam = $_POST['nbUsersTeam'] ?? 10;
		$nbStoriesRoom = $_POST['nbStoriesRoom'] ?? 20;
		$hasSuites = isset($_POST['createSuites']);
		$hasConfig = isset($_POST['createConfig']);
		$createVotes = isset($_POST['createVotes']);
		if ($hasSuites) {
			$this->createSuites();
		}
		if ($hasConfig) {
			$this->createConfigurations();
		}
		$this->createUsers($nbUsers);
		$this->createTeams($nbTeams, $nbUsersTeam);
		$this->createRoomAndStories($nbRooms, $nbStoriesRoom, $createVotes);

		$list = $this->jquery->semantic()->htmlList('list');
		$list->setBulleted();
		$list->addItem('Users created: ' . DAO::count(User::class));
		$list->addItem('Rooms created: ' . DAO::count(Room::class));
		$list->addItem('Teams created: ' . DAO::count(Team::class));
		$list->addItem('Stories created: ' . DAO::count(Story::class));
		$list->addItem('Votes created: ' . DAO::count(Voter::class));
		$list->addItem('Suites created: ' . DAO::count(Suite::class));
		$list->addItem('Configurations created: ' . DAO::count(Params::class));
		$this->jquery->renderView('FakeController/createFakeAction.html');
	}

}
