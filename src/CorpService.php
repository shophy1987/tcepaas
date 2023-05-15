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

    public function __construct($suite_id, $suite_secret)
    {
        Utils::checkNotEmptyStr($suite_id, "suite_id");
        Utils::checkNotEmptyStr($suite_secret, "suite_secret");

        $this->suiteId = $suite_id;
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
        Utils::checkNotEmptyStr($suite_ticket, "suite_ticket");

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
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            throw new ApiException($response['errmsg'] ?? 'unknown error', $response['errcode']);
        }
        if (!isset($response['suite_access_token'])) {
            throw new ApiException('response missing suite_access_token');
        }

        $this->suiteAccessToken = $response['suite_access_token'];
        $this->setCache($cache_key, $this->suiteAccessToken, $response['expires_in']);
        return $this->suiteAccessToken;
    }

    public function getCorpPermanentCode($auth_code)
    {
        $params = [
            'auth_code' => $auth_code
        ];
        $response = $this->post('get_permanent_code?suite_access_token='.$this->getSuiteToken(), $params);
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            throw new ApiException($response['errmsg'] ?? 'unknown error', $response['errcode']);
        }

        return $response;
    }

    public function getCorpInfo($corp_id, $permanent_code)
    {
        $params = [
            'auth_corpid' => $corp_id,
            'permanent_code' => $permanent_code
        ];
        $response = $this->post('get_auth_info?suite_access_token='.$this->getSuiteToken(), $params);
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            throw new ApiException($response['errmsg'] ?? 'unknown error', $response['errcode']);
        }

        return $response;
    }

    public function getCorpAccessToken($corp_id, $permanent_code)
    {
        $cacheKey = self::CP_CORP_ACCESS_TOKEN . $corp_id . '=' . $permanent_code;
        if (isset($this->corpAccessToken[$cacheKey]) && $this->corpAccessToken[$cacheKey]) {
            return $this->corpAccessToken[$cacheKey];
        }

        $this->corpAccessToken[$cacheKey] = $this->getCache($cacheKey);
        if ($this->corpAccessToken[$cacheKey]) {
            return $this->corpAccessToken[$cacheKey];
        }

        $params = [
            'auth_corpid' => $corp_id,
            'permanent_code' => $permanent_code
        ];
        $response = $this->post('get_corp_token?suite_access_token='.$this->getSuiteToken(), $params);
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            throw new ApiException($response['errmsg'] ?? 'unknown error', $response['errcode']);
        }

        $this->corpAccessToken[$cacheKey] = $response['access_token'];
        $this->setCache($cacheKey, $this->corpAccessToken[$cacheKey], $response['expires_in']);
        return $this->corpAccessToken[$cacheKey];
    }

    public function getUserAccessToken($code)
    {
    }

    public function getUserInfoByToken($user_token)
    {
    }

    abstract public function getCorpApi($corp_id, $permanent_code);

    protected function request($method, $uri, $data = [], $headers = [])
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => self::BASE_URL, 
            'timeout' => 15
        ]);
        $response = $client->request($method, 'service/' . uri, $data, $headers);
        if ($response->getStatusCode() == 204) {
            return [];
        } else {
            return json_decode($this->response->getBody()->getContents(), true);
        }
    }
}
