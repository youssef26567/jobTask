<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;



class userController extends Controller
{
    use ValidatesRequests;

    /**

     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'nullable',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
            'code' => Str::random(6), // Generates a random 6-character code
            'is_verified' => false, // Add this to mark the user as unverified
        ]);
        Log::info(`Verification code for user {$user->id}: {$user->code}`);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'code' => $user->code,
        ], 201);
    }

    public function verifyCode(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string|size:6',
        ]);

        $user = User::find($validatedData['user_id']);

        if ($user->code === $validatedData['code']) {
            $user->is_verified = true;
            $user->save();

            return response()->json(['message' => 'User verified successfully.']);
        } else {
            return response()->json(['message' => 'Invalid verification code.'], 422);
        }
    }


    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|name',
            'password' => 'required',
        ]);

        $user = User::where('name', $validatedData['name'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        if (!$user->is_verified) {
            return response()->json(['message' => 'Account not verified.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user'=> $user,
        ]);
    }

}
