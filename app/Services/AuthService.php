<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AuthService
{
    public function validateLogin(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
            ],
            'password' => 'required',
        ]);
    }

    public function validateRegister(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                'unique:users',
            ],
            'password' => 'required|string',
        ]);
    }


    public function doRegister(Request $request)
    {
        $data = [
            'name' => data_get($request, 'name'),
            'email' => data_get($request, 'email'),
            'password' => Hash::make(data_get($request, 'password')),
        ];

        return User::create($data);
    }
}
