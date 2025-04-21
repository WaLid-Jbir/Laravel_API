<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register a new user.
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
            return ResponseHelper::error(message: 'Something went wrong! Please try again.', statusCode: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
