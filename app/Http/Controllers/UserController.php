<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('username', $request->username)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => ['The provided credentials are incorrect.'],
                ],404);
            }
        
            return $user->createToken($user->username)->plainTextToken;
            
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ],422);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:50',
            'email' => 'required|email',
            'password' => 'required|max:50',
            'status' => ['required', 'in:default,editor,admin'],
        ]);
   
        try{
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);
            return response()->json([
                'username' => $user->username,
                'status' => 'Success'
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => $e
            ]);
        } 
    }

    public function logout(Request $request)
    {
        try{
            return $request->user()->currentAccessToken()->delete();
        }catch(Exception $e){
            return response()->json([
                "error" => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}