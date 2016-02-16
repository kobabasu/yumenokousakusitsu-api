<?php
/**
 * Web App REST API
 *
 * @link https://github.com/kobabasu/rest-php.git
 */

namespace Lib\Config;

/**
 * アプリケーション設定
 *
 * @package Config
 */
class DetectEnvironment
{
    const DEVELOPMENT = 'development';
    const PRODUCTION  = 'production';

    /**
     * productionであればtrue
     */
    public $flag = false;

    /**
     * 与えられた引数をcheckIps()にかけ結果を返す
     *
     * Arrayにキャストする
     * $flagに代入する
     *
     * @param Mixed $ips
     */
    public function __construct($ips)
    {
        $this->flag = $this->checkIps((Array)$ips);
    }

    /**
     * SERVER_ADDRと比較し結果を返す
     *
     * @param Array $ips
     * @return Boolean
     */
    public function checkIps(Array $ips)
    {
        $flag = false;
        $serverAddr = $this->getServerAddr();

        foreach ($ips as $val) {
            if ($serverAddr == $this->checkIp($val)) {
                $flag = true;
            }
        }

        return $flag;
    }

    /**
     * 環境変数のSERVER_ADDRを取得
     *
     * @return String
     */
    public function getServerAddr()
    {
        $res = null;

        if (isset($_SERVER['SERVER_ADDR'])) {
            $res = $this->checkIp($_SERVER['SERVER_ADDR']);
        }

        return $res;
    }

    /**
     * 正しい書式かどうか確認
     *
     * @param String $addr
     * @return String
     */
    public function checkIp($addr)
    {
        $res = null;

        if (filter_var($addr, FILTER_VALIDATE_IP)) {
            $res = $addr;
        }

        if ($addr == 'localhost') {
            $res = $addr;
        }

        return $res;
    }

    /**
     * production環境であればtrueを返す
     *
     * @return boolean
     */
    public function evalProduction()
    {
        return $this->flag;
    }

    /**
     * development環境であればtrueを返す
     *
     * @return boolean
     */
    public function evalDevelopment()
    {
        return !$this->flag;
    }

    /**
     * booleanの代わりにenvironment名を返す
     *
     * @return String
     */
    public function getName()
    {
        $res = self::DEVELOPMENT;
        if ($this->flag) {
            $res = self::PRODUCTION;
        }

        return $res;
    }
}
