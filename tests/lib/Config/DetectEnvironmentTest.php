<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Lib\Config;

/**
 * Helloクラス用のテストファイル
 *
 * @package Config
 */
class DetectEnvironmentTest extends \PHPUnit_Framework_TestCase
{
    /** @var Object $object 対象クラス */
    protected $object;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp()
    {
        $_SERVER['SERVER_ADDR'] = '192.168.0.1';

        $this->object = new DetectEnvironment('192.168.0.1');
    }

    /**
     * DetectEnvironmentラフ設計用のスタブを作成
     *
     * @return Object
     */
    private function setStub()
    {
        $stub = $this->getMockBuilder('DetectEnvironment')
            ->disableOriginalConstructor()
            ->setConstructorArgs(array('192.168.0.1'))
            ->setMethods(array('getIps'))
            ->getMock();

        $stub->expects($this->any())
            ->method('getIps')
            ->willReturn(true);

        return $stub;
    }

    /**
     * @ignore
     */
    protected function tearDown()
    {
    }

    /**
     * SERVER_ADDRと与えられたIPが一致すればtrueを返すか
     *
     * @covers Lib\Config\DetectEnvironment::__construct()
     * @test testConstruct()
     */
    public function testConstruct()
    {
        $this->assertTrue($this->object->flag);
    }

    /**
     * SERVER_ADDRと与えられたIPが一致すればtrueを返すか
     *
     * @covers Lib\Config\DetectEnvironment::checkIps()
     * @test testCheckIps()
     */
    public function testCheckIps()
    {
        $ip = '192.168.0.1';
        $ips = (Array)$ip;
        $res = $this->object->checkIps($ips);

        $this->assertTrue($res);
    }

    /**
     * SERVER_ADDRが取得出来なかった場合nullを返すか
     *
     * @covers Lib\Config\DetectEnvironment::getServerAddr()
     * @test testGetServerAddr()
     */
    public function testGetServerAddr()
    {
        $res = $this->object->getServerAddr();
        $serv = $_SERVER['SERVER_ADDR'];

        if (filter_var($serv, FILTER_VALIDATE_IP)) {
            $this->assertEquals($res, $serv);
        } else {
            $this->assertNull($res);
        }
    }

    /**
     * host名がlocalhost以外はnullであるか
     *
     * @covers Lib\Config\DetectEnvironment::checkIp()
     * @test testCheckIp() Host
     */
    public function testCheckIpHost()
    {
        $addr = 'localhost';
        $res = $this->object->checkIp($addr);

        $this->assertEquals('localhost', $res);
    }

    /**
     * 正しいIP以外はnullであるか
     *
     * @covers Lib\Config\DetectEnvironment::checkIp()
     * @test testCheckIp() Ip
     */
    public function testCheckIp()
    {
        $addr = '1922.168.0.1111';
        $res = $this->object->checkIp($addr);

        $this->assertNull($res);
    }

    /**
     * productionはtrueか
     *
     * @covers Lib\Config\DetectEnvironment::evalProduction()
     * @test testEvalProduction()
     */
    public function testEvalProduction()
    {
        $res = $this->object->evalProduction();
        $this->assertTrue($res);
    }

    /**
     * developmentはfalseか
     *
     * @covers Lib\Config\DetectEnvironment::evalDevelopment()
     * @test testEvalDevelopment()
     */
    public function testEvalDevelopment()
    {
        $res = $this->object->evalDevelopment();
        $this->assertFalse($res);
    }

    /**
     * getNameはproductionを返す
     *
     * @covers Lib\Config\DetectEnvironment::getName()
     * @test testGetName()
     */
    public function testGetName()
    {
        $res = $this->object->getName();
        $this->assertEquals($res, 'production');
    }
}
