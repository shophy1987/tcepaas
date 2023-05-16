<?php 

namespace Tcepaas\Traits;

trait DepartTrait
{
    public function getDeparts($params = [])
    {
        $params['access_token'] = $this->getAccessToken();
        return $this->get('department/list', $params);
    }

    public function getDepart($params = [])
    {
        $params['access_token'] = $this->getAccessToken();
        return $this->get('department/get', $params);
    }

    public function searchDepart($params = [])
    {
        return $this->post('department/search?access_token='.$this->getAccessToken(), $params);
    }
}
