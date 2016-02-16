<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Lib\SwiftMailer;

/**
 * 実際にメールを送る
 *
 * @package SwiftMailer
 */

class Mailer
{
    /** @var Object #swift Swiftオブジェクト */
    private $swift;

    /** @var Object #message メッセージオブジェクト */
    private $message;

    /**
     * Swiftオブジェクトを代入
     *
     * @param Object $swift
     * @return void
     * @codeCoverageIgnore
     */
    public function __construct(
        \Lib\SwiftMailer\Init $swift
    ) {
        $this->swift = $swift;
    }

    /**
     * Twigのテンプレートを反映して返す
     *
     * @param String $template
     * @param Array $data
     * @return Object
     */
    public function setTemplate(
        $template,
        $data
    ) {
        return $this->swift->setTemplate(
            $template,
            $data
        );
    }

    /**
     * メッセージを返す
     *
     * @param String $subject
     * @param Array $from
     * @param String $body
     * @return Object
     */
    public function setMessage(
        $subject,
        $from,
        $body
    ) {
        $this->message = $this->swift->setMessage(
            $subject,
            $from,
            $body
        );

        return $this->message;
    }

    /**
     * 実際に送る
     *
     * @param Array $to
     * @return void
     */
    public function send($to)
    {
        $mailer = $this->swift->getMailer();
        $this->message->setTo((Array)$to);
        $res = $mailer->send($this->message);

        return $res;
    }
}
