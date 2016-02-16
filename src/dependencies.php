<?php
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Monolog\Handler\StreamHandler;

use \Lib\Db\Connect;
use \Lib\Db\Get;
use \Lib\Db\Post;
use \Lib\Db\Put;
use \Lib\Db\Delete;

use \Lib\SwiftMailer\Init;
use \Lib\SwiftMailer\Mailer;

/**
 * DIC configuration
 */

$container = $app->getContainer();


/**
 * monolog
 */
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];

    $logger = new Logger($settings['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(
        new StreamHandler($settings['path'], Logger::DEBUG)
    );

    return $logger;
};


/**
 * Database
 */
$container['db.pdo'] = function ($c) {
    $settings = $c->get('settings')['db'];

    $pdo = new Connect(
        $settings['host'],
        $settings['name'],
        $settings['user'],
        $settings['pass']
    );

    $pdo->setPort($settings['port']);
    $pdo->setCharset($settings['charset']);
    $pdo->setDebug($c->get('settings')['debug_mode']);

    return $pdo->getConnection();
};

// GET
$container['db.get'] = function ($c) {
    $pdo = $c->get('db.pdo');
    $obj = new Get($pdo);
    $obj->setDebug($c->get('settings')['debug_mode']);

    return $obj;
};

// POST
$container['db.post'] = function ($c) {
    $pdo = $c->get('db.pdo');
    $obj = new Post($pdo);
    $obj->setDebug($c->get('settings')['debug_mode']);

    return $obj;
};

// PUT
$container['db.put'] = function ($c) {
    $pdo = $c->get('db.pdo');
    $obj = new Put($pdo);
    $obj->setDebug($c->get('settings')['debug_mode']);

    return $obj;
};

// DELETE
$container['db.delete'] = function ($c) {
    $pdo = $c->get('db.pdo');
    $obj = new Delete($pdo);
    $obj->setDebug($c->get('settings')['debug_mode']);

    return $obj;
};


/**
 * Swift Mailer
 */
$container['mailer'] = function ($c) {
    $settings = $c->get('settings')['mail'];

    $transport = new Init(
        $settings['host'],
        $settings['port'],
        $settings['user'],
        $settings['pass']
    );

    return new Mailer(
        $transport
    );
};
