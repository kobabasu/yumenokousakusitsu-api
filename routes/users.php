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

            // img
            $base64 = base64_decode($body['canvas']);
            $image = imagecreatefromstring($base64);

            $path = '../drawing/upload/';
            $filename = date('Ymd_His');
            $master = $path . $filename . '.png';
            $thumb = $path . $filename . '_s.jpg';

            // save master
            imagepng($image, $master, 9);
            chmod($master, 0755);

            // save thumb
            $canvas = imagecreatetruecolor(200, 200);
            imagecopyresampled(
                $canvas,
                $image,
                0,
                0,
                0,
                0,
                200,
                200,
                677,
                677
            );

            imagejpeg($canvas, $thumb, 50);
            chmod($thumb, 0755);

            imagedestroy($canvas);

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

            $from = $this->get('settings')['mail'];
            $mailer->setMessage(
                '＼投稿がありました／ ぬりえであそぼ！',
                array($from['from'] => $from['name']),
                $template
            );

            $res = $mailer->send('info@example.com');

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
