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

    /** /users/pages/ */
    $this->get(
        '/pages/{page:[0-9]*}',
        function (
            $request,
            $response,
            $args
        ) {
            $db = $this->get('db.get');

            $page = 1;
            if ($args['page'] > 0) {
                $page = $args['page'];
            }

            $limit = 15;
            $offset = $limit * ($page - 1);

            $sql = 'SELECT * FROM `users` ';
            $sql .= 'WHERE `approved` = 1 ';
            $sql .= 'ORDER BY `posted` DESC LIMIT ';
            $sql .= (int)$offset . ', ' . (int)$limit . ';';
            $pages = $db->execute($sql);

            $sql = 'SELECT COUNT(*) AS `total` FROM `users` ';
            $sql .= 'WHERE `approved` = 1;';
            $total = $db->execute($sql);

            $body = array(
                'pages' => $pages,
                'limit' => (int)$limit,
                'total' => (int)array_shift($total)->total
            );

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

            $db = $this->get('db.post');

            $sql  = 'INSERT INTO `users` ';

            $fields = array_keys($body);
            $values = array_values($body);
            $holder = array_fill(0, count($values), '?');

            $sql .= '(' . implode(', ', $fields) . ')';
            $sql .= ' VALUES ';
            $sql .= '(' . implode(', ', $holder) . ')';

            $db->execute($sql, $values);

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
