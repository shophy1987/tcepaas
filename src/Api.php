<?php

namespace Tcepaas;

use Tcepaas\Exception\ApiException;

abstract class Api
{
    const BASE_URL = 'https://oapi.epaas.qq.com/';

    protected function get($uri, $data = [], $headers = [])
    {
        return $this->request('GET', $uri, ['headers' => $headers, 'query' => $data]);
    }

    protected function post($uri, $data = [], $headers = [])
    {
        return $this->request('POST', $uri, ['headers' => $headers, 'json' => $data]);
    }

    protected function put($uri, $data = [], $headers = [])
    {
        return $this->request('PUT', $uri, ['headers' => $headers, 'json' => $data]);
    }

    protected function patch($uri, $data = [], $headers = [])
    {
        return $this->request('PATCH', $uri, ['headers' => $headers, 'json' => $data]);
    }

    protected function delete($uri, $data = [], $headers = [])
    {
        return $this->request('DELETE', $uri, ['headers' => $headers, 'query' => $data]);
    }

    protected function request($method, $uri, $options = [])
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => self::BASE_URL, 
            'timeout' => 15
        ]);
        $response = $client->request($method, $uri, $options);
        if ($response->getStatusCode() == 204) {
            return [];
        } else {
            $response = json_decode($response->getBody()->getContents(), true);
            if (isset($response['errcode']) && $response['errcode'] != 0) {
                throw new ApiException($response['errmsg'] ?? 'unknown error', $response['errcode']);
            }

            return $response;
        }
    }

    abstract protected function getCache($key);

    abstract protected function setCache($key, $value, $expire);
}
