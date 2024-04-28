<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UrlGenerationController extends Controller {
    function show($id) {
        $url = url("/post/$id");
        return redirect()->to($url);
    }

    function getPost($post) {
        // return route('newPost', ['post' => 'hello']);
        // return URL::signedRoute('newPost', ['post' => 1], absolute: false);
        return URL::temporarySignedRoute(
            'newPost', now()->addMinutes(30), ['post' => 1]
        );
    }
}