<?php

namespace Tcepaas;

use Tcepaas\Helper\Utils;
use Tcepaas\Exception\ApiException;

abstract class CorpService extends Api 
{
    use Traits\NotifyTrait;

    const CP_SUITE_TICKET = 'EPAAS-ST-';
    const CP_CORP_ACCESS_TOKEN = 'EPAAS-CAT-';
    const CP_SUITE_ACCESS_TOKEN = 'EPAAS-SAT-';

    protected $suiteId;
    protected $suiteSecret;
    protected $suiteTicket;

    protected $corpAccessToken;
    protected $suiteAccessToken;

    public function __construct($suiteid, $suite_secret)
    {
        Utils::checkEmptyStr($suiteid, "suite_id");
        Utils::checkEmptyStr($suite_secret, "suite_secret");

        $this->suiteId = $suiteid;
        $this->suiteSecret = $suite_secret;
    }

    public function getSuiteTicket()
    {
        if ($this->suiteTicket) {
            return $this->suiteTicket;
        }

        $this->suiteTicket = $this->getCache(self::CP_SUITE_TICKET . $this->suite_id);
        if ($this->suiteTicket) {
            return $this->suiteTicket;
        }

        throw new ApiException('无效的suite_ticket');
    }

    public function setSuiteTicket($suite_ticket)
    {
        Utils::checkEmptyStr($suite_ticket, "suite_ticket");

        $this->suiteTicket = $suite_ticket;
        $this->setCache(self::CP_SUITE_TICKET . $this->suite_id, $this->suiteTicket, 600);
    }

    public function getSuiteToken()
    {
        if ($this->suiteAccessToken) {
            return $this->suiteAccessToken;
        }

        $cache_key = self::CP_SUITE_ACCESS_TOKEN . $this->suite_id;
        $this->suiteAccessToken = $this->getCache($cache_key);
        if ($this->suiteAccessToken) {
            return $this->suiteAccessToken;
        }

        $params = [
            'suite_id' => $this->suiteAccessToken,
            'suite_secret' => $this->suiteSecret,
            'suite_ticket' => $this->getSuiteTicket()
        ];
        $response = $this->post('get_suite_token', $params);
        if (!isset($response['suite_access_token'])) {
            throw new ApiException('response missing suite_access_token');
        }

        $this->suiteAccessToken = $response['suite_access_token'];
        $this->setCache($cache_key, $this->suiteAccessToken, $response['expires_in']);
        return $this->suiteAccessToken;
    }

    public function getCorpPermanentCode($auth_code)
    {
        Utils::checkEmptyStr($auth_code, "auth_code");
        return $this->post('get_permanent_code?suite_access_token='.$this->getSuiteToken(), ['auth_code' => $auth_code]);
    }

    public function getCorpInfo($corpid, $permanent_code)
    {
        Utils::checkEmptyStr($corpid, "corpid");
        Utils::checkEmptyStr($permanent_code, "permanent_code");

        return $this->post('get_auth_info?suite_access_token='.$this->getSuiteToken(), [
            'auth_corpid' => $corpid,
            'permanent_code' => $permanent_code
        ]);
    }

    public function getCorpAccessToken($corpid, $permanent_code)
    {
        Utils::checkEmptyStr($corpid, "corpid");
        Utils::checkEmptyStr($permanent_code, "permanent_code");

        $cacheKey = self::CP_CORP_ACCESS_TOKEN . $corpid . '=' . $permanent_code;
        if (isset($this->corpAccessToken[$cacheKey]) && $this->corpAccessToken[$cacheKey]) {
            return $this->corpAccessToken[$cacheKey];
        }

        $this->corpAccessToken[$cacheKey] = $this->getCache($cacheKey);
        if ($this->corpAccessToken[$cacheKey]) {
            return $this->corpAccessToken[$cacheKey];
        }

        $response = $this->post('get_corp_token?suite_access_token='.$this->getSuiteToken(), [
            'auth_corpid' => $corpid,
            'permanent_code' => $permanent_code
        ]);
        
        $this->corpAccessToken[$cacheKey] = $response['access_token'];
        $this->setCache($cacheKey, $this->corpAccessToken[$cacheKey], $response['expires_in']);
        return $this->corpAccessToken[$cacheKey];
    }

    public function getUserAccessToken($code)
    {
        Utils::checkEmptyStr($code, "code");

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://sso.qq.com/open/access_token', ['query' => [
            'appid' => $this->suiteId,
            'secret' => $this->suiteSecret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]]);
        if (isset($response['code']) && $response['code'] != 0) {
            throw new ApiException($response['msg'] ?? 'unknown error', $response['msg']);
        }

        return $response;
    }

    protected function request($method, $uri, $data = [], $headers = [])
    {
        parent::request($method, 'service/'.$uri, $data, $headers);
    }

    abstract public function getCorpApi($corpid, $permanent_code);
}
