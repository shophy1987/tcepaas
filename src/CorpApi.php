<?php

namespace Tcepaas;

use Tcepaas\Helper\Utils;

abstract class CorpApi extends Api
{
    use Trait\UserTrait;
    use Trait\DepartTrait;
    use Trait\SchoolUserTrait;
    use Trait\SchoolDepartTrait;

    const CP_ACCESS_TOKEN = 'EPAAS-AT-';

    protected $corpId;
    protected $agentSecret;
    protected $accessToken;

    public function __construct($corpId = null, $agent_secret = null)
    {
        $this->corpId = $corpId;
        $this->agentSecret = $agent_secret;
    }

    public function GetAccessToken($bflush = false)
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        throw new NotImplementedException();
        /*Utils::checkNotEmptyStr($this->corpId, "corpid");
        Utils::checkNotEmptyStr($this->agentSecret, "agent_secret");

        $cacheKey = self::ACCESS_TOKEN . $this->corpId.'-'.$this->agentSecret;
        $this->accessToken = $bflush ? '' : $this->getCache($cacheKey);
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $params = [
            'corpid' => $this->corpId,
            'corpsecret' => $this->agentSecret
        ];
        $response = $this->post('/cgi-bin/gettoken', $params);

        $this->accessToken = $response['access_token'];
        $this->setCache($cacheKey, $this->accessToken, $response['expires_in']);
        return $this->accessToken;*/
    }

    public function setAccessToken($access_token)
    {
        Utils::checkNotEmptyStr($access_token, 'access_token');
        $this->accessToken = $access_token;
    }

    protected function request($method, $uri, $data = [], $headers = [])
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => self::BASE_URL, 
            'timeout' => 15
        ]);
        $response = $client->request($method, uri, $data, $headers);
        if ($response->getStatusCode() == 204) {
            return [];
        } else {
            return json_decode($this->response->getBody()->getContents(), true);
        }
    }
}
