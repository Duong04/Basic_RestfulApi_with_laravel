<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\AdminCountryController;
use App\Http\Controllers\UrlGenerationController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\CourseRegisterController;
use Illuminate\Http\Request;
use App\Http\Controllers\api\UserController as ApiUserController;
use App\Http\Controllers\api\ClasseController as ApiClasseController;
use App\Http\Controllers\api\CourseController as ApiCourseController;
use App\Http\Controllers\api\CourseRegisterController as ApiCourseRegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('customers.login');
})->name('login');

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);

Route::get('/register', function () {
    return view('customers.register');
})->name('register');

Route::post('/register', [UserController::class, 'register'])->name('register');

Route::get('/active/{token}', [UserController::class, 'active']);

Route::get('/forgot-password', function () {
    return view('customers.forgotPsw');
});

Route::post('/forgot-password', [UserController::class, 'sendResetEmail']);

Route::get('/reset-password/{token}', function($token, Request $request) {
    $email = $request->query('email');
    return View('customers.resetPsw', ['token' => $token, 'email' => $email] );
})->name('password.reset');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/profile', [UserController::class, 'userProfile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile']);
    
    // ----------------------------------------------------------------
    Route::get('/view-courses', [SiteController::class, 'showCourse']);
    Route::post('/register-courses', [SiteController::class, 'courseRegister'])->name('courses.register');
    Route::get('/register-courses', [SiteController::class, 'showCourseRegister']);
});

Route::prefix('api')->group(function() {
    Route::middleware(['api', 'jwt.auth'])->group(function () {
        Route::get('/countries', [CountryController::class, 'index']);
        Route::get('/countries/paginate/{quantity}', [CountryController::class, 'countryPaginate']);
        Route::get('/countries/{id}', [CountryController::class, 'getCountryId']);
        Route::get('/countries/code/{code}', [CountryController::class, 'getCountryCode']);
        Route::post('/countries', [CountryController::class, 'addCountry']);
        Route::put('/countries/{id}', [CountryController::class, 'updateCountry']);
        Route::delete('/countries/{id}', [CountryController::class, 'deleteCountry']);
        Route::post('/logout', [ApiUserController::class, 'logout']);
        Route::get('/profile', [ApiUserController::class, 'profile']);
        Route::put('/update-profile', [ApiUserController::class, 'updateProfile']);
        Route::get('/users', [ApiUserController::class, 'users']);
        Route::get('/classes', [ApiClasseController::class, 'index']);
        Route::post('/add-classe', [ApiClasseController::class, 'store']);
        Route::get('/get-classe/{id}', [ApiClasseController::class, 'show']);
        Route::put('/update-classe/{id}', [ApiClasseController::class, 'update']);
        Route::delete('/delete-classe/{id}', [ApiClasseController::class, 'destroy']);
        Route::get('/courses', [ApiCourseController::class, 'index']);
        Route::post('/add-course', [ApiCourseController::class, 'store']);
        Route::get('/get-course/{id}', [ApiCourseController::class, 'show']);
        Route::put('/update-course/{id}', [ApiCourseController::class, 'update']);
        Route::delete('/delete-course/{id}', [ApiCourseController::class, 'destroy']);
        Route::get('/course-register', [ApiCourseRegisterController::class, 'index']);
        Route::post('/course-register', [ApiCourseRegisterController::class, 'courseRegister']);
    });

    Route::post('/login', [ApiUserController::class, 'login']);
    Route::post('/register', [ApiUserController::class, 'register']);
    Route::get('/active/{token}', [ApiUserController::class, 'active']);
    Route::post('/send-reset-email', [ApiUserController::class, 'sendResetEmail']);
    Route::post('/reset-password', [ApiUserController::class, 'resetPassword']);
});

Route::prefix('admin')->middleware('check.role')->group(function () {
    Route::get('/', function (){
        return view('admin.dashboard');
    });

    Route::get('/list-countries', [AdminCountryController::class, 'index']);
    
    Route::get('/add-country', [AdminCountryController::class, 'create']);
    Route::post('/add-country', [AdminCountryController::class, 'store']);
    
    Route::get('/edit-country/{id}', [AdminCountryController::class, 'edit'])->name('updateCountry');
    Route::put('/edit-country/{id}', [AdminCountryController::class, 'update']);
    Route::delete('/delete-country/{id}', [AdminCountryController::class, 'destroy']);

    // ---------------- users -----------------
    Route::get('/list-users', [AdminUserController::class, 'index']);

    // --------------- Courses ----------------
    Route::get('/list-courses', [CourseController::class, 'index'])->name('course.list');
    Route::get('/add-course', [CourseController::class, 'create'])->name('course.show.add');
    Route::post('/add-course', [CourseController::class, 'store'])->name('course.add');
    Route::get('/edit-course/{id}', [CourseController::class, 'edit']);
    Route::put('/edit-course/{id}', [CourseController::class, 'update'])->name('course.update');
    Route::delete('/delete-course/{id}', [CourseController::class, 'destroy']);

    // --------------- Clases ----------------
    Route::get('/list-classes', [ClasseController::class, 'index'])->name('classe.list');
    Route::get('/add-classe', [ClasseController::class, 'create'])->name('classe.show.add');
    Route::post('/add-classe', [ClasseController::class, 'store'])->name('classe.add');
    Route::get('/edit-classe/{id}', [ClasseController::class, 'edit']);
    Route::put('/edit-classe/{id}', [ClasseController::class, 'update'])->name('classe.update');
    Route::delete('/delete-classe/{id}', [ClasseController::class, 'destroy']);

    // -------------- Course register -------------
    Route::get('/course-register', [CourseRegisterController::class, 'index']);
});

// ------------- URL Generation -----------------------
Route::get('/urlGeneration/{id}', [UrlGenerationController::class, 'show']);

Route::get('/post/{id?}', function ($id='Hello world') {
    // Get the current URL without the query string
    // return url()->current();

    // Get the current URL including the query string
    // return url()->full();

    // Get the full URL for the previous reques
    // return url()->previous();
    return $id;
});

// -----------------Send mail -----------------------
Route::get('/send-mail', function() {
    return view('mail.sendmail');
});

Route::post('/send-mail', [UserController::class, 'sendMail']);

Route::fallback(function () {
    return view('errors.404');
});