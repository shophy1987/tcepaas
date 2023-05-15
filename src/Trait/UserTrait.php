<?php 

namespace Tcepaas\Traits;

use Tcepaas\Helper\Utils;
use Tcepaas\Exception\ArgumentException;

trait UserTrait
{
	public function getUser($params)
    {
        Utils::checkArrayEmptyStr($params, 'userid');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('user/get', $params);
    }

    public function getUserDetail($params)
    {
        Utils::checkArrayEmptyStr($params, 'userid');

        return $this->post('user/get_info?access_token='.$this->getAccessToken(), $params);
    }

    public function batchUser($params)
    {
        Utils::checkEmptyStrArray($params, 'userid_list');
        return $this->post('user/batch_get?access_token='.$this->getAccessToken(), $params);
    }

    public function batchUserDetail($params)
    {
        Utils::checkEmptyStrArray($params, 'useridlist');
        return $this->post('user/batch_get_info?access_token='.$this->getAccessToken(), $params);
    }

    public function getDepartUsers($params)
    {
        Utils::checkPositiveIntArray($params, 'department_id');
        Utils::checkPositiveIntArray($params, 'page_index');
        Utils::checkPositiveIntArray($params, 'page_size');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('user/list_simple', $params);
    }

    public function getDepartUsersDetail($params)
    {
        Utils::checkPositiveIntArray($params, 'department_id');
        Utils::checkPositiveIntArray($params, 'page_index');
        Utils::checkPositiveIntArray($params, 'page_size');
        Utils::checkPositiveIntArray($params, 'department_type');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('user/list', $params);
    }

    public function searchUser($params)
    {
        Utils::checkPositiveIntArray($params, 'page_index');
        Utils::checkPositiveIntArray($params, 'page_size');
        return $this->post('user/search?access_token='.$this->getAccessToken(), $params);
    }

    public function searchUserInSubOrgs($params)
    {
        Utils::checkPositiveIntArray($params, 'page_index');
        Utils::checkPositiveIntArray($params, 'page_size');
        return $this->post('user/search_in_sub_orgs?access_token='.$this->getAccessToken(), $params);
    }

    public function getUserDeparts($params)
    {
        Utils::checkArrayEmptyStr($params, 'userid');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('common/teacher/get_group', $params);
    }

    public function searchProfile($params)
    {
        if (!isset($params['search_keys'])) {
            throw new ArgumentException("missing reuqired key", 'search_keys');
        }

        Utils::checkArrayEmptyStr($params['search_keys'], 'key');
        Utils::checkArrayEmptyStr($params['search_keys'], 'value');
        Utils::checkPositiveIntArray($params, 'page_index');
        Utils::checkPositiveIntArray($params, 'page_size');
        return $this->post('user/search_profile?access_token='.$this->getAccessToken(), $params);
    }

    public function statUser($params) 
    {
        Utils::checkPositiveIntArray($params, 'role_id');
        return $this->post('corp/user_stat/batch_get?access_token='.$this->getAccessToken(), $params);
    }
}
