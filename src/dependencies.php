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

use \Lib\Image\Original;
use \Lib\Image\Thumbnail;

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
 *
 * [ e.g. ]
 * $db =$this->get('db.get');
 * $sql = 'SELECT * FROM `users` WHERE `id` = ?;';
 * $values = array(1);
 * $res = $db->execute($sql, $values);
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
 *
 * [ e.g. ]
 * $mailer = $this->get('mailer');
 *
 * $tempate = $mailer('mailer');
 *   'users.twig',
 *   array('name' => 'taro')
 * );
 *
 * $mailer->setAttachment(
 *     '../upload/' . $filename . '_s.jpg',
 *     'image/jpeg',
 *     $body['name'] . '.jpg'
 * );
 *
 * $mailer->setMessage('title',$template);
 * $res = $mailer->send('info@test.com');
 */
$container['mailer'] = function ($c) {
    $settings = $c->get('settings')['mail'];

    $transport = new Init(
        $settings['host'],
        $settings['port'],
        $settings['user'],
        $settings['pass']
    );

    $mailer = new Mailer($transport);
    $mailer->setFrom($settings['from']);
    $mailer->setName($settings['name']);

    return $mailer;
};


/**
 * Image
 *
 * [ e.g. ]
 * $fliename = date('Ymd_His');
 * // ファイル名に秒を含めるとずれるためここで確定
 * $original = $this->get('image.original');
 * $original->source($body['canvas']);
 * $original->setFilename($filename);
 * $original->save()
 */
$container['image.original'] = function ($c) {
    $original = new Original();
    $original->setDestination('../upload/');
    $original->setCompress(100);
    $original->setImageType('png');

    return $original;
};

$container['image.thumbnail'] = function ($c) {
    $thumbnail = new Thumbnail();
    $thumbnail->setDestination('../upload/');
    $thumbnail->setPostfix('_s');
    $thumbnail->setCompress(70);
    $thumbnail->setWidth(200);
    $thumbnail->setHeight(200);
    $thumbnail->setImageType('jpg');

    return $thumbnail;
};
