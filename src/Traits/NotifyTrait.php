<?php 

namespace Tcepaas\Traits;

use Tcepaas\CorpServiceHandle;
use Tcepaas\Exception\ArgumentException;

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

    public function handleMessage($nonce, $timestamp, $signature, $msg_encrypt)
    {
        Utils::checkNotEmptyStr($nonce, "nonce");
        Utils::checkNotEmptyStr($timestamp, "timestamp");
        Utils::checkNotEmptyStr($signature, "signature");

        $xmlData = '';
        $wxMsgCrypt = new WXBizMsgCrypt($this->suiteToken, $this->suiteEncodingAesKey, $this->suiteId);
        if (0 == $wxMsgCrypt->DecryptMsg($signature, $timestamp, $nonce, $msg_encrypt, $xmlData)) {
            throw new ArgumentException('invalid msg signature', $signature);
        }

        echo 'success';
        set_time_limit(0);
        ignore_user_abort();
        function_exists('fastcgi_finish_request') && fastcgi_finish_request();

        $this->log('xml data: '.$xmlData, $this->suiteId);
        $xmlData = simplexml_load_string($xmlData, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($xmlData === false) {
            $this->log('load msg data failed', $this->suiteId);
            exit;
        }

        $suiteId = strval($xmlData->SuiteId);
        if ($suiteId != $this->suiteId) {
            $this->log('msg suiteid('.$suiteId.') does not match the current suiteid', $this->suiteId);
            exit;
        }

        (new CorpServiceHandle($this, $xmlData))->handle();
    }
}
