<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController
{
  // POST [name, email, password]
  public function register(Request $request)
  {
    // Validation
    $request->validate([
      "name" => "required|string",
      "email" => "required|string|email|unique:users",
      "password" => "required|confirmed" // password_confirmation
    ]);

    // Create User
    User::create([
      "name" => $request->name,
      "email" => $request->email,
      "password" => bcrypt($request->password)
    ]);

    return response()->json([
      "status" => true,
      "message" => "User registered successfully",
      "data" => []
    ]);

  }

  public function login(Request $request)
  {
    // POST [email, password]
    // Validation
    $request->validate([
      'email' => 'required|email|string',
      'password' => 'required'
    ]);

    // Email check
    $user = User::where("email", $request->email)->first();

    if (!empty($user)) {
      // User exists
      if (Hash::check($request->password, $user->password)) {
        // Password matched
        $token = $user->createToken("myAccessToken")->plainTextToken;

        return response()->json([
          "status" => true,
          "message" => "Login successful",
          "token" => $token,
          "data" => []
        ]);
      } else {
        return response()->json([
          "status" => false,
          "message" => "Password didn't match",
          "data" => []
        ]);
      }
    } else {
      return response()->json([
        "status" => false,
        "message" => "Invalid Email value",
        "data" => []
      ]);
    }
  }

  public function profile()
  {
    $userData = auth()->user();

    return response()->json([
      "status" => true,
      "message" => "Profile information",
      "data" => $userData,
      "id" => auth()->user()->id
    ]);
  }

  public function logout()
  {
    auth()->user()->tokens()->delete();

    return response()->json([
      "status" => true,
      "message" => "User Logged out successfully",
      "data" => []
    ]);
  }
}
