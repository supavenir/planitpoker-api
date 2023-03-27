<?php
use Ubiquity\controllers\Router;
use Ubiquity\events\EventsManager;
use Ubiquity\events\DAOEvents;
use Ubiquity\contents\transformation\TransformersManager;

\Ubiquity\cache\CacheManager::startProd($config);
\Ubiquity\orm\DAO::start();
Router::startAll();
Router::addRoute("_default", "controllers\\IndexController");

\Ubiquity\security\data\EncryptionManager::start($config,\Ubiquity\security\data\Encryption::AES256);

EventsManager::addListener([DAOEvents::BEFORE_UPDATE,DAOEvents::BEFORE_INSERT],function($instance){
	if ($instance instanceof \models\User) {
		$instance->setPassword(password_hash($instance->getPassword(), PASSWORD_DEFAULT));
	}
	TransformersManager::transformInstance($instance);
	}
);
