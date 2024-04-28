<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use Illuminate\Validation\Rule;
use App\Models\Course;

class CourseController extends Controller
{
/**
 * @OA\Get(
 *      path="/api/courses",
 *      operationId="getAllCourses",
 *      tags={"Courses"},
 *      summary="Get all Courses",
 *      security={{"bearerAuth": {}}},
 *      description="Retrieve a list of courses from the database and return it as a JSON response.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="id", type="integer", example=1),
 *                  @OA\Property(property="course_name", type="string", example="Course Name"),
 *                  @OA\Property(property="course_code", type="string", example="C1"),
 *                  @OA\Property(property="course_image", type="string", example="image.png"),
 *                  @OA\Property(property="classes", type="object",
 *                      @OA\Property(property="id", type="integer", example=1),
 *                      @OA\Property(property="name", type="string", example="classe Name"),
 *                      @OA\Property(property="code", type="string", example="Classe code"),
 *                      @OA\Property(property="description", type="string", example="Description"),
 *                      @OA\Property(property="schedule", type="string", example="Wednesday"),
 *                      @OA\Property(property="start_date", type="date", example="2024-05-01"),
 *                      @OA\Property(property="end_date", type="date", example="2024-05-01"),
 *                  )
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server Error",
 *      )
 * )
 */
    public function index()
    {
        $courses = Course::with('classes')->get();
        return response()->json($courses, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/add-course",
     *     summary="Create a new course",
     *     security={{"bearerAuth": {}}},
     *     description="Creates a new course with course code, name, and image.",
     *     tags={"Courses"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Course data to be added",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="course_code", type="string", maxLength=3, example="001", description="Code of the course"),
     *                 @OA\Property(property="course_name", type="string", example="Course A", description="Name of the course"),
     *                 @OA\Property(property="course_image", type="string", format="binary", description="Image file of the course")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Course created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object", example={"course_code": {"The code field is required."}})
     *         )
     *     )
     * )
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

        Course::create($data);

        return response()->json(['message' => 'Course created successfully'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/get-course/{id}",
     *     security={{"bearerAuth": {}}},
     *     summary="Retrieve a specific course by ID",
     *     description="Retrieves details of a course based on its ID.",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the course",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID of the course"),
     *             @OA\Property(property="course_code", type="string", example="001", description="Code of the course"),
     *             @OA\Property(property="course_name", type="string", example="Course A", description="Name of the course"),
     *             @OA\Property(property="course_image", type="string", example="image.png", description="Image of the course")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Course not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        return response()->json($course);
    }

    /**
     * @OA\Put(
     *     path="/api/update-course/{id}",
     *     summary="Update a course",
     *     security={{"bearerAuth": {}}},
     *     description="Updates an existing course with course code, name, and optional image.",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the course to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Course data to be updated",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="course_code", type="string", maxLength=3, example="001", description="Code of the course"),
     *                 @OA\Property(property="course_name", type="string", example="Course A", description="Name of the course"),
     *                 @OA\Property(property="course_image", type="string", format="binary", description="Image file of the course")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Course updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Course not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object", example={"course_code": {"The code field is required."}})
     *         )
     *     )
     * )
     */
    public function update(CourseRequest $request, string $id)
    {
        $data = $request->validated();
    
        $course = Course::find($id);
    
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        if ($request->hasFile('course_image')) {
            $course_image = $request->file('course_image');
            $image = $course_image->getClientOriginalName();
            $course_image->move(public_path('assets/courses'), $image);
            $data['course_image'] = $image;
        }
    
        $course->update($data);
    
        return response()->json(['message' => 'Course updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/delete-course/{id}",
     *     summary="Delete a specific course by ID",
     *     security={{"bearerAuth": {}}},
     *     description="Deletes a course based on its ID.",
     *     tags={"Courses"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the course",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Course deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Course deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Course not found")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $course->delete();
        return response()->json(['message' => 'Course deleted successfully'], 204);
    }
}
