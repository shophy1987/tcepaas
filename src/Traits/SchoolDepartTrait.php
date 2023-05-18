<?php 

namespace Tcepaas\Traits;

use Tcepaas\Helper\Utils;

trait SchoolDepartTrait
{
    public function getSchoolDepart($params = [])
    {
        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/department/get', $params);
    }

    public function getSchoolDeparts($params = [])
    {
        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/department/list', $params);
    }

    public function getSchoolDepartsByTag($params = [])
    {
        Utils::checkPositiveIntArray($params, 'tag');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/department/list_by_tag', $params);
    }

    public function getClassStudentNum($params = [])
    {
        Utils::checkPositiveIntArray($params, 'department_ids');
        return $this->post('school/department/get_student_num?access_token='.$this->getAccessToken(), $params);
    }

    public function searchSchoolDepart($params = [])
    {
        return $this->post('school/department/search?access_token='.$this->getAccessToken(), $params);
    }
}
