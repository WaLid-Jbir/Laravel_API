<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Function: Register a new user.
     * @param RegisterRequest $request
     * @return JSONResponse
     */
    public function register(RegisterRequest $request)
    {
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);

            if($user){
                return ResponseHelper::success(
                    message: 'User has been registered successfully!', 
                    data: $user, 
                    statusCode: 201
                );
            }

            return ResponseHelper::error(message: 'Unable to register user! Please try again.', statusCode: 201);
        }
        catch(\Exception $e){
            Log::error('Unable to Register User : ' . $e->getMessage() . ' - on line ' . $e->getLine());
            return ResponseHelper::error(message: 'Something went wrong! Please try again.' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Login user.
     * @param LoginRequest $request
     * @return JSONResponse
     */
    public function login(LoginRequest $request)
    {
        try {

            $isUser = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
            // Check if user is already authenticated
            if(!$isUser){
                return ResponseHelper::error(message: 'Invalid email or password!', statusCode: 400);
            }
            $user = Auth::user();

            // create a token for the user
            $token = $user->createToken('My API Token')->plainTextToken;

            $authUser = [
                'user' => $user,
                'token' => $token
            ];

            return ResponseHelper::success(
                message: 'User has been logged in successfully!',
                data: $authUser,
                statusCode: 200
            );
        } catch (\Exception $e) {
            Log::error('Unable to Login User : ' . $e->getMessage() . ' - on line ' . $e->getLine());
            return ResponseHelper::error(message: 'Something went wrong! Please try again.' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Get user profile data / Authenticated user data.
     * @param None
     * @return JSONResponse
     */
    public function userProfile()
    {
        try {
            $user = Auth::user();
            if($user){
                return ResponseHelper::success(
                    message: 'User profile data fetched successfully!',
                    data: $user,
                    statusCode: 200
                );
            }
            return ResponseHelper::error(message: 'Unable to fetch user profile data due to invalid token.', statusCode: 400);
        } catch (\Exception $e) {
            Log::error('Unable to fetch user profile data : ' . $e->getMessage() . ' - on line ' . $e->getLine());
            return ResponseHelper::error(message: 'Something went wrong! Please try again.' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: User logout.
     * @param None
     * @return JSONResponse
     */
    public function logout()
    {
        try {
            $user = Auth::user();
            if($user){
                // delete the token that was used to authenticate the current request...
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(
                    message: 'User logged out successfully!',
                    statusCode: 200
                );
            }
            return ResponseHelper::error(message: 'Unable to logout.', statusCode: 400);
        } catch (\Exception $e) {
            Log::error('Unable to logout : ' . $e->getMessage() . ' - on line ' . $e->getLine());
            return ResponseHelper::error(message: 'Something went wrong! Please try again.' . $e->getMessage(), statusCode: 500);
        }
    }
}
