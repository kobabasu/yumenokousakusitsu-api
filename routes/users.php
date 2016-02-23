<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Routes;

/**
 * users
 */
$app->group('/users', function () {

    /**
     * GET
     */
    $this->get(
        '/{id:[0-9]*}',
        function (
            $request,
            $response,
            $args
        ) {
            $db = $this->get('db.get');
            $sql = 'select * from `users`';

            if ($args['id']) {
                $sql .= ' WHERE `id` = ?;';
                $body = $db->execute($sql, $args['id']);
            } else {
                $body = $db->execute($sql);
            }

            return $response->withJson(
                $body,
                200,
                $this->get('settings')['withJsonEnc']
            );
        }
    );

    /**
     * POST
     */
    $this->post(
        '/',
        function (
            $request,
            $response,
            $args
        ) {
            $body = $request->getParsedBody();
                
            $filename = date('Ymd_His');

            $original = $this->get('image.original');
            $original->source($body['canvas']);
            $original->setFilename($filename);
            $original->save();

            $thumbnail = $this->get('image.thumbnail');
            $thumbnail->source($body['canvas']);
            $thumbnail->setFilename($filename);
            $thumbnail->save();

            // DB
            $db = $this->get('db.post');

            $sql  = 'INSERT INTO `users` ';
            $sql .= '(`name`, `approved`, `path`, `posted`) ';
            $sql .= 'VALUES ';
            $sql .= '(?, ?, ?, ?);';

            $values = array(
                $body['name'],
                $body['approved'],
                $filename,
                date('Y-m-d H:i:s')
            );

            $db->execute($sql, $values);

            // mail
            $mailer = $this->get('mailer');

            $template = $mailer->setTemplate(
                'users.twig',
                array(
                    'name' => $body['name'],
                    'date' => date('n月d日H時i分'),
                    'approved' => $body['approved']
                )
            );

            $mailer->setAttachment(
                '../upload/' . $filename . '_s.jpg',
                'image/jpeg',
                $body['name'] . '.jpg'
            );

            $mailer->setMessage(
                '＼投稿がありました／ ぬりえであそぼ！',
                $template
            );

            $addr = $this->get('settings')['mail']['addr'];
            $res = $mailer->send($addr);

            // response
            return $response->withJson(
                $body,
                200,
                $this->get('settings')['withJsonEnc']
            );
        }
    );

    /**
     * PUT
     */
    $this->put(
        '/{id:[0-9]+}',
        function (
            $request,
            $response,
            $args
        ) {
            $body = $request->getParsedBody();

            $db = $this->get('db.put');

            $fields = array_keys($body);
            $values = array_values($body);

            $sql = 'UPDATE `users` SET ';
            $sql .= implode(' = ?, ', $fields) . ' = ?';
            $sql .= ' WHERE `id` = ' . (int)$args['id'];

            $res = $db->execute($sql, $values);

            return $response->withJson(
                $res,
                200,
                $this->get('settings')['withJsonEnc']
            );
        }
    );

    /**
     * DELETE
     */
    $this->delete(
        '/{id:[0-9]+}',
        function (
            $request,
            $response,
            $args
        ) {
            $db = $this->get('db.delete');

            $sql = 'DELETE FROM `users` ';
            $sql .= 'WHERE `id` = ' . (int)$args['id'];

            $res = $db->execute($sql);

            return $response->withJson(
                $res,
                200,
                $this->get('settings')['withJsonEnc']
            );
        }
    );

});
