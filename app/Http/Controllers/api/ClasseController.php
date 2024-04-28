<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classe;
use Illuminate\Validation\Rule;
use App\Http\Requests\ClassRequest;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     /**
     * @OA\Get(
     *      path="/api/classes",
     *      operationId="getAllClasses",
     *      tags={"Classes"},
     *      summary="Get all classes",
     *      security={{"bearerAuth": {}}},
     *      description="Retrieve a list of classes from the database and return it as a JSON response.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Class Name"),
     *                  @OA\Property(property="code", type="string", example="CL1"),
     *                  @OA\Property(property="start_date", type="string", format="date", example="2024-03-01"),
     *                  @OA\Property(property="end_date", type="string", format="date", example="2024-03-01"),
     *                  @OA\Property(property="schedule", type="string", example="Monday"),
     *                  @OA\Property(property="description", type="string", example="Class schedule at 8:00 AM"),
     *                  @OA\Property(property="course_id", type="integer", example="1"),
     *                  @OA\Property(property="courses", type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="course_name", type="string", example="Course Name"),
     *                      @OA\Property(property="course_code", type="string", example="Course code"),
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
        $lists = Classe::with('course')->get();
        return response()->json($lists, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/add-classe",
     *      summary="Create a new classe",
     *      description="Creates a new classe resource.",
     *      tags={"Classes"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Classe object that needs to be added to the store",
     *          @OA\JsonContent(
     *              required={"name", "code", "start_date", "end_date", "schedule", "description"},
     *              @OA\Property(property="name", type="string", example="Class A", description="Name of the class"),
     *              @OA\Property(property="code", type="string", example="001", description="Code of the class"),
     *              @OA\Property(property="start_date", type="string", format="date", example="2024-03-24", description="Start date of the class"),
     *              @OA\Property(property="end_date", type="string", format="date", example="2024-06-30", description="End date of the class"),
     *              @OA\Property(property="course_id", type="integer", example="1", description="Course id"),
     *              @OA\Property(property="schedule", type="string", example="Monday, Wednesday, Friday", description="Class schedule"),
     *              @OA\Property(property="description", type="string", example="This is Class A", description="Description of the class")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Classe created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Classe created successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid"),
     *              @OA\Property(property="errors", type="object", example={"code": {"The code field is required."}})
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|max:6|unique:classes,code',
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'course_id' => 'required',
            'schedule' => 'required',
            'description' => 'required'
        ]);
    
        Classe::create($data);
        
        return response()->json(['message' => 'Classe created successfully'], 201);
    }

    
    /**
     * @OA\Get(
     *     path="/api/get-classe/{id}",
     *     summary="Retrieve a specific classe by ID",
     *     description="Retrieves details of a classe based on its ID.",
     *     security={{"bearerAuth": {}}},
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the classe",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example="1", description="ID of the class"),
     *              @OA\Property(property="name", type="string", example="Class A", description="Name of the class"),
     *              @OA\Property(property="code", type="string", example="001", description="Code of the class"),
     *              @OA\Property(property="start_date", type="string", format="date", example="2024-03-24", description="Start date of the class"),
     *              @OA\Property(property="end_date", type="string", format="date", example="2024-06-30", description="End date of the class"),
     *              @OA\Property(property="course_id", type="integer", example="1", description="Course id"),
     *              @OA\Property(property="schedule", type="string", example="Monday, Wednesday, Friday", description="Class schedule"),
     *              @OA\Property(property="description", type="string", example="This is Class A", description="Description of the class")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Class not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Class not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $classe = Classe::find($id);

        if (!$classe) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        return response()->json($classe);
    }

    
    /**
     * @OA\Put(
     *     path="/api/update-classe/{id}",
     *     summary="Update a specific classe by ID",
     *     description="Updates details of a classe based on its ID.",
     *     security={{"bearerAuth": {}}},
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the classe",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Classe object that needs to be updated",
     *         @OA\JsonContent(
     *              required={"name", "code", "start_date", "end_date", "schedule", "description"},
     *              @OA\Property(property="name", type="string", example="Class A", description="Name of the class"),
     *              @OA\Property(property="code", type="string", example="001", description="Code of the class"),
     *              @OA\Property(property="start_date", type="string", format="date", example="2024-03-24", description="Start date of the class"),
     *              @OA\Property(property="end_date", type="string", format="date", example="2024-06-30", description="End date of the class"),
     *              @OA\Property(property="course_id", type="integer", example="1", description="Course id"),
     *              @OA\Property(property="schedule", type="string", example="Monday, Wednesday, Friday", description="Class schedule"),
     *              @OA\Property(property="description", type="string", example="This is Class A", description="Description of the class")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Classe updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Classe updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Class not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Class not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid"),
     *             @OA\Property(property="errors", type="object", example={"code": {"The code field is required."}})
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'code' => ['required', 'max:6', Rule::unique('classes')->ignore($id)],
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'course_id' => 'required',
            'schedule' => 'required',
            'description' => 'required'
        ]);
    
        $classe = Classe::find($id);
    
        if (!$classe) {
            return response()->json(['error' => 'Class not found'], 404);
        }
    
        $classe->update($data);
    
        return response()->json(['message' => 'Classe updated successfully'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/delete-classe/{id}",
     *     summary="Delete a specific classe by ID",
     *     security={{"bearerAuth": {}}},
     *     description="Deletes a classe based on its ID.",
     *     tags={"Classes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the classe",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Classe deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Classe deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Class not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Class not found")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $classe = Classe::find($id);
        if (!$classe) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $classe->delete();
        return response()->json(['message' => 'Classe deleted successfully'], 204);
    }
}
