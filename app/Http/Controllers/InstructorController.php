<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function dashboard()
    {
        $viewData = [
            'meta_title' => 'Instructor Dashboard | LMS',
            'meta_desc'  => 'Manage your courses, assignments, and students',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.dashboard', $viewData);
    }

    // Course Management
    public function createCourse()
    {
        $viewData = [
            'meta_title' => 'Create Course | LMS',
            'meta_desc'  => 'Create a new course',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.create-courses', $viewData);
    }

    public function manageCourses()
    {
        $viewData = [
            'meta_title' => 'Manage Courses | LMS',
            'meta_desc'  => 'Manage your courses',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.courses', $viewData);
    }

    // Lecture Materials
    public function uploadMaterial()
    {
        $viewData = [
            'meta_title' => 'Upload Material | LMS',
            'meta_desc'  => 'Upload lecture materials',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.materials.upload', $viewData);
    }

    public function viewMaterials()
    {
        $viewData = [
            'meta_title' => 'View Materials | LMS',
            'meta_desc'  => 'View lecture materials',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.materials.index', $viewData);
    }

    // Assignments
    public function createAssignment()
    {
        $viewData = [
            'meta_title' => 'Create Assignment | LMS',
            'meta_desc'  => 'Create a new assignment',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.create-assignments', $viewData);
    }

    public function manageAssignments()
    {
        $viewData = [
            'meta_title' => 'Manage Assignments | LMS',
            'meta_desc'  => 'Manage your assignments',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.assignments', $viewData);
    }

    // Submissions
    public function viewSubmissions()
    {
        $viewData = [
            'meta_title' => 'View Submissions | LMS',
            'meta_desc'  => 'View student submissions',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.submissions', $viewData);
    }

    public function gradeAssignments()
    {
        $viewData = [
            'meta_title' => 'Grade Assignments | LMS',
            'meta_desc'  => 'Grade student assignments',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.grade-assignments', $viewData);
    }

    // Students
    public function viewEnrolledStudents()
    {
        $viewData = [
            'meta_title' => 'Enrolled Students | LMS',
            'meta_desc'  => 'View enrolled students',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.students', $viewData);
    }

    // Messages
    public function messages()
    {
        $viewData = [
            'meta_title' => 'Messages | LMS',
            'meta_desc'  => 'View and send messages',
            'meta_image' => url('assets/images/logo/logo.png'),
        ];

        return view('instructor.messages', $viewData);
    }
}