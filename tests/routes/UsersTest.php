<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Routes;

/**
 * Dbクラス用のテストファイル
 *
 * @package Routes
 */
class UsersTest extends AppMock
{
    /** @var String $path URI */
    protected $path = '/users/';

    /** @var String $filename 対象ファイル */
    protected $filename = 'routes/users.php';

    /**
     * 正常系 '/users/taro'のgetがIDが1のJSONを返すか
     *
     * @test testUsersNameGetNormal()
     */
    public function testUsersNameGetNormal()
    {
        $app = $this->create($this->path . '1');
        require $this->filename;
        $resOut = $this->invoke($app);

        $expect = array(
            array(
                'id' => '1',
                'name' => 'ニックちゃん',
                'approved' => '1',
                'path' => '20160215-012544.png',
                'posted' => '2016-02-15 01:25:44'
            )
        );

        $this->assertEquals(
            json_encode($expect),
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/users/'のgetが正しいJSONを返すか
     *
     * @test testUsersGetNormal()
     */
    public function testUsersGetNormal()
    {
        $app = $this->create($this->path);
        require $this->filename;
        $resOut = $this->invoke($app);

        $expect = array(
            array(
                'id' => '1',
                'name' => 'ニックちゃん',
                'approved' => '1',
                'path' => '20160215-012544.png',
                'posted' => '2016-02-15 01:25:44'
            ),
            array(
                'id' => '2',
                'name' => 'うさ子',
                'approved' => '0',
                'path' => '20160215-012544.png',
                'posted' => '2016-02-15 01:25:44'
            )
        );

        $this->assertEquals(
            json_encode($expect),
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/users/'のpostが正しいJSONを返すか
     *
     * @test testUsersPostNormal()
     */
    public function testUsersPostNormal()
    {
        $req = array(
                'name' => 'モロッコくん',
                'approved' => '1',
                'path' => '20160215-012544.png',
                'posted' => '2016-02-15 01:25:44'
        );
        $this->setRequestBody(json_encode($req));

        $app = $this->create($this->path, 'POST');
        require $this->filename;
        $resOut = $this->invoke($app);

        $this->assertEquals(
            json_encode($req),
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/users/'のputが正しいJSONを返すか
     *
     * @test testUsersPutNormal()
     */
    public function testUsersPutNormal()
    {
        $req = array(
                'name' => 'メキシコちゃん',
                'approved' => '1',
                'path' => '20160215-012544.png',
                'posted' => '2016-02-15 01:25:44'
        );
        $this->setRequestBody(json_encode($req));

        $app = $this->create($this->path . '1', 'PUT');
        require $this->filename;
        $resOut = $this->invoke($app);

        $this->assertEquals(
            1,
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/users/'のdeleteが正しいJSONを返すか
     *
     * @test testUsersDeleteNormal()
     */
    public function testUsersDeleteNormal()
    {
        $app = $this->create($this->path . '1', 'DELETE');
        require $this->filename;
        $resOut = $this->invoke($app);

        $this->assertEquals(
            1,
            (string)$resOut->getBody()
        );
    }
}
