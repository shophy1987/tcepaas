<?php

namespace Tcepaas\Trait;

use Tcepaas\Helper\Utils;

trait NotifyTrait
{
    protected $suiteToken;
    protected $suiteEncodingAesKey;

    public function setNotifyToken($token, $encoding_aes_key)
    {
        Utils::checkNotEmptyStr($token, "token");
        Utils::checkNotEmptyStr($encoding_aes_key, "encoding_aes_key");

        $this->suiteToken = $token;
        $this->suiteEncodingAesKey = $encoding_aes_key;
    }

    public function verifyMessage($nonce, $timestamp, $signature, $msg_encrypt)
    {
        if ($suite_id != $this->suite_id) {
            throw new exceptions\ArgumentException('无效的suite_id', $suite_id);
        }
        if ($sign != sha1($nonce . $this->callback_secret . $timestamp)) {
            throw new exceptions\ArgumentException('无效的签名', $sign);
        }
    }

    public function handleMessage($action, $attributes)
    {
        set_time_limit(0);
        ignore_user_abort();
        function_exists('fastcgi_finish_request') && fastcgi_finish_request();
        
        if ($action === 'service/suite_ticket') {
            if (isset($attributes['suite_ticket'])) {
                $this->setSuiteTicket($attributes['suite_ticket']);
            }
        } else {
            $method = str_replace('/', '_', $action);
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
    }
}
