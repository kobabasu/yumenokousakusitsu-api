<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Routes;

/**
 * routes/pages.phpのテストファイル
 *
 * @package Routes
 */
class PageTest extends AppMock
{
    /** @var String $path URI */
    protected $path = '/pages/';

    /** @var String $filename 対象ファイル */
    protected $filename = 'routes/pages.php';

    /**
     * 正常系 '/pages/'が1ページ目を返すか
     *
     * @test testPagesGetNormal()
     */
    public function testPagesGetNormal()
    {
        $app = $this->create($this->path . '');
        require $this->filename;
        $resOut = $this->invoke($app);

        $expect = array(
            'pages' => array(
                array(
                    'id' => '1',
                    'name' => 'ニックちゃん',
                    'approved' => '1',
                    'path' => '20160215-012544.png',
                    'posted' => '2016-02-15 01:25:44'
                )
            ),
            'limit' => 15,
            'total' => 1
        );

        $this->assertEquals(
            json_encode($expect),
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/pages/0'が1ページ目を返すか
     *
     * @test testPagesZeroGetNormal()
     */
    public function testPagesZeroGetNormal()
    {
        $app = $this->create($this->path . '0');
        require $this->filename;
        $resOut = $this->invoke($app);

        $expect = array(
            'pages' => array(
                array(
                    'id' => '1',
                    'name' => 'ニックちゃん',
                    'approved' => '1',
                    'path' => '20160215-012544.png',
                    'posted' => '2016-02-15 01:25:44'
                )
            ),
            'limit' => 15,
            'total' => 1
        );

        $this->assertEquals(
            json_encode($expect),
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/pages/1'が1ページ目を返すか
     *
     * @test testPagesOneGetNormal()
     */
    public function testPagesOneGetNormal()
    {
        $app = $this->create($this->path . '1');
        require $this->filename;
        $resOut = $this->invoke($app);

        $expect = array(
            'pages' => array(
                array(
                    'id' => '1',
                    'name' => 'ニックちゃん',
                    'approved' => '1',
                    'path' => '20160215-012544.png',
                    'posted' => '2016-02-15 01:25:44'
                )
            ),
            'limit' => 15,
            'total' => 1
        );

        $this->assertEquals(
            json_encode($expect),
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/pages/2'が空を返すか
     *
     * @test testPagesTwoGetNormal()
     */
    public function testPagesTwoGetNormal()
    {
        $app = $this->create($this->path . '2');
        require $this->filename;
        $resOut = $this->invoke($app);

        $expect = array(
            'pages' => array(
            ),
            'limit' => 15,
            'total' => 1
        );

        $this->assertEquals(
            json_encode($expect),
            (string)$resOut->getBody()
        );
    }

    /**
     * 正常系 '/pages/taro' 数値以外を与えて404を返すか
     *
     * @test testPagesStringGetNormal()
     */
    public function testPagesStringGetNormal()
    {
        $app = $this->create($this->path . 'taro');
        require $this->filename;
        $resOut = $this->invoke($app);

        $this->assertEquals(
            '404',
            $resOut->getStatusCode()
        );
    }
}
