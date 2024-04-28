<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseRegister;
use Auth;

class CourseRegisterController extends Controller
{
    public function index() {
        $courses = CourseRegister::with('user', 'course', 'classe')->get();
        return view('admin.courseRegister.list', compact('courses'));
    }
}
