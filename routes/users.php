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

    function saveMasterImage($data, $path)
    {
        imagepng($data, $path, 9);
        chmod($path, 0755);
    };

    function saveThumbImage($data, $path)
    {
        $canvas = imagecreatetruecolor(200, 200);
        imagecopyresampled(
            $canvas,
            $data,
            0,
            0,
            0,
            0,
            200,
            200,
            677,
            677
        );

        imagejpeg($canvas, $path, 50);
        chmod($path, 0755);

        imagedestroy($canvas);
    }

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

            saveMasterImage($image, $master);
            saveThumbImage($image, $thumb);

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
                    'date' => date('Y年m月d日 H時i分')
                )
            );

            $mailer->setMessage(
                array('admin@yumenokousakusitsu.com' => 'システム自動通知'),
                $mail
                '＼投稿がありました／ ぬりえであそぼ！',
                $template
            );

            $res = $mailer->send(
                'test@example.com'
            );

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
