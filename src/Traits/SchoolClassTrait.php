<?php 

namespace Tcepaas\Traits;

trait SchoolClassTrait
{
    // 获取学生在教师的班级列表
    public function getStudentClassByTeacher($params)
    {
        Utils::checkArrayEmptyStr($params, 'student_userid');
        Utils::checkArrayEmptyStr($params, 'teacher_userid');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('school/student/search_in_sub_orgs', $params);
    }

    // 获取班级列表
    public function getClasses($params)
    {
        Utils::checkPositiveIntArray($params, 'page_index');
        Utils::checkPositiveIntArray($params, 'page_size');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('class/list', $params);
    }

    // 通过教师获取任课班级
    public function getTeacherClasses($params)
    {
        Utils::checkArrayEmptyStr($params, 'teacher_userid');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('teacher/class/list', $params);
    }

    // 通过班级获取教师
    public function getClassTeachers($params)
    {
        Utils::checkPositiveIntArray($params, 'department_id');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('class/teacher/list', $params);
    }

    // 获取学生所属班级列表
    public function getStudentClasses($params)
    {
        Utils::checkArrayEmptyStr($params, 'userid');

        $params['access_token'] = $this->getAccessToken();
        return $this->get('student/class/list', $params);
    }
}
