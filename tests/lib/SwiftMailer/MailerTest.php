<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Lib\SwiftMailer;

/**
 * Mailerクラス用のテストファイル
 *
 * @package SwiftMailer
 */
class MailerTest extends \PHPUnit_Framework_TestCase
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
        $swift = new \Lib\SwiftMailer\Init(
            $GLOBALS['MAIL_HOST'],
            $GLOBALS['MAIL_PORT'],
            $GLOBALS['MAIL_USER'],
            $GLOBALS['MAIL_PASS']
        );

        $this->object = new Mailer($swift);
    }

    /**
     * @ignore
     */
    protected function tearDown()
    {
    }

    /**
     * 正常系 テンプレートを返すか
     *
     * @covers Lib\SwiftMailer\Mailer::setTemplate()
     * @test testSetTemplateNormal()
     */
    public function testSetTemplateNormal()
    {
        $res = $this->object->setTemplate(
            'defaultTest.twig',
            array('name' => 'twig')
        );

        $this->assertEquals('Hello twig' . PHP_EOL, $res);
    }

    /**
     * 正常系 メッセージを返すか
     *
     * @covers Lib\SwiftMailer\Mailer::setMessage()
     * @test testSetMessageNormal()
     */
    public function testSetMessageNormal()
    {
        $body = $this->object->setTemplate(
            'defaultTest.twig',
            array('name' => '太郎')
        );

        $res = $this->object->setMessage(
            'test subject',
            array('test@example.com' => 'テスト担当'),
            $body
        );

        $this->assertInternalType('object', $res);
    }

    /**
     * 正常系 メッセージを返すか
     *
     * @covers Lib\SwiftMailer\Mailer::send()
     * @test testSetSendNormal()
     */
    public function testSendNormal()
    {
        $body = $this->object->setTemplate(
            'defaultTest.twig',
            array('name' => '太郎')
        );

        $this->object->setMessage(
            'タイトル',
            array('admin@example.com' => 'テスト担当'),
            $body
        );

        $res = $this->object->send(
            'test@example.com'
        );

        $this->assertEquals(1, $res);
    }
}
