<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Course;
use App\Models\CourseRegister;
use Auth;

class SiteController extends Controller
{
    public function showCourse() {
        $courses = Course::with('classes')->get();

        return view('customers.courses', compact('courses'));
    }

    public function courseRegister(Request $request) {
        $data = $request->validate([
            'class_id' => 'required',
            'course_id' => 'required'
        ]);
    
        $user_id = Auth::id();
    
        $existingRegistration = CourseRegister::where('user_id', $user_id)
            ->where('class_id', $data['class_id'])
            ->exists();
    
        if ($existingRegistration) {
            return back()->with('error', 'You have already registered for this course in this class');
        }
    
        $data['user_id'] = $user_id;
        CourseRegister::create($data);

        return back()->with('success', 'Successfully registered for the course');
    }

    public function showCourseRegister() {
        $courses = CourseRegister::with('user', 'course', 'classe')->where('user_id', Auth::id())->get();
        return view('customers.registerCourses', compact('courses'));
    }
}
