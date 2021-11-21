<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Calendar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);

        $validator = Validator::make($request, [
            'name' => 'required|min:1|max:60',
            'password'  => 'required|confirmed|min:6|max:28',
            'email'     => 'required|unique:App\Models\User,email',
            'role' => 'in:admin,user'
        ]);
        if ($validator->fails()) {
            return response([
                'status' => 400,
                $validator->errors()->toArray(),
            ], 400);
        }

        $request['password'] = Hash::make($request['password']);

        $user = User::create($request);

        return response([
            $user
        ], 200);
    }
}
