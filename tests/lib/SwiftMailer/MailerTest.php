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

        $this->object->setFrom($GLOBALS['MAIL_FROM']);
        $this->object->setName($GLOBALS['MAIL_NAME']);
        $res = $this->object->setMessage(
            'test subject',
            $body
        );

        $this->assertInternalType('object', $res);
    }

    /**
     * 正常系 $formが正しく設定されるか
     *
     * @covers Lib\SwiftMailer\Mailer::setFrom()
     * @test testSetFromNormal()
     */
    public function testSetFromNormal()
    {
        $class = new \ReflectionClass($this->object);
        $ref = $class->getProperty('from');
        $ref->setAccessible(true);
        $this->object->setFrom('test@example.com');
        $res = $ref->getValue($this->object);

        $this->assertEquals('test@example.com', $res);
    }

    /**
     * 正常系 $nameが正しく設定されるか
     *
     * @covers Lib\SwiftMailer\Mailer::setName()
     * @test testSetNameNormal()
     */
    public function testSetNameNormal()
    {
        $class = new \ReflectionClass($this->object);
        $ref = $class->getProperty('name');
        $ref->setAccessible(true);
        $this->object->setName('システム自動通知');
        $res = $ref->getValue($this->object);

        $this->assertEquals('システム自動通知', $res);
    }

    /**
     * 正常系 添付ファイルを添付してもメッセージも返すか
     *
     * @covers Lib\SwiftMailer\Mailer::setAttachment()
     * @test testSetAttachmentNormal()
     */
    public function testSetAttachmentNormal()
    {
        $class = new \ReflectionClass($this->object);
        $ref = $class->getProperty('attach');
        $ref->setAccessible(true);
        $this->object->setAttachment(
            'tests/imgs/test.jpg',
            'image/jpeg'
        );
        $res = $ref->getValue($this->object);


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

        $this->object->setFrom($GLOBALS['MAIL_FROM']);
        $this->object->setName($GLOBALS['MAIL_NAME']);
        $this->object->setMessage(
            'タイトル',
            $body
        );

        $res = $this->object->send(
            'test@example.com'
        );

        $this->assertEquals(1, $res);
    }

    /**
     * 正常系 添付画像を含むメッセージを返すか
     *
     * @covers Lib\SwiftMailer\Mailer::send()
     * @test testSetSendAttachmentNormal()
     */
    public function testSendAttachmentNormal()
    {
        $body = $this->object->setTemplate(
            'defaultTest.twig',
            array('name' => '太郎')
        );

        $this->object->setAttachment(
            'tests/imgs/test.jpg',
            'image/jpeg',
            'test'
        );

        $this->object->setFrom($GLOBALS['MAIL_FROM']);
        $this->object->setName($GLOBALS['MAIL_NAME']);
        $this->object->setMessage(
            '添付画像テスト',
            $body
        );

        $res = $this->object->send(
            'test@example.com'
        );

        $this->assertEquals(1, $res);
    }
}
