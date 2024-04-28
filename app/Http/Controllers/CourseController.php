<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CourseService;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $courseService;

    public function __construct(CourseService $courseService) {
        $this->courseService = $courseService;
    }
    
    public function index()
    {
        $listCourses = $this->courseService->all();
        return view('admin.courses.list', compact('listCourses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courses.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('course_image')) {
            $course_image = $request->file('course_image');
            $image = $course_image->getClientOriginalName();
            $course_image->move(public_path('assets/courses'), $image);
            $data['course_image'] = $image;
        }

        $this->courseService->create($data);
        return back()->with('success','Courses created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result = $this->courseService->find($id);
        return view('admin.courses.update', compact('result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id)
    {
        $data = $request->validated();

        if ($request->hasFile('course_image')) {
            $course_image = $request->file('course_image');
            $image = $course_image->getClientOriginalName();
            $course_image->move(public_path('assets/courses'), $image);
            $data['course_image'] = $image;
        }

        $this->courseService->update($id, $data);

        return redirect()->route('course.list')->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->courseService->delete($id);
        return back()->with('success', 'Course destroy successfully!');
    }
}
