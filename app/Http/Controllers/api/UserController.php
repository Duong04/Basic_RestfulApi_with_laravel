<?php

namespace App\Http\Controllers\api;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Mail\sendmail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Auth;
use Mail;

class UserController extends Controller
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     description="Authenticate user by providing email and password.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", format="email", description="User email"),
     *                 @OA\Property(property="password", type="string", description="User password")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="api_token", type="string", example="your_api_token"),
     *             @OA\Property(property="redirect", type="string", example="profile")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="The provided credentials do not match our records.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unprocessable Entity"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if ($token = auth('api')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = auth('api')->user(); 

            if ($user->status == 0) {
                auth('api')->logout(); 
                return response()->json('Your account is not active.', 401);
            }

            $redirectUrl = $user->role == 1 ? 'admin' : 'profile';

            return response()->json([
                'message' => 'Login successful',
                'token' => $this->respondWithToken($token),
                'redirect' => $redirectUrl,
            ]);
        }

        return response()->json(['error' => 'The provided credentials do not match our records.'], 401);
    }
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Registers a new user by providing the required information and sends an activation email to the user upon successful registration.",
     *     tags={"Authentication"},    
     *     @OA\RequestBody(
     *         required=true,
     *         description="Registration information",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="first_name", type="string", description="First name"),
     *                 @OA\Property(property="last_name", type="string", description="Last name"),
     *                 @OA\Property(property="dob", type="date", description="2004-09-30"),
     *                 @OA\Property(property="address", type="string", description="Đã Nẵng"),
     *                 @OA\Property(property="email", type="string", format="email", description="Email address"),
     *                 @OA\Property(property="password", type="string", format="password", description="Password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully. Please check your email for activation instructions.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unprocessable Entity"),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"first_name": {"The first name field is required."}})
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']); 
        $data['status'] = false;
        $data['activation_token'] = Str::random(60);
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
        unset($data['password_confirmation']);

        $user = User::create($data);

        Mail::to($user->email)->send(new Sendmail($user->activation_token));

        return response()->json([
            'message' => 'User registered successfully. Please check your email for activation instructions.',
            'user' => $user, 
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/active/{token}",
     *     summary="Activate user",
     *     description="Activate user account by providing activation token.",
     *     tags={"Authentication"},
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         required=true,
     *         description="Activation token",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User activated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User activated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function active($token) {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->status = true;
        $user->activation_token = null;
        $user->save();

        return response()->json(['message' => 'User activated successfully'], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/send-reset-email",
     *     summary="Send password reset email",
     *     description="Send a password reset email to the specified email address.",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email address to send the password reset link.",
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset email sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Password reset email sent successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function sendResetEmail(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['status' => __($status)]);
        } else {
            return response()->json(['error' => __($status)], 422);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Reset password",
     *     description="Reset the user password using the provided token and new password.",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Information needed to reset the password.",
     *         @OA\JsonContent(
     *             required={"token", "email", "password", "password_confirmation"},
     *             @OA\Property(property="token", type="string", description="The password reset token", example="reset_token"),
     *             @OA\Property(property="email", type="string", format="email", description="The email address of the user", example="user@example.com"),
     *             @OA\Property(property="password", type="string", description="The new password", example="new_password"),
     *             @OA\Property(property="password_confirmation", type="string", description="The confirmation of the new password", example="new_password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Password reset successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or token invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
    
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['status' => __($status)]);
        } else {
            return response()->json(['error' => __($status)], 422);
        }
    }

    public function profile() {

        return response()->json(auth()->user('api'));
    }
    /**
     * @OA\Put(
     *     path="/api/update-profile",
     *     summary="Update user profile",
     *     description="Update the user profile information including avatar, phone number, first name, last name, and address.",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User profile data",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="avatar", type="file", format="binary", description="User avatar image (optional)"),
     *                 @OA\Property(property="phone", type="string", description="User phone number (optional)"),
     *                 @OA\Property(property="first_name", type="string", description="User first name"),
     *                 @OA\Property(property="last_name", type="string", description="User last name"),
     *                 @OA\Property(property="address", type="string", description="User address")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Cập nhật thành công!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function updateProfile(ProfileRequest $request)
    {
        $validator = validated();

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $user->phone = $request->input('phone');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->name = $request->input('first_name') . ' ' . $request->input('last_name');

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = $user->id . '_avatar_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('assets/avatar'), $avatarName);
            $user->avatar = $avatarName;
        }

        $user->save();

        return response()->json(['success' => 'Cập nhật thành công!'], 204);
    }

    /**
     * @OA\Get(
     *      path="/api/users",
     *      operationId="getAllUsers",
     *      tags={"Users"},
     *      summary="Get all users",
     *      security={{"bearerAuth": {}}},
     *      description="Retrieve a list of all users from the database and return it as a JSON response.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Nguyen Thanh Duong"),
     *                  @OA\Property(property="email", type="string", example="example@gmail.com"),
     *                  @OA\Property(property="first_name", type="string", example="Nguyen Thanh"),
     *                  @OA\Property(property="last_name", type="string", example="Duong")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server Error"
     *      )
     * )
     */
    public function users() {
        $listUsers = User::all();
        return response()->json($listUsers, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Logs out the authenticated user, invalidating the current JWT token.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="string",
     *             example="Logout successful"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             type="string",
     *             example="Unauthenticated"
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(
            ['message' => 'Successfully logged out']
        );
    }
}
