<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

public function processRegister(Request $request)
{
    // Validate the form data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'phone' => 'required|string|max:15|unique:users,phone',
        'address' => 'required|string',
    ]);

    // If validation fails
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Create the user (role will be set to 'member' by default)
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'role' => 'member',  // Set default role as 'member'
    ]);

    // Log the user in
    Auth::login($user);  // This will authenticate the user and create a session

    // Redirect to CRUD page after successful registration
    return redirect()->route('crud.index'); // Redirect to the desired route after registration
}   
}
