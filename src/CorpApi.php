<?php

namespace Tcepaas;

use Tcepaas\Helper\Utils;
use Tcepaas\Exception\NotImplementedException;

abstract class CorpApi extends Api
{
    use Traits\UserTrait;
    use Traits\DepartTrait;
    use Traits\SchoolUserTrait;
    use Traits\SchoolClassTrait;
    use Traits\SchoolDepartTrait;

    const CP_ACCESS_TOKEN = 'EPAAS-AT-';

    protected $corpId;
    protected $agentSecret;
    protected $accessToken;

    public function __construct($corpId = null, $agent_secret = null)
    {
        $this->corpId = $corpId;
        $this->agentSecret = $agent_secret;
    }

    public function getAccessToken($bflush = false)
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        throw new NotImplementedException();
        /*Utils::checkEmptyStr($this->corpId, "corpid");
        Utils::checkEmptyStr($this->agentSecret, "agent_secret");

        $cacheKey = self::ACCESS_TOKEN . $this->corpId.'-'.$this->agentSecret;
        $this->accessToken = $bflush ? '' : $this->getCache($cacheKey);
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $params = [
            'corpid' => $this->corpId,
            'corpsecret' => $this->agentSecret
        ];
        $response = $this->post('cgi-bin/gettoken', $params);

        $this->accessToken = $response['access_token'];
        $this->setCache($cacheKey, $this->accessToken, $response['expires_in']);
        return $this->accessToken;*/
    }

    public function setAccessToken($access_token)
    {
        Utils::checkEmptyStr($access_token, 'access_token');
        $this->accessToken = $access_token;
    }

    public function getUserInfoByToken($user_token)
    {
        Utils::checkEmptyStr($user_token, 'user_token');
        return $this->get('account/userinfo', ['access_token' => $user_token]);
    }

    public function getUserPhone($userid)
    {
        Utils::checkEmptyStr($userid, 'userid');
        return $this->get('user/phone/get', ['access_token' => $this->getAccessToken(), 'userid' => $userid]);
    }

    public function getUserPhones($userids)
    {
        Utils::checkEmptyStrArray($userids, 'userids');
        return $this->post('user/phone/get?access_token='.$this->getAccessToken(), ['userids' => $userids]);
    }
}
