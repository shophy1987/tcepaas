<?php

namespace Tcepaas;

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

    abstract protected function request($method, $uri, $data = [], $headers = []);

    abstract protected function getCache($key);

    abstract protected function setCache($key, $value, $expire);
}
