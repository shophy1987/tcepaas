<?php

namespace Tcepaas;

class CorpServiceHandle
{
    private $data;
    private $service;

    public function __construct(CorpService &$corpService, &$msgData)
    {
        if (is_null($msgData)) {
            throw new ArgumentException("message data can not be null", 'msgData');
        }
        if (is_null($corpService)) {
            throw new ArgumentException("instance can not be null", 'corpService');
        }

        $this->data = $msgData;
        $this->service = $service;
    }

    public function handle()
    {
        $infoType = strval($this->data->InfoType);
        $changeType = strval($this->data->ChangeType);

        $method = $infoType . (empty($changeType) ? '' : ('_'.$changeType));
        if (method_exists($this, $method)) {
            $this->method();
        }
    }

    // 推送suite_ticket
    public function suite_ticket() {
        $service->setSuiteTicket(strval($this->data->SuiteTicket));
    }

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
