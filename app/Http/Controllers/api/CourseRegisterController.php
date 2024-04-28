<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseRegister;
use Auth;

class CourseRegisterController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/course-register",
     *      operationId="getAllCourseRegistrations",
     *      tags={"Courses register"},
     *      security={{"bearerAuth": {}}},
     *      summary="Get all course registrations",
     *      description="Retrieve a list of all course registrations along with associated user, course, and class information.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="user_id", type="integer", example=1),
     *                  @OA\Property(property="class_id", type="integer", example=1),
     *                  @OA\Property(property="course_id", type="integer", example=1),
     *                  @OA\Property(property="courses", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="course_name", type="string", example="Course Name"),
     *                      @OA\Property(property="course_code", type="string", example="Course code"),
     *                  ),
     *                  @OA\Property(property="classes", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Class Name"),
     *                      @OA\Property(property="code", type="string", example="Class code"),
     *                  ),
     *                  @OA\Property(property="users", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="name", type="string", example="Nguyen Thanh Duong"),
     *                      @OA\Property(property="email", type="string", example="example@gmail.com"),
     *                      @OA\Property(property="first_name", type="string", example="Nguyen Thanh"),
     *                      @OA\Property(property="last_name", type="string", example="Duong"),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error"
     *      )
     * )
     */
    public function index() {
        $courses = CourseRegister::with('user', 'course', 'classe')->get();
        return response()->json($courses, 200);;
    }


    /**
     * @OA\Post(
    *      path="/api/course-register",
    *      operationId="courseRegister",
    *      tags={"Courses register"},
    *      summary="Register for a course",
    *      security={{"bearerAuth": {}}},
    *      description="Registers a user for a course. Requires 'class_id' and 'course_id' in the request body.",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *              required={"class_id","course_id"},
    *              @OA\Property(property="class_id", type="integer", example=1),
    *              @OA\Property(property="course_id", type="integer", example=1)
    *          )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successfully registered for the course",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="Successfully registered for the course")
    *          )
    *      ),
    *      @OA\Response(
    *          response=422,
    *          description="Validation Error",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="The given data was invalid")
    *          )
    *      ),
    *      @OA\Response(
    *          response=409,
    *          description="Conflict",
    *          @OA\JsonContent(
    *              @OA\Property(property="message", type="string", example="You have already registered for this course in this class")
    *          )
    *      )
    * )
    */
    public function courseRegister(Request $request) {
        $data = $request->validate([
            'class_id' => 'required|integer',
            'course_id' => 'required|integer'
        ]);
    
        $user_id = Auth::id();
    
        $existingRegistration = CourseRegister::where('user_id', $user_id)
            ->where('class_id', $data['class_id'])
            ->exists();
    
        if ($existingRegistration) {
            return response()->json(['message' => 'You have already registered for this course in this class'], 409);
        }
    
        $data['user_id'] = $user_id;
        CourseRegister::create($data);
    
        return response()->json(['message' => 'Successfully registered for the course'], 200);
    }
}

