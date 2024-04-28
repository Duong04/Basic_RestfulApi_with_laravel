<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use App\Mail\sendmail;
use Illuminate\Support\Str;
use Auth;
use Mail;


class UserController extends Controller {
    
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->status == 0) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is not active.',
                ])->onlyInput('email');
            }

            if(Auth::user()->role == 1) {
                return redirect()->intended('admin');
            }
            return redirect()->intended('profile');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(RegisterRequest $request) {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']); 
        $data['status'] = false;
        $data['activation_token'] = Str::random(60);
        $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
        unset($data['password_confirmation']);

        $user = User::create($data);

        Mail::to($user->email)->send(new sendmail($user->activation_token));

        return back()->with('message', 'User registered successfully. Please check your email for activation instructions.');
    }

    public function active($token) {
        $user = User::where('activation_token', $token)->first();
    
        if (!$user) {
            return new HttpResponseException(abort(404));
        }
    
        $user->status = true;
        $user->activation_token = null;
        $user->save();
    
        return redirect()->route('login');
    }

    public function sendResetEmail(Request $request) {
        $request->validate(['email'=>'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        
    }

    public function resetPassword(ResetPasswordRequest $request) {
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

        return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
    }

    public function userProfile() {

        $user = Auth::user();
            
        return view('customers.profile', compact('user'));
    }

    public function updateProfile(ProfileRequest $request) {
        $request->validated();

        $user = $request->user();
        $user->phone = $request->input('phone');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->name = $request->input('first_name') . ' ' . $request->input('last_name');

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = $user->id.'_avatar_'.$avatar->getClientOriginalName();
            $avatar->move(public_path('assets/avatar'), $avatarName);
            $user->avatar = $avatarName;
        }

        $user->save();

        return back()->with('success', 'Cập nhật thành công!');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }

}