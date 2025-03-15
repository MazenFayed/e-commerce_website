<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Traits\MustVerifyMobile;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'phone' => 'required|string|max:255|phone:AUTO|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    $phone =phone($request->phone)->formatE164();
    $length= Str::length($phone)-2 ;
    $phone = substr($phone,2,$length);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone ,
            'password' => Hash::make($request->password),
            'mobile_verify_code' => random_int(111111, 999999),
        ]);

        event(new Registered($user));

        //Auth::login($user);
        //$request->user()->sendMobileVerificationNotification(true);
        return redirect(RouteServiceProvider::HOME);
        //return redirect()->route('user.home');
    }
}
