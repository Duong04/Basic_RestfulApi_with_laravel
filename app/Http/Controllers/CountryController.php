<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Countries;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/countries",
     *     operationId="getCountries",
     *     tags={"Countries"},
     *     summary="Get a list of countries",
     *     security={{"bearerAuth": {}}},
     *     description="Retrieve a list of countries from the database and return it as a JSON response.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="country_name", type="string", example="Country Name"),
     *                 @OA\Property(property="country_code", type="string", example="VN"),
     *             )
     *         )
     *     )
     * )
     */
    public function index() {
        $results = Countries::get();
        return response()->json($results);
    }

    /**
     * @OA\Post(
     *     path="/api/countries",
     *     summary="Create a new country",
     *     tags={"Countries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Country data",
     *         @OA\JsonContent(
     *             required={"country_code", "country_name"},
     *             @OA\Property(property="country_code", type="string", example="US", description="Country code (max 2 characters)"),
     *             @OA\Property(property="country_name", type="string", example="United States", description="Country name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Country created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Country created successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"country_code": {"The country code field is required."}})
     *         )
     *     )
     * )
     */
    public function addCountry(Request $request) {

        $request->validate([
            'country_code' => 'required|max:2|unique:apps_countries,country_code',
            'country_name' => 'required'
            ], 
        );
        Countries::create($request->all());
        
        return response()->json(['message' => 'Country created successfully!'], 201);
    }

    /**
     * Update a country.
     *
     * @OA\Put(
     *     path="/api/countries/{id}",
     *     summary="Update a country",
     *     tags={"Countries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the country",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Country data",
     *         @OA\JsonContent(
     *             required={"country_code", "country_name"},
     *             @OA\Property(property="country_code", type="string", example="US", description="Country code (max 2 characters)"),
     *             @OA\Property(property="country_name", type="string", example="United States", description="Country name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Country updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Country updated successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"country_code": {"The country code field is required."}})
     *         )
     *     )
     * )
     */
    public function updateCountry(Request $request, string $id)
    {
        $request->validate([
            'country_code' => ['required','max:2', Rule::unique('apps_countries')->ignore($id)],
            'country_name' => 'required'
        ]);
        $country = Countries::findOrFail($id); 
        $country->update($request->all());
        return response()->json(['message' => 'Country update successfully!'], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/countries/{id}",
     *     summary="Delete a country by ID",
     *     tags={"Countries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the country to delete",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Country deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Country deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Country not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Country not found")
     *         )
     *     )
     * )
     */
    public function deleteCountry(string $id)
    {
        $country = Countries::findOrFail($id); 
        $country->delete();
        return response()->json(['message' => 'Country deleted successfully'], 204);
    }
    /**
     * @OA\Get(
     *     path="/api/countries/paginate/{quantity}",
     *     summary="Get paginated list of countries",
     *     description="Retrieve a paginated list of countries with the specified quantity per page.",
     *     tags={"Countries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="quantity",
     *         in="path",
     *         required=true,
     *         description="Number of countries per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of countries",
     *         @OA\JsonContent(
     *             type="array",
     *          @OA\Items(
     *             @OA\Property(property="id", type="integer", description="Country ID"),
     *             @OA\Property(property="country_name", type="string", description="Country name"),
     *             @OA\Property(property="country_code", type="string", description="Country code")
     *              )
     *         )
     *     )
     * )
    */
    public function countryPaginate($quantity) {
        $results = Countries::paginate($quantity);
        return response()->json($results);
    }

    /**
     * @OA\Get(
     *     path="/api/countries/{id}",
     *     summary="Retrieve a specific country by ID",
     *     tags={"Countries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the country",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Country ID"),
     *             @OA\Property(property="country_name", type="string", description="Country name"),
     *             @OA\Property(property="country_code", type="string", description="Country code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Country not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Country not found")
     *         )
     *     )
     * )
     */
    public function getCountryId($id) {
        $results = Countries::find($id);
        if ($results) {
            return response()->json($results);
        }else {
            return response()->json(['error' => 'Country not found'], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/countries/code/{code}",
     *     summary="Retrieve a specific country by code",
     *     tags={"Countries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Code of the country",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", description="Country ID"),
     *             @OA\Property(property="country_name", type="string", description="Country name"),
     *             @OA\Property(property="country_code", type="string", description="Country code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Country not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Country not found")
     *         )
     *     )
     * )
     */
    public function getCountryCode($code) {
        $result = Countries::where('country_code', $code)->first();
        if ($result) {
            return response()->json($result);
        } else {
            return response()->json(['error' => 'Country not found'], 404);
        }
    }

}