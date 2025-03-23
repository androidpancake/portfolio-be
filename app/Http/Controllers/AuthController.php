<?php

namespace App\Http\Controllers;

use App\apiResponseClass;
use App\Http\Requests\UserAppRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function registerUserApp(UserAppRequest $request)
    {
        $validator = $request->validated();

        if (!$validator) {
            return apiResponseClass::sendError('Validation Error', $validator->errors());
        }

        $userData = $request->all();

        $userData['password'] = bcrypt($userData['password']);
        $userAppData = User::create($userData);
        $success['token'] = $userAppData->createToken('fms-app', ['abilities:get-data'])->plainTextToken;

        return apiResponseClass::sendResponse($success, 'Registered to access app', 201);
    }

    public function getTokenAdmin(Request $request)
    {
        $userData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($userData)) {
            $user = Auth::user();
            $user->tokens()->delete();
            $success['token'] = $request->user()->createToken('fms-admin', ['admin'])->plainTextToken;
            return apiResponseClass::sendResponse($success, 'Authenticated', 201);
        } else {
            return apiResponseClass::sendError('Unauthorized', ['error' => 'Unauthorized']);
        }
    }

    public function getTokenApp(Request $request)
    {
        $userAppData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'username required',
            'password.required' => 'password required'
        ]);

        if (Auth::attempt($userAppData)) {
            $userAppAuth = Auth::user();
            if ($userAppAuth->is_approved != '1') {
                return apiResponseClass::sendError('Not Approved', ['error' => 'Unauthorized']);
            }
            $userAppAuth->tokens()->delete();
            $success['token'] = $userAppAuth->createToken('fms-app', ['get-data'])->plainTextToken;

            return apiResponseClass::sendResponse($success, 'Authenticated to access', 200);
        } else {
            return apiResponseClass::sendError('Unauthorized', ['error' => 'Unauthorized']);
        }
    }

    public function approveUserApp(Request $request)
    {
        $userData = $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ], ['user_id' => 'User not found']);

        $findUserToApprove = User::where('user_id', $userData['user_id'])->first();

        if ($findUserToApprove) {
            $findUserToApprove->is_approved = '1';
            $data = $findUserToApprove->save();
        }

        return apiResponseClass::sendResponse($data, 'Success approve user app', 201);
    }

    public function forgotPassword(Request $request)
    {
        $toValidate = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'password' => 'required|min:5|max:12'
        ]);

        $findUser = User::where('user_id', $toValidate['user_id'])->first();

        if ($findUser) {
            $this->changePassword($toValidate['password'], $findUser);
            return apiResponseClass::sendResponse($findUser['user_id'], 'Success update password for ' . $findUser['user_id'], 200);
        } else {
            return apiResponseClass::sendError('Account not found', 'Username not found');
        }
    }

    public function changePassword($newPassword, User $user)
    {
        $user->password = Hash::make($newPassword);
    }
}
