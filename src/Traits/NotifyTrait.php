<?php 

namespace Tcepaas\Traits;

use Tcepaas\Helper\Utils;
use Tcepaas\Helper\WXBizMsgCrypt;
use Tcepaas\Exception\ArgumentException;

trait NotifyTrait
{
    protected $suiteToken;
    protected $suiteEncodingAesKey;

    public function setNotifyToken($token, $encoding_aes_key)
    {
        Utils::checkEmptyStr($token, "token");
        Utils::checkEmptyStr($encoding_aes_key, "encoding_aes_key");

        $this->suiteToken = $token;
        $this->suiteEncodingAesKey = $encoding_aes_key;
    }

    public function handleMessage($nonce, $timestamp, $signature, $msg_encrypt)
    {
        Utils::checkEmptyStr($nonce, "nonce");
        Utils::checkEmptyStr($timestamp, "timestamp");
        Utils::checkEmptyStr($signature, "signature");

        $xmlData = '';
        $wxMsgCrypt = new WXBizMsgCrypt($this->suiteToken, $this->suiteEncodingAesKey, $this->suiteId);
        if (0 == $wxMsgCrypt->DecryptMsg($signature, $timestamp, $nonce, $msg_encrypt, $xmlData)) {
            throw new ArgumentException('invalid msg signature', $signature);
        }

        echo 'success';
        set_time_limit(0);
        ignore_user_abort();
        function_exists('fastcgi_finish_request') && fastcgi_finish_request();

        $this->logInfo('xml data: '.$xmlData, $this->suiteId);
        $xmlData = simplexml_load_string($xmlData, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($xmlData === false) {
            $this->logError('load msg data failed', $this->suiteId);
            exit;
        }

        $suiteId = strval($xmlData->SuiteId);
        if ($suiteId != $this->suiteId) {
            $this->logError('msg suiteid('.$suiteId.') does not match the current suiteid', $this->suiteId);
            exit;
        }

        $infoType = strval($this->data->InfoType);
        $changeType = strval($this->data->ChangeType);

        $method = $infoType . (empty($changeType) ? '' : ('_'.$changeType));
        if (method_exists($this, $method)) {
            $this->method();
        }
    }

    // 推送suite_ticket
    // public function suite_ticket() {
    //     $service->setSuiteTicket(strval($this->data->SuiteTicket));
    // }

    // // 授权成功通知,从企业微信应用市场发起授权时，企业微信后台会推送授权成功通知
    // public function create_auth() { }

    // // 变更授权通知
    // public function change_auth() { }
    
    // // 取消授权通知
    // public function cancel_auth() { }
    
    // // 新增成员事件
    // public function create_user() { }
    
    // // 更新成员事件
    // public function update_user() { }
    
    // // 删除成员事件
    // public function delete_user() { }

    // // 新增部门事件
    // public function create_party() { }
    
    // // 更新部门事件
    // public function update_party() { }

    // // 删除部门事件
    // public function delete_party() { }

    // // 新增学生事件
    // public function create_student() { }

    // // 编辑学生事件
    // public function update_student() { }

    // // 删除学生事件
    // public function delete_student() { }

    // // 新增家长事件
    // public function create_parent() { }

    // // 编辑家长事件
    // public function update_parent() { }

    // // 删除家长事件
    // public function delete_parent() { }

    // // 家校沟通创建部门事件
    // public function create_department() { }

    // // 家校沟通更新部门事件
    // public function update_department() { }

    // // 家校沟通删除部门事件
    // public function delete_department() { }

    // // 手机激活事件
    // public function phone_active() { }
}
