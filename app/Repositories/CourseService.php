<?php 

namespace App\Repositories;

use App\Models\Course;

class CourseService {
    public function all()
    {
        return Course::all();
    }

    public function create($data)
    {
        return Course::create($data);
    }

    public function find($id)
    {
        return Course::findOrFail($id);
    }

    public function update($id, $data)
    {
        $course = Course::findOrFail($id);
        $course->update($data);
        return $course;
    }

    public function delete($id)
    {
        $country = Course::findOrFail($id);
        $country->delete();
    }
}