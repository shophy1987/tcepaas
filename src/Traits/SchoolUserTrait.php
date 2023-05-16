<?php 

namespace Tcepaas\Traits;

trait SchoolUserTrait
{
    public function getSchoolDepartStudents($params)
    {
        Utils::checkArrayPositiveInt($params, 'department_id');
        Utils::checkArrayPositiveInt($params, 'page_index');
        Utils::checkArrayPositiveInt($params, 'page_size');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/user/list_simple', $params);
    }

    public function getSchoolDepartStudentsDetail($params)
    {
        Utils::checkArrayPositiveInt($params, 'department_id');
        Utils::checkArrayPositiveInt($params, 'page_index');
        Utils::checkArrayPositiveInt($params, 'page_size');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/user/list', $params);
    }

    public function getSchoolStudentDetail($params)
    {
        Utils::checkArrayEmptyStr($params, 'userid');
        return $this->post('school/user/get_student_info?access_token='.$this->getAccessToken(), $params);
    }

    public function batchSchoolStudentsDetail($params)
    {
        Utils::checkEmptyStrArray($params, 'userids');
        return $this->post('school/user/batch_get_student_info?access_token='.$this->getAccessToken(), $params);
    }

    public function getSchoolParentDetail($params)
    {
        Utils::checkArrayEmptyStr($params, 'userid');
        return $this->post('school/user/get_parent_info?access_token='.$this->getAccessToken(), $params);
    }

    public function batchSchoolParentsDetail($params)
    {
        Utils::checkEmptyStrArray($params, 'userids');
        return $this->post('school/user/batch_get_parent_info?access_token='.$this->getAccessToken(), $params);
    }

    public function getSchoolUserDetail($params)
    {
        Utils::checkArrayEmptyStr($params, 'userid');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/user/get', $params);
    }

    public function batchSchoolUsersDetail($params)
    {
        Utils::checkEmptyStrArray($params, 'userid_list');
        return $this->post('school/user/get?access_token='.$this->getAccessToken(), $params);
    }

    public function batchSchoolParents($params)
    {
        Utils::checkEmptyStrArray($params, 'userids');
        return $this->post('school/parent/batch_get?access_token='.$this->getAccessToken(), $params);
    }

    public function batchSchoolStudents($params)
    {
        Utils::checkEmptyStrArray($params, 'userids');
        return $this->post('school/student/batch_get_by_parent?access_token='.$this->getAccessToken(), $params);
    }

    public function searchSchoolStudent($params)
    {
        Utils::checkArrayPositiveInt($params, 'page_index');
        Utils::checkArrayPositiveInt($params, 'page_size');
        return $this->post('school/student/search?access_token='.$this->getAccessToken(), $params);
    }

    public function searchSchoolParent($params)
    {
        Utils::checkArrayEmptyStr($params, 'name');
        Utils::checkArrayPositiveInt($params, 'page_index');
        Utils::checkArrayPositiveInt($params, 'page_size');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/parent/search', $params);
    }

    public function searchSchoolStudentInSubOrgs($params)
    {
        Utils::checkArrayPositiveInt($params, 'page_index');
        Utils::checkArrayPositiveInt($params, 'page_size');
        return $this->post('school/student/search_in_sub_orgs?access_token='.$this->getAccessToken(), $params);
    }

    public function searchSchoolStudentProfile($params)
    {
        if (!isset($params['search_keys'])) {
            throw new ArgumentException("missing reuqired key", 'search_keys');
        }

        Utils::checkArrayEmptyStr($params['search_keys'], 'key');
        Utils::checkArrayEmptyStr($params['search_keys'], 'value');
        Utils::checkArrayPositiveInt($params, 'page_index');
        Utils::checkArrayPositiveInt($params, 'page_size');
        return $this->post('school/student/search_in_sub_orgs?access_token='.$this->getAccessToken(), $params);
    }
}
