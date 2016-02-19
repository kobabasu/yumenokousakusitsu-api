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
        imagepng($data, $path, 0);
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

            $base64 = base64_decode($body['canvas']);
            $image = imagecreatefromstring($base64);

            $path = '../drawing/upload/';
            $filename = date('Ymd_his');
            $master = $path . $filename . '.png';
            $thumb = $path . $filename . '_s.jpg';

            saveMasterImage($image, $master);
            saveThumbImage($image, $thumb);

            $db = $this->get('db.post');

            /*
            $sql  = 'INSERT INTO `users` ';

            $fields = array_keys($body);
            $values = array_values($body);
            $holder = array_fill(0, count($values), '?');

            $sql .= '(' . implode(', ', $fields) . ')';
            $sql .= ' VALUES ';
            $sql .= '(' . implode(', ', $holder) . ')';

            $db->execute($sql, $values);
             */

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
