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
$app->group('/pages', function () {

    /** /users/pages/ */
    $this->get(
        '/{page:[0-9]*}',
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

});
