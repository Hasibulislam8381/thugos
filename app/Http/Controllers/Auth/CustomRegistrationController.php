<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomRegistrationController extends Controller
{
    public function register_user(Request $request)
    {

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            // 'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            flash('Password must not less than 6')->error();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $phoneNum = $request->session()->get('phone_num');


        $user = User::where('phone', $phoneNum)->first();



        if ($user) {
            // $avatarName = $user->id . '_avatar' . time() . '.' . $request->profile_image->getClientOriginalExtension();
            // $request->profile_image->storeAs('avatars', $avatarName);


            $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->email_verified_at = now();
            // $user->avatar_original = $request->photo;
            $user->save();
        }

        auth()->login($user);

        flash('Registration Successfull')->success();
        return redirect()->route('home');
    }
    public function register_user_cart(Request $request)
    {


        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            // 'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            flash('Password must not less than 6')->error();
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $phoneNum = $request->phone_num ?? $request->session()->get('phone_num');
        
        $user = User::where('phone', $phoneNum)->first();

        if ($user) {
            // $avatarName = $user->id . '_avatar' . time() . '.' . $request->profile_image->getClientOriginalExtension();
            // $request->profile_image->storeAs('avatars', $avatarName);


            // $user->name = $request->name;
            $user->password = Hash::make($request->password);
            $user->email_verified_at = now();
            // $user->avatar_original = $request->photo;
            $user->save();
        }

        auth()->login($user);

        flash('Registration Successfull')->success();
        return redirect()->route('checkout.shipping_info');
    }
}