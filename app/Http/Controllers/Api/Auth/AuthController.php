<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    /**
     * Register User Method
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8|max:25',
            'password_confirmation' => 'required|min:8|max:25',
            'type' => 'nullable|integer'
        ]);

        if ($validator->fails()){
            return validation_response($validator->errors()->getMessages());
        }

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role_id' => $request->input('type') ?? User::Role['Customer'],
            ]);

            if (empty($user)){
                throw new Exception('Could not create user');
            }

            $user = new UserResource($user->load('role'));
            return json_response('Success', ResponseAlias::HTTP_OK, $user, 'You have been successfully register', true);

        } catch (Exception $exception) {
            return json_response('Failed', ResponseAlias::HTTP_BAD_REQUEST, '', $exception->getMessage(), false);
        }

    }

    /**
     * Login User Method
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:55',
            'password' => 'required|min:8|max:15'
        ]);

        if ($validator->fails()){
            return validation_response($validator->errors()->getMessages());
        }

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        {
            $user = Auth::user()->load('role');
            $token = $user->createToken('Ecommerce')->plainTextToken;
            $user = new UserResource($user);
            $data = [
                'token' => $token,
                'user' => $user,
            ];

            return json_response('Success', ResponseAlias::HTTP_OK, $data, 'You have been successfully log in', true);
        }
        return json_response('Failed', ResponseAlias::HTTP_UNAUTHORIZED, '', 'Wrong Credentials', false);
    }

    /**
     * Logout User Method
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return json_response('Success', ResponseAlias::HTTP_OK, '', 'You have logout successfully', true);
    }
}
